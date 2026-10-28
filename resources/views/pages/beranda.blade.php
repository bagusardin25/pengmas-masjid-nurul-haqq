<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Masjid Nurul Haqq — Griya Taman Asri, Sidoarjo</title>
<meta name="description" content="Masjid Jami Nurul Haqq: jadwal sholat, kegiatan, Program TPQ, dan layanan ZISWAF untuk jamaah Griya Taman Asri, Tawangsari, Sidoarjo.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;family=Amiri:wght@400;700&amp;display=swap">
@if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@endif
<style>
html{scroll-behavior:smooth}
body{margin:0}
*{box-sizing:border-box}
a{color:#0B3B2C}a:hover{color:#135C43}
@media (max-width:640px){.hero-media{padding-bottom:0!important}.hero-quote{position:relative!important;left:auto!important;bottom:auto!important;width:auto!important;margin:-64px 16px 0!important}.tpq-media{min-height:0!important;aspect-ratio:3025/2363}}
</style>
</head>
<body>
<div style="min-height:100vh;display:flex;flex-direction:column;background:#F8F6EF;color:#17231E;font-family:'Plus Jakarta Sans',system-ui,sans-serif;font-size:16px;line-height:1.6;overflow-x:hidden">

<x-site-header />

<main id="main-content" style="flex:0 0 auto">

<section id="beranda" style="padding:56px 0 136px;background:#FFFFFF">
<div style="max-width:1440px;margin:0 auto;padding-inline:clamp(20px,4.5vw,64px);display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,480px),1fr));gap:56px;align-items:center">
<div>
<p style="margin:0;display:inline-flex;align-items:center;gap:8px;padding:7px 14px;border-radius:999px;background:#EEF5F1;color:#0B3B2C;font-size:12px;font-weight:700;letter-spacing:0.1em">MASJID JAMI · YAYASAN NURUL HAQQ</p>
<h1 style="margin:22px 0 0;font-size:clamp(48px,6vw,84px);line-height:1.02;font-weight:800;letter-spacing:-0.025em;color:#0B3B2C">Masjid<br>Nurul Haqq</h1>
<p style="margin:22px 0 0;max-width:540px;font-size:18px;line-height:1.7;color:#42524B">Tempat sholat berjamaah lima waktu, sholat Jumat, kajian kitab, dan pendidikan Al-Qur'an bagi warga Griya Taman Asri, Tawangsari. Terbuka untuk umum.</p>
<p style="margin:24px 0 0;max-width:520px;display:flex;gap:10px;align-items:flex-start;font-size:15px;line-height:1.6;color:#42524B"><span style="flex-shrink:0;margin-top:1px;color:#8A6A12"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg></span><span>Griya Taman Asri Blok FF 08-09 RT 32 RW 08, Tawangsari, Kec. Taman, Kabupaten Sidoarjo, Jawa Timur</span></p>
<div style="margin-top:32px;display:flex;flex-wrap:wrap;gap:12px">
<a href="{{ route('jadwal') }}" style="display:inline-flex;align-items:center;gap:10px;min-height:52px;padding:0 24px;border-radius:14px;background:#0B3B2C;color:#FFFFFF;font-size:16px;font-weight:700;text-decoration:none"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"></rect><path d="M16 2v4M8 2v4M3 10h18"></path></svg>Jadwal Kegiatan</a>
<a href="{{ route('kontak') }}" style="display:inline-flex;align-items:center;gap:10px;min-height:52px;padding:0 24px;border-radius:14px;border:1.5px solid #0B3B2C;color:#0B3B2C;font-size:16px;font-weight:700;text-decoration:none"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>Hubungi Pengurus</a>
</div>
</div>
<div class="hero-media" style="position:relative;padding-bottom:40px">
<figure style="margin:0;aspect-ratio:6/5;border-radius:320px 320px 28px 28px;overflow:hidden;background-color:#E4ECE6">
<img src="{{ asset('images/beranda/hero-masjid-pintu-1200.jpg') }}" srcset="{{ asset('images/beranda/hero-masjid-pintu-720.jpg') }} 720w, {{ asset('images/beranda/hero-masjid-pintu-1200.jpg') }} 1200w" sizes="(min-width: 1100px) 640px, calc(100vw - 40px)" width="1200" height="1000" alt="Pintu masuk Masjid Nurul Haqq dengan papan nama dan fasad yang diterangi lampu pada malam hari" fetchpriority="high" decoding="async" style="display:block;width:100%;height:100%;object-fit:cover">
</figure>
<blockquote class="hero-quote" style="position:absolute;left:24px;bottom:0;margin:0;width:min(400px,calc(100% - 48px));padding:22px 24px;border-radius:20px;background:#0B3B2C;color:#FFFFFF;box-shadow:0 20px 44px rgba(11,59,44,0.28)">
<p lang="ar" dir="rtl" style="margin:0;font-family:'Amiri',serif;font-size:22px;line-height:1.8;color:#E2B84B">إِنَّمَا يَعْمُرُ مَسَاجِدَ اللَّهِ مَنْ آمَنَ بِاللَّهِ وَالْيَوْمِ الْآخِرِ</p>
<p style="margin:8px 0 0;font-size:14px;line-height:1.6;color:#E3ECE7">“Sesungguhnya yang memakmurkan masjid-masjid Allah hanyalah orang yang beriman kepada Allah dan hari akhir…”</p>
<cite style="display:block;margin-top:10px;font-style:normal;font-size:12px;font-weight:700;letter-spacing:0.12em;color:#E2B84B">QS. AT-TAUBAH: 18</cite>
</blockquote>
</div>
</div>
</section>

<section aria-labelledby="judul-sholat" style="position:relative;margin-top:-84px">
<div style="max-width:1440px;margin:0 auto;padding-inline:clamp(20px,4.5vw,64px)">
<div style="display:flex;flex-wrap:wrap;align-items:center;gap:24px 40px;padding:28px 32px;border-radius:24px;background:#0B3B2C;color:#FFFFFF;box-shadow:0 24px 48px rgba(11,59,44,0.18)">
<div style="flex:1 1 260px">
<h2 id="judul-sholat" style="margin:0;font-size:12px;font-weight:700;letter-spacing:0.14em;color:#E2B84B">JADWAL SHOLAT HARI INI</h2>
<p style="margin:6px 0 0;font-size:21px;font-weight:800;line-height:1.3">Selasa, 6 Oktober 2026</p>
<p style="margin:2px 0 0;font-size:14px;color:#B9CCC3">24 Rabiul Akhir 1448 H · Sidoarjo dan sekitarnya</p>
<p style="margin:12px 0 0;display:inline-flex;align-items:center;gap:8px;padding:6px 12px;border-radius:999px;background:rgba(226,184,75,0.16);color:#F3D88C;font-size:13px;font-weight:700"><span style="width:8px;height:8px;border-radius:999px;background:#E2B84B"></span>Dzuhur dalam 32 menit</p>
</div>
<ol style="flex:3 1 560px;min-width:0;list-style:none;margin:0;padding:0;display:grid;grid-template-columns:repeat(auto-fit,minmax(92px,1fr));gap:10px">
<li style="padding:14px 10px;border-radius:16px;background:rgba(255,255,255,0.07);text-align:center"><span style="display:block;font-size:13px;font-weight:600;color:#B9CCC3">Subuh</span><span style="display:block;margin-top:2px;font-size:23px;font-weight:800;letter-spacing:-0.01em">03:56</span></li>
<li style="padding:14px 10px;border-radius:16px;background:rgba(255,255,255,0.07);text-align:center"><span style="display:block;font-size:13px;font-weight:600;color:#B9CCC3">Terbit</span><span style="display:block;margin-top:2px;font-size:23px;font-weight:800;letter-spacing:-0.01em">05:10</span></li>
<li aria-current="time" style="padding:14px 10px;border-radius:16px;background:#E2B84B;color:#0B3B2C;text-align:center"><span style="display:block;font-size:13px;font-weight:800">Dzuhur</span><span style="display:block;margin-top:2px;font-size:23px;font-weight:800;letter-spacing:-0.01em">11:20</span></li>
<li style="padding:14px 10px;border-radius:16px;background:rgba(255,255,255,0.07);text-align:center"><span style="display:block;font-size:13px;font-weight:600;color:#B9CCC3">Ashar</span><span style="display:block;margin-top:2px;font-size:23px;font-weight:800;letter-spacing:-0.01em">14:25</span></li>
<li style="padding:14px 10px;border-radius:16px;background:rgba(255,255,255,0.07);text-align:center"><span style="display:block;font-size:13px;font-weight:600;color:#B9CCC3">Maghrib</span><span style="display:block;margin-top:2px;font-size:23px;font-weight:800;letter-spacing:-0.01em">17:26</span></li>
<li style="padding:14px 10px;border-radius:16px;background:rgba(255,255,255,0.07);text-align:center"><span style="display:block;font-size:13px;font-weight:600;color:#B9CCC3">Isya</span><span style="display:block;margin-top:2px;font-size:23px;font-weight:800;letter-spacing:-0.01em">18:35</span></li>
</ol>
</div>
</div>
</section>

<section id="profil" style="padding:88px 0 96px">
<div style="max-width:1440px;margin:0 auto;padding-inline:clamp(20px,4.5vw,64px)">
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,460px),1fr));gap:48px 64px;align-items:start">
<div>
<p style="margin:0;display:flex;align-items:center;gap:10px;font-size:12px;font-weight:700;letter-spacing:0.14em;color:#8A6A12"><span style="width:24px;height:2px;background:#C9971C"></span>PROFIL MASJID</p>
<h2 style="margin:14px 0 0;font-size:clamp(30px,3.4vw,44px);line-height:1.15;font-weight:800;letter-spacing:-0.02em;color:#0B3B2C">Rumah ibadah warga Griya Taman Asri</h2>
<p style="margin:20px 0 0;font-size:17px;line-height:1.75;color:#42524B">Masjid Jami Nurul Haqq berada di bawah naungan Yayasan Nurul Haqq dan melayani jamaah di lingkungan Griya Taman Asri, Tawangsari. Selain sholat berjamaah, masjid menjadi tempat pengajian, pendidikan Al-Qur'an anak, dan pengelolaan dana umat.</p>
<p style="margin:16px 0 0;font-size:17px;line-height:1.75;color:#42524B">Pembangunan masjid dimulai pada Ahad, 1 Rajab 1431 H (13 Juni 2010) dan diresmikan pada Ahad, 28 Rajab 1439 H (15 April 2018). Kini masjid dikelola Takmir Masjid Nurul Haqq periode 2025–2030 bersama warga RT 30, RT 32, dan RT 33 RW 08.</p>
<a href="#profil" style="margin-top:20px;display:inline-flex;align-items:center;gap:8px;min-height:44px;font-size:15px;font-weight:700;color:#0B3B2C;text-decoration:none">Baca profil lengkap<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg></a>
</div>
<div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px">
<div style="padding:24px;border-radius:20px;background:#FFFFFF;border:1px solid #E6E2D6">
<span style="width:46px;height:46px;border-radius:14px;background:#EEF5F1;color:#0B3B2C;display:flex;align-items:center;justify-content:center"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"></path></svg></span>
<p style="margin:16px 0 0;font-size:30px;font-weight:800;line-height:1.1;letter-spacing:-0.01em;color:#0B3B2C">270 m²</p>
<p style="margin:6px 0 0;font-size:14px;color:#4A5A53">Luas tanah</p>
</div>
<div style="padding:24px;border-radius:20px;background:#FFFFFF;border:1px solid #E6E2D6">
<span style="width:46px;height:46px;border-radius:14px;background:#EEF5F1;color:#0B3B2C;display:flex;align-items:center;justify-content:center"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><path d="M9 22V12h6v10"></path></svg></span>
<p style="margin:16px 0 0;font-size:30px;font-weight:800;line-height:1.1;letter-spacing:-0.01em;color:#0B3B2C">150 m²</p>
<p style="margin:6px 0 0;font-size:14px;color:#4A5A53">Luas bangunan</p>
</div>
<div style="padding:24px;border-radius:20px;background:#FFFFFF;border:1px solid #E6E2D6">
<span style="width:46px;height:46px;border-radius:14px;background:#EEF5F1;color:#0B3B2C;display:flex;align-items:center;justify-content:center"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg></span>
<p style="margin:16px 0 0;font-size:30px;font-weight:800;line-height:1.1;letter-spacing:-0.01em;color:#0B3B2C">600</p>
<p style="margin:6px 0 0;font-size:14px;color:#4A5A53">Daya tampung jamaah</p>
</div>
<div style="padding:24px;border-radius:20px;background:#FFFFFF;border:1px solid #E6E2D6">
<span style="width:46px;height:46px;border-radius:14px;background:#EEF5F1;color:#0B3B2C;display:flex;align-items:center;justify-content:center"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path><path d="M14 2v6h6"></path><path d="m9 15 2 2 4-4"></path></svg></span>
<p style="margin:16px 0 0;font-size:30px;font-weight:800;line-height:1.1;letter-spacing:-0.01em;color:#0B3B2C">Wakaf</p>
<p style="margin:6px 0 0;font-size:14px;color:#4A5A53">Status tanah</p>
</div>
</div>
</div>

<div style="margin-top:64px">
<div style="display:flex;flex-wrap:wrap;align-items:baseline;justify-content:space-between;gap:8px 24px">
<h3 style="margin:0;font-size:22px;font-weight:800;color:#0B3B2C">Fasilitas</h3>
<p style="margin:0;font-size:15px;color:#4A5A53">Sarana yang tersedia untuk kenyamanan ibadah jamaah</p>
</div>
<ul style="list-style:none;margin:20px 0 0;padding:0;display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px">
<li style="display:flex;align-items:center;gap:14px;padding:16px 18px;border-radius:16px;background:#FFFFFF;border:1px solid #E6E2D6;font-size:15px;font-weight:700;color:#17231E"><span style="width:42px;height:42px;border-radius:12px;background:#0B3B2C;color:#E2B84B;display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 20.5h18"></path><path d="M5.5 20.5v-6.2a6.5 6.5 0 0 1 13 0v6.2"></path><path d="M12 7.8V4.6"></path><circle cx="12" cy="3.4" r="1"></circle><path d="M10 20.5v-3a2 2 0 0 1 4 0v3"></path></svg></span>Ruang sholat</li>
<li style="display:flex;align-items:center;gap:14px;padding:16px 18px;border-radius:16px;background:#FFFFFF;border:1px solid #E6E2D6;font-size:15px;font-weight:700;color:#17231E"><span style="width:42px;height:42px;border-radius:12px;background:#0B3B2C;color:#E2B84B;display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22a7 7 0 0 0 7-7c0-2-1-3.9-3-5.5s-3.5-4-4-6.5c-.5 2.5-2 4.9-4 6.5C6 11.1 5 13 5 15a7 7 0 0 0 7 7z"></path></svg></span>Tempat wudhu</li>
<li style="display:flex;align-items:center;gap:14px;padding:16px 18px;border-radius:16px;background:#FFFFFF;border:1px solid #E6E2D6;font-size:15px;font-weight:700;color:#17231E"><span style="width:42px;height:42px;border-radius:12px;background:#0B3B2C;color:#E2B84B;display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg></span>Perpustakaan</li>
<li style="display:flex;align-items:center;gap:14px;padding:16px 18px;border-radius:16px;background:#FFFFFF;border:1px solid #E6E2D6;font-size:15px;font-weight:700;color:#17231E"><span style="width:42px;height:42px;border-radius:12px;background:#0B3B2C;color:#E2B84B;display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 13a10 10 0 0 1 14 0"></path><path d="M8.5 16.5a5 5 0 0 1 7 0"></path><path d="M2 8.82a15 15 0 0 1 20 0"></path><path d="M12 20h.01"></path></svg></span>Akses internet</li>
<li style="display:flex;align-items:center;gap:14px;padding:16px 18px;border-radius:16px;background:#FFFFFF;border:1px solid #E6E2D6;font-size:15px;font-weight:700;color:#17231E"><span style="width:42px;height:42px;border-radius:12px;background:#0B3B2C;color:#E2B84B;display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"></rect><path d="M9 17V7h4a3 3 0 0 1 0 6H9"></path></svg></span>Area parkir</li>
<li style="display:flex;align-items:center;gap:14px;padding:16px 18px;border-radius:16px;background:#FFFFFF;border:1px solid #E6E2D6;font-size:15px;font-weight:700;color:#17231E"><span style="width:42px;height:42px;border-radius:12px;background:#0B3B2C;color:#E2B84B;display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="6.5" cy="4.5" r="1.8"></circle><path d="M4.5 22v-6H3.5l1.5-6.5h3L9.5 16h-1v6"></path><path d="M12 2v20"></path><circle cx="17.5" cy="4.5" r="1.8"></circle><path d="M15.5 22V9.5h4V22"></path></svg></span>Toilet bersih</li>
</ul>
</div>
</div>
</section>

<section id="jadwal" style="padding:96px 0;background:#FFFFFF">
<div style="max-width:1440px;margin:0 auto;padding-inline:clamp(20px,4.5vw,64px)">
<div style="display:flex;flex-wrap:wrap;align-items:flex-end;justify-content:space-between;gap:16px 32px">
<div>
<p style="margin:0;display:flex;align-items:center;gap:10px;font-size:12px;font-weight:700;letter-spacing:0.14em;color:#8A6A12"><span style="width:24px;height:2px;background:#C9971C"></span>JADWAL &amp; KEGIATAN</p>
<h2 style="margin:14px 0 0;font-size:clamp(30px,3.4vw,44px);line-height:1.15;font-weight:800;letter-spacing:-0.02em;color:#0B3B2C">Ibadah dan kajian setiap pekan</h2>
</div>
<a href="#jadwal" style="display:inline-flex;align-items:center;gap:8px;min-height:44px;font-size:15px;font-weight:700;color:#0B3B2C;text-decoration:none">Lihat semua kegiatan<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg></a>
</div>
<div style="margin-top:40px;display:flex;flex-wrap:wrap;gap:24px;align-items:stretch">
<div style="flex:1.5 1 520px;min-width:0;padding:8px 28px;border-radius:24px;background:#F8F6EF">
<h3 style="margin:18px 0 4px;font-size:13px;font-weight:700;letter-spacing:0.12em;color:#8A6A12">KEGIATAN RUTIN</h3>
<ul style="list-style:none;margin:0;padding:0">
<li style="display:flex;flex-wrap:wrap;align-items:center;gap:12px 16px;padding:18px 0;border-bottom:1px solid #E6E2D6">
<span style="width:48px;height:48px;border-radius:14px;background:#FFFFFF;color:#0B3B2C;display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 20.5h18"></path><path d="M5.5 20.5v-6.2a6.5 6.5 0 0 1 13 0v6.2"></path><path d="M12 7.8V4.6"></path><circle cx="12" cy="3.4" r="1"></circle><path d="M10 20.5v-3a2 2 0 0 1 4 0v3"></path></svg></span>
<span style="flex:1 1 180px;min-width:0"><span style="display:block;font-size:17px;font-weight:700;color:#17231E">Sholat fardhu berjamaah</span><span style="display:block;margin-top:2px;font-size:14px;color:#4A5A53">Imam rawatib bergiliran</span></span>
<span style="padding:6px 14px;border-radius:999px;background:#FFFFFF;border:1px solid #E6E2D6;font-size:14px;font-weight:600;color:#2F4038">5 waktu sehari</span>
</li>
<li style="display:flex;flex-wrap:wrap;align-items:center;gap:12px 16px;padding:18px 0;border-bottom:1px solid #E6E2D6">
<span style="width:48px;height:48px;border-radius:14px;background:#FFFFFF;color:#0B3B2C;display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"></path><path d="m3.3 7 8.7 5 8.7-5"></path><path d="M12 22V12"></path></svg></span>
<span style="flex:1 1 180px;min-width:0"><span style="display:block;font-size:17px;font-weight:700;color:#17231E">Sholat Jumat</span><span style="display:block;margin-top:2px;font-size:14px;color:#4A5A53">Khotib &amp; imam terjadwal oleh takmir</span></span>
<span style="padding:6px 14px;border-radius:999px;background:#FFFFFF;border:1px solid #E6E2D6;font-size:14px;font-weight:600;color:#2F4038">Setiap Jumat</span>
</li>
<li style="display:flex;flex-wrap:wrap;align-items:center;gap:12px 16px;padding:18px 0;border-bottom:1px solid #E6E2D6">
<span style="width:48px;height:48px;border-radius:14px;background:#FFFFFF;color:#0B3B2C;display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg></span>
<span style="flex:1 1 180px;min-width:0"><span style="display:block;font-size:17px;font-weight:700;color:#17231E">Ngaji Kitab Nashoihud Diniyyah</span><span style="display:block;margin-top:2px;font-size:14px;color:#4A5A53">KH. Ahmad Jazuli Sholeh, S.Ag</span></span>
<span style="display:flex;flex-wrap:wrap;gap:8px"><span style="padding:6px 14px;border-radius:999px;background:#FFFFFF;border:1px solid #E6E2D6;font-size:14px;font-weight:600;color:#2F4038">Sabtu pekan ke-1 &amp; 3 · Ba'da Subuh</span><span style="padding:6px 14px;border-radius:999px;background:#FFFFFF;border:1px solid #E6E2D6;font-size:14px;font-weight:600;color:#2F4038">Rabu pekan ke-2 &amp; 4 · Ba'da Isya'</span></span>
</li>
<li style="display:flex;flex-wrap:wrap;align-items:center;gap:12px 16px;padding:18px 0;border-bottom:1px solid #E6E2D6">
<span style="width:48px;height:48px;border-radius:14px;background:#FFFFFF;color:#0B3B2C;display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg></span>
<span style="flex:1 1 180px;min-width:0"><span style="display:block;font-size:17px;font-weight:700;color:#17231E">Ngaji Kitab Irsyadul Ibad</span><span style="display:block;margin-top:2px;font-size:14px;color:#4A5A53">Ust. Ainur Rofiq Muslimin</span></span>
<span style="padding:6px 14px;border-radius:999px;background:#FFFFFF;border:1px solid #E6E2D6;font-size:14px;font-weight:600;color:#2F4038">Ahad · Ba'da Maghrib</span>
</li>
<li style="display:flex;flex-wrap:wrap;align-items:center;gap:12px 16px;padding:18px 0;border-bottom:1px solid #E6E2D6">
<span style="width:48px;height:48px;border-radius:14px;background:#FFFFFF;color:#0B3B2C;display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg></span>
<span style="flex:1 1 180px;min-width:0"><span style="display:block;font-size:17px;font-weight:700;color:#17231E">Ngaji Kitab Riyadush Shalihin</span><span style="display:block;margin-top:2px;font-size:14px;color:#4A5A53">Ust. H. M. Ghofirin, S.Pd, M.Pd</span></span>
<span style="padding:6px 14px;border-radius:999px;background:#FFFFFF;border:1px solid #E6E2D6;font-size:14px;font-weight:600;color:#2F4038">Sabtu pekan ke-2 &amp; 4 · Ba'da Subuh</span>
</li>
<li style="display:flex;flex-wrap:wrap;align-items:center;gap:12px 16px;padding:18px 0;border-bottom:1px solid #E6E2D6">
<span style="width:48px;height:48px;border-radius:14px;background:#FFFFFF;color:#0B3B2C;display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m16 6 4 14"></path><path d="M12 6v14"></path><path d="M8 8v12"></path><path d="M4 4v16"></path></svg></span>
<span style="flex:1 1 180px;min-width:0"><span style="display:block;font-size:17px;font-weight:700;color:#17231E">Ngaji Al-Qur'an Sak Maknane</span><span style="display:block;margin-top:2px;font-size:14px;color:#4A5A53">Ust. Rachmad</span></span>
<span style="padding:6px 14px;border-radius:999px;background:#FFFFFF;border:1px solid #E6E2D6;font-size:14px;font-weight:600;color:#2F4038">Selasa · Ba'da Isya'</span>
</li>
<li style="display:flex;flex-wrap:wrap;align-items:center;gap:12px 16px;padding:18px 0">
<span style="width:48px;height:48px;border-radius:14px;background:#FFFFFF;color:#0B3B2C;display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m16 6 4 14"></path><path d="M12 6v14"></path><path d="M8 8v12"></path><path d="M4 4v16"></path></svg></span>
<span style="flex:1 1 180px;min-width:0"><span style="display:block;font-size:17px;font-weight:700;color:#17231E">Ngaji Kitab Tajwid Hidayatush Shibyan</span><span style="display:block;margin-top:2px;font-size:14px;color:#4A5A53">Ust. M. Faishal Riza, S.HI</span></span>
<span style="padding:6px 14px;border-radius:999px;background:#FFFFFF;border:1px solid #E6E2D6;font-size:14px;font-weight:600;color:#2F4038">Kamis · Ba'da Isya'</span>
</li>
</ul>
</div>
@php
    // Jadwal khotib/imam sholat Jumat 2026 dari Takmir Masjid Nurul Haqq (periode Agustus–Desember).
    $jadwalKhotibJumat = [
        '2026-08-07' => 'Ust. Fuad Mustofa, S.Pd',
        '2026-08-14' => 'Ust. Khusnul Yaqin Noor',
        '2026-08-21' => 'Ust. Abdul Rosid Achmad, S.Pd',
        '2026-08-28' => 'Ust. Achmad Muaidi Jakfar',
        '2026-09-04' => 'Ust. M. Syarif, M.Pd',
        '2026-09-11' => 'Ust. H. M. Ghofirin, S.Pd, M.Pd',
        '2026-09-18' => 'Ust. Abdul Rosid Achmad, S.Pd',
        '2026-09-25' => 'Ust. Dr. Achmad Yasin Muchtar, M.Ag',
        '2026-10-02' => 'Ust. Fuad Mustofa, S.Pd',
        '2026-10-09' => 'Ust. Adib Amrulloh Al Chafidz',
        '2026-10-16' => 'Ust. Abdul Rosid Achmad, S.Pd',
        '2026-10-23' => 'Ust. Drs. H. Ainur Rofiq',
        '2026-10-30' => 'Ust. Ibnu Abdillah, S.Ag',
        '2026-11-06' => 'Ust. Kholil Anwar, SH',
        '2026-11-13' => 'Ust. Muzamil, M.Pd',
        '2026-11-20' => 'Ust. Achmad Muaidi Jakfar',
        '2026-11-27' => 'Ust. Abdul Fattah',
        '2026-12-04' => 'Ust. H. M. Ghofirin, S.Pd, M.Pd',
        '2026-12-11' => 'Ust. Adib Amrulloh Al Chafidz',
        '2026-12-18' => 'Ust. Abdul Rosid Achmad, S.Pd',
        '2026-12-25' => 'Ust. Ibnu Abdillah, S.Ag',
    ];
    $hariIni = now('Asia/Jakarta')->toDateString();
    $jumatMendatang = array_values(array_filter(array_keys($jadwalKhotibJumat), fn ($tanggal) => $tanggal >= $hariIni));
    $jumatTerdekat = isset($jumatMendatang[0]) ? \Illuminate\Support\Carbon::parse($jumatMendatang[0])->locale('id') : null;
    $jumatBerikutnya = isset($jumatMendatang[1]) ? \Illuminate\Support\Carbon::parse($jumatMendatang[1])->locale('id') : null;
@endphp
<article style="flex:1 1 360px;min-width:0;display:flex;flex-direction:column;padding:28px;border-radius:24px;background:#0B3B2C;color:#FFFFFF">
<h3 style="margin:0;font-size:13px;font-weight:700;letter-spacing:0.12em;color:#E2B84B">AGENDA TERDEKAT</h3>
@if ($jumatTerdekat)
<div style="margin-top:20px;display:flex;gap:18px;align-items:flex-start">
<p style="margin:0;flex-shrink:0;width:84px;padding:12px 0;border-radius:16px;background:#E2B84B;color:#0B3B2C;text-align:center;line-height:1.1"><span style="display:block;font-size:36px;font-weight:800">{{ $jumatTerdekat->day }}</span><span style="display:block;margin-top:4px;font-size:13px;font-weight:800;letter-spacing:0.12em">{{ strtoupper($jumatTerdekat->translatedFormat('M')) }}</span></p>
<div style="min-width:0">
<p style="margin:0;font-size:24px;font-weight:800;line-height:1.25">Sholat Jumat</p>
<ul style="list-style:none;margin:12px 0 0;padding:0;display:flex;flex-direction:column;gap:8px;font-size:15px;color:#D5E2DC">
<li style="display:flex;align-items:center;gap:10px"><span style="color:#E2B84B;display:flex"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"></circle><path d="M20 21a8 8 0 0 0-16 0"></path></svg></span>Khotib &amp; imam: {{ $jadwalKhotibJumat[$jumatTerdekat->toDateString()] }}</li>
<li style="display:flex;align-items:center;gap:10px"><span style="color:#E2B84B;display:flex"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg></span>{{ $jumatTerdekat->translatedFormat('l, j F Y') }} · waktu Dzuhur</li>
<li style="display:flex;align-items:center;gap:10px"><span style="color:#E2B84B;display:flex"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg></span>Masjid Nurul Haqq</li>
</ul>
</div>
</div>
@else
<p style="margin:20px 0 0;font-size:24px;font-weight:800;line-height:1.25">Belum ada agenda terjadwal</p>
<p style="margin:8px 0 0;font-size:15px;color:#D5E2DC">Agenda berikutnya akan diumumkan oleh takmir.</p>
@endif
<div style="margin-top:24px;margin-bottom:24px;display:flex;flex-wrap:wrap;gap:10px">
<a href="#jadwal" style="flex:1 1 140px;display:inline-flex;align-items:center;justify-content:center;gap:8px;min-height:48px;padding:0 18px;border-radius:12px;background:#E2B84B;color:#0B3B2C;font-size:15px;font-weight:700;text-decoration:none">Detail agenda</a>
<a href="#jadwal" style="flex:1 1 160px;display:inline-flex;align-items:center;justify-content:center;gap:8px;min-height:48px;padding:0 18px;border-radius:12px;border:1.5px solid rgba(255,255,255,0.45);color:#FFFFFF;font-size:15px;font-weight:700;text-decoration:none"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"></rect><path d="M16 2v4M8 2v4M3 10h18"></path></svg>Simpan ke kalender</a>
</div>
@if ($jumatTerdekat)
<p style="margin:auto 0 0;padding-top:20px;border-top:1px solid rgba(255,255,255,0.14);font-size:14px;color:#B9CCC3">
@if ($jumatBerikutnya)
Jumat berikutnya: {{ $jumatBerikutnya->translatedFormat('j F Y') }} · {{ $jadwalKhotibJumat[$jumatBerikutnya->toDateString()] }}
@else
Belum ada agenda lain yang terjadwal.
@endif
</p>
@endif
</article>
</div>
</div>
</section>

<section id="tpq" style="padding:96px 0">
<div style="max-width:1440px;margin:0 auto;padding-inline:clamp(20px,4.5vw,64px)">
<div style="display:flex;flex-wrap:wrap;overflow:hidden;border-radius:28px;background:#FFFFFF;border:1px solid #E6E2D6">
<div style="flex:1.2 1 480px;min-width:0;padding:clamp(28px,4vw,52px)">
<p style="margin:0;display:inline-flex;padding:7px 14px;border-radius:999px;background:#F6EDD3;color:#6B520E;font-size:12px;font-weight:800;letter-spacing:0.1em">PROGRAM UNGGULAN</p>
<h2 style="margin:16px 0 0;font-size:clamp(30px,3.4vw,44px);line-height:1.15;font-weight:800;letter-spacing:-0.02em;color:#0B3B2C">Program TPQ</h2>
<p style="margin:14px 0 0;max-width:560px;font-size:17px;line-height:1.75;color:#42524B">TPQ Nurul Haqq membentuk generasi berkarakter Qur'ani melalui pembelajaran Al-Qur'an metode Tartil, dari jenjang Tartil 1–6 hingga Marhalah Ula, Wustha, Akhir, dan Diniyah.</p>
<ul style="list-style:none;margin:24px 0 0;padding:0;display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:12px 20px">
<li style="display:flex;align-items:center;gap:12px;font-size:16px;font-weight:600;color:#17231E"><span style="width:28px;height:28px;border-radius:999px;background:#EEF5F1;color:#0B3B2C;display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"></path></svg></span>Membaca Al-Qur'an dengan tartil</li>
<li style="display:flex;align-items:center;gap:12px;font-size:16px;font-weight:600;color:#17231E"><span style="width:28px;height:28px;border-radius:999px;background:#EEF5F1;color:#0B3B2C;display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"></path></svg></span>Menulis Al-Qur'an dengan benar</li>
<li style="display:flex;align-items:center;gap:12px;font-size:16px;font-weight:600;color:#17231E"><span style="width:28px;height:28px;border-radius:999px;background:#EEF5F1;color:#0B3B2C;display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"></path></svg></span>Munaqosah setiap enam bulan</li>
<li style="display:flex;align-items:center;gap:12px;font-size:16px;font-weight:600;color:#17231E"><span style="width:28px;height:28px;border-radius:999px;background:#EEF5F1;color:#0B3B2C;display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"></path></svg></span>Ekstrakurikuler jujitsu, banjari &amp; drum band</li>
</ul>
<div style="margin-top:28px;display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:12px">
<div style="padding:14px 16px;border-radius:14px;background:#F8F6EF"><p style="margin:0;font-size:12px;font-weight:700;letter-spacing:0.1em;color:#4A5A53">USIA</p><p style="margin:4px 0 0;font-size:15px;font-weight:700;color:#17231E">[rentang usia]</p></div>
<div style="padding:14px 16px;border-radius:14px;background:#F8F6EF"><p style="margin:0;font-size:12px;font-weight:700;letter-spacing:0.1em;color:#4A5A53">JADWAL</p><p style="margin:4px 0 0;font-size:15px;font-weight:700;color:#17231E">Senin–Jumat, 15.30–17.00 WIB</p></div>
<div style="padding:14px 16px;border-radius:14px;background:#F8F6EF"><p style="margin:0;font-size:12px;font-weight:700;letter-spacing:0.1em;color:#4A5A53">BIAYA</p><p style="margin:4px 0 0;font-size:15px;font-weight:700;color:#17231E">[biaya per bulan]</p></div>
</div>
<div style="margin-top:28px;display:flex;flex-wrap:wrap;gap:12px">
<a href="{{ route('tpq') }}" style="display:inline-flex;align-items:center;gap:10px;min-height:52px;padding:0 24px;border-radius:14px;background:#0B3B2C;color:#FFFFFF;font-size:16px;font-weight:700;text-decoration:none"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>Daftar TPQ</a>
<a href="https://wa.me/6281239567390" style="display:inline-flex;align-items:center;gap:10px;min-height:52px;padding:0 24px;border-radius:14px;border:1.5px solid #0B3B2C;color:#0B3B2C;font-size:16px;font-weight:700;text-decoration:none"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path></svg>Tanya via WhatsApp</a>
</div>
</div>
<figure class="tpq-media" style="position:relative;flex:1 1 400px;min-height:440px;margin:0;background-color:#142A25">
<img src="{{ asset('images/beranda/tpq-papan-visi-misi-1200.jpg') }}" srcset="{{ asset('images/beranda/tpq-papan-visi-misi-720.jpg') }} 720w, {{ asset('images/beranda/tpq-papan-visi-misi-1200.jpg') }} 1200w" sizes="(min-width: 1100px) 600px, calc(100vw - 40px)" width="1200" height="937" alt="Papan nama serta visi dan misi Taman Pendidikan Al-Qur'an (TPQ) Nurul Haqq" loading="lazy" decoding="async" style="position:absolute;inset:0;display:block;width:100%;height:100%;object-fit:contain">
</figure>
</div>
</div>
</section>

<section id="ziswaf" style="position:relative;overflow:hidden;padding:96px 0;background:#0B3B2C;color:#FFFFFF">
<svg aria-hidden="true" viewBox="0 0 200 200" fill="none" stroke="#E2B84B" stroke-width="1.2" style="position:absolute;top:-140px;right:-140px;width:540px;height:540px;opacity:0.12"><rect x="45" y="45" width="110" height="110"></rect><rect x="45" y="45" width="110" height="110" transform="rotate(45 100 100)"></rect><rect x="70" y="70" width="60" height="60"></rect><rect x="70" y="70" width="60" height="60" transform="rotate(45 100 100)"></rect><circle cx="100" cy="100" r="16"></circle><circle cx="100" cy="100" r="92"></circle></svg>
<div style="position:relative;max-width:1440px;margin:0 auto;padding-inline:clamp(20px,4.5vw,64px)">
<p style="margin:0;display:flex;align-items:center;gap:10px;font-size:12px;font-weight:700;letter-spacing:0.14em;color:#E2B84B"><span style="width:24px;height:2px;background:#E2B84B"></span>ZISWAF</p>
<h2 style="margin:14px 0 0;max-width:760px;font-size:clamp(30px,3.4vw,44px);line-height:1.15;font-weight:800;letter-spacing:-0.02em;color:#FFFFFF">Salurkan zakat, infaq, sedekah, dan wakaf</h2>
<p style="margin:16px 0 0;max-width:640px;font-size:17px;line-height:1.7;color:#C9D8D1">Zakat, infaq, dan shodaqoh dihimpun dan disalurkan oleh Lembaga Amil Zakat, Infaq, dan Shadaqoh NU (LAZISNU) Masjid Nurul Haqq Tawangsari melalui seksi pengumpulan, pengemasan, dan pembagian.</p>
<div style="margin-top:44px;display:flex;flex-wrap:wrap;gap:32px;align-items:flex-start">
<div style="flex:1.15 1 520px;min-width:0">
<h3 style="margin:0;display:flex;align-items:center;gap:12px;font-size:17px;font-weight:700"><span style="width:28px;height:28px;border-radius:999px;background:#E2B84B;color:#0B3B2C;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:800">1</span>Pilih jenis dana</h3>
<div style="margin-top:16px;display:grid;grid-template-columns:repeat(auto-fit,minmax(118px,1fr));gap:12px">
<button type="button" aria-pressed="true" style="display:flex;flex-direction:column;align-items:flex-start;gap:10px;padding:18px;border-radius:16px;border:1.5px solid #E2B84B;background:#E2B84B;color:#0B3B2C;font-family:inherit;text-align:left;cursor:pointer"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="8" cy="8" r="6"></circle><path d="M18.09 10.37A6 6 0 1 1 10.34 18"></path><path d="M7 6h1v4"></path><path d="m16.71 13.88.7.71-2.82 2.82"></path></svg><span style="font-size:17px;font-weight:800">Zakat</span><span style="font-size:13px;line-height:1.4;font-weight:600">Mal &amp; fitrah</span></button>
<button type="button" aria-pressed="false" style="display:flex;flex-direction:column;align-items:flex-start;gap:10px;padding:18px;border-radius:16px;border:1.5px solid rgba(255,255,255,0.18);background:rgba(255,255,255,0.06);color:#FFFFFF;font-family:inherit;text-align:left;cursor:pointer"><span style="color:#E2B84B;display:flex"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 14h2a2 2 0 1 0 0-4h-3c-.6 0-1.1.2-1.4.6L3 16"></path><path d="m7 20 1.6-1.4c.3-.4.8-.6 1.4-.6h4c1.1 0 2.1-.4 2.8-1.2l4.6-4.4a2 2 0 0 0-2.75-2.91l-4.2 3.9"></path><path d="m2 15 6 6"></path><path d="M19.5 8.5c.7-.7 1.5-1.6 1.5-2.7A2.73 2.73 0 0 0 16 4a2.78 2.78 0 0 0-5 1.8c0 1.2.8 2 1.5 2.8L16 12Z"></path></svg></span><span style="font-size:17px;font-weight:800">Infaq</span><span style="font-size:13px;line-height:1.4;font-weight:600;color:#B9CCC3">Kas &amp; operasional</span></button>
<button type="button" aria-pressed="false" style="display:flex;flex-direction:column;align-items:flex-start;gap:10px;padding:18px;border-radius:16px;border:1.5px solid rgba(255,255,255,0.18);background:rgba(255,255,255,0.06);color:#FFFFFF;font-family:inherit;text-align:left;cursor:pointer"><span style="color:#E2B84B;display:flex"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path></svg></span><span style="font-size:17px;font-weight:800">Sedekah</span><span style="font-size:13px;line-height:1.4;font-weight:600;color:#B9CCC3">Kepedulian sosial</span></button>
<button type="button" aria-pressed="false" style="display:flex;flex-direction:column;align-items:flex-start;gap:10px;padding:18px;border-radius:16px;border:1.5px solid rgba(255,255,255,0.18);background:rgba(255,255,255,0.06);color:#FFFFFF;font-family:inherit;text-align:left;cursor:pointer"><span style="color:#E2B84B;display:flex"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 22h18"></path><path d="M6 18v-7"></path><path d="M10 18v-7"></path><path d="M14 18v-7"></path><path d="M18 18v-7"></path><path d="M12 2l8 5H4z"></path></svg></span><span style="font-size:17px;font-weight:800">Wakaf</span><span style="font-size:13px;line-height:1.4;font-weight:600;color:#B9CCC3">Aset produktif umat</span></button>
</div>

<h3 style="margin:32px 0 0;display:flex;align-items:center;gap:12px;font-size:17px;font-weight:700"><span style="width:28px;height:28px;border-radius:999px;background:#E2B84B;color:#0B3B2C;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:800">2</span>Transfer atau scan QRIS</h3>
<div style="margin-top:16px;display:flex;flex-wrap:wrap;align-items:center;gap:20px;padding:20px;border-radius:20px;background:#FFFFFF;color:#17231E">
<figure style="margin:0;width:132px;height:132px;flex-shrink:0;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px;border:1.5px dashed #9FB3AA;border-radius:14px;background:#F4F7F5;color:#2F5C4A">
<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="5" height="5" rx="1"></rect><rect x="16" y="3" width="5" height="5" rx="1"></rect><rect x="3" y="16" width="5" height="5" rx="1"></rect><path d="M21 16h-3a2 2 0 0 0-2 2v3"></path><path d="M21 21v.01"></path><path d="M12 7v3a2 2 0 0 1-2 2H7"></path><path d="M3 12h.01"></path><path d="M12 3h.01"></path><path d="M12 16v.01"></path><path d="M16 12h1"></path><path d="M21 12v.01"></path><path d="M12 21v-1"></path></svg>
<figcaption style="font-size:12px;font-weight:700">Kode QRIS</figcaption>
</figure>
<div style="flex:1 1 220px;min-width:0">
<p style="margin:0;font-size:12px;font-weight:700;letter-spacing:0.12em;color:#4A5A53">TRANSFER BANK</p>
<p style="margin:6px 0 0;font-size:16px;font-weight:700">Bank Syariah Mandiri</p>
<p style="margin:2px 0 0;font-size:24px;font-weight:800;letter-spacing:0.02em;color:#0B3B2C">7005342184</p>
<p style="margin:2px 0 0;font-size:14px;color:#4A5A53">a.n. Yayasan Nurul Haqq</p>
<button type="button" style="margin-top:12px;display:inline-flex;align-items:center;gap:8px;min-height:44px;padding:0 16px;border-radius:12px;border:1.5px solid #0B3B2C;background:#FFFFFF;color:#0B3B2C;font-family:inherit;font-size:14px;font-weight:700;cursor:pointer"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="8" y="8" width="14" height="14" rx="2"></rect><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"></path></svg>Salin nomor</button>
</div>
</div>

<h3 style="margin:32px 0 0;display:flex;align-items:center;gap:12px;font-size:17px;font-weight:700"><span style="width:28px;height:28px;border-radius:999px;background:#E2B84B;color:#0B3B2C;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:800">3</span>Konfirmasi donasi</h3>
<div style="margin-top:16px;display:flex;flex-wrap:wrap;align-items:center;gap:12px 20px">
<a href="https://wa.me/6281239567390" style="display:inline-flex;align-items:center;gap:10px;min-height:52px;padding:0 24px;border-radius:14px;background:#E2B84B;color:#0B3B2C;font-size:16px;font-weight:800;text-decoration:none"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path></svg>Konfirmasi via WhatsApp</a>
<p style="margin:0;flex:1 1 220px;font-size:14px;line-height:1.6;color:#B9CCC3">Konfirmasi membantu pengurus mencatat dana sesuai niat Anda.</p>
</div>
</div>

<article style="flex:1 1 400px;min-width:0;padding:28px;border-radius:24px;background:#FFFFFF;color:#17231E">
<div style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:flex-start;gap:12px">
<div>
<h3 style="margin:0;font-size:12px;font-weight:700;letter-spacing:0.12em;color:#4A5A53">LAPORAN DANA</h3>
<p style="margin:4px 0 0;font-size:16px;font-weight:700">Periode Mei 2026</p>
</div>
<a href="#ziswaf" style="display:inline-flex;align-items:center;gap:6px;min-height:44px;padding:0 14px;border-radius:12px;background:#EEF5F1;color:#0B3B2C;font-size:14px;font-weight:700;text-decoration:none"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><path d="M7 10l5 5 5-5"></path><path d="M12 15V3"></path></svg>Laporan lengkap</a>
</div>
<p style="margin:24px 0 0;font-size:14px;color:#4A5A53">Total terkumpul</p>
<p style="margin:2px 0 0;font-size:clamp(32px,3vw,40px);line-height:1.15;font-weight:800;letter-spacing:-0.02em;color:#0B3B2C">Rp 23.122.000</p>
<div style="margin-top:16px;display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px">
<div style="padding:12px 14px;border-radius:14px;border:1.5px dashed #CFC8B4"><p style="margin:0;font-size:13px;color:#4A5A53">Tersalurkan</p><p style="margin:2px 0 0;font-size:17px;font-weight:800;color:#17231E">[Rp —]</p></div>
<div style="padding:12px 14px;border-radius:14px;border:1.5px dashed #CFC8B4"><p style="margin:0;font-size:13px;color:#4A5A53">Saldo</p><p style="margin:2px 0 0;font-size:17px;font-weight:800;color:#17231E">[Rp —]</p></div>
</div>
<h4 style="margin:24px 0 0;font-size:12px;font-weight:700;letter-spacing:0.12em;color:#4A5A53">RINCIAN PER JENIS</h4>
<ul style="list-style:none;margin:12px 0 0;padding:0;display:flex;flex-direction:column;gap:14px">
<li><div style="display:flex;justify-content:space-between;gap:12px;font-size:15px"><span style="font-weight:700">Zakat</span><span style="color:#4A5A53"><strong style="color:#17231E">Rp 4.875.000</strong> · 21%</span></div><div style="margin-top:6px;height:10px;border-radius:999px;background:#EDEBE3"><div style="width:56.1%;height:100%;border-radius:999px;background:#135C43"></div></div></li>
<li><div style="display:flex;justify-content:space-between;gap:12px;font-size:15px"><span style="font-weight:700">Infaq</span><span style="color:#4A5A53"><strong style="color:#17231E">Rp 6.342.000</strong> · 27%</span></div><div style="margin-top:6px;height:10px;border-radius:999px;background:#EDEBE3"><div style="width:73%;height:100%;border-radius:999px;background:#135C43"></div></div></li>
<li><div style="display:flex;justify-content:space-between;gap:12px;font-size:15px"><span style="font-weight:700">Sedekah</span><span style="color:#4A5A53"><strong style="color:#17231E">Rp 3.215.000</strong> · 14%</span></div><div style="margin-top:6px;height:10px;border-radius:999px;background:#EDEBE3"><div style="width:37%;height:100%;border-radius:999px;background:#135C43"></div></div></li>
<li><div style="display:flex;justify-content:space-between;gap:12px;font-size:15px"><span style="font-weight:700">Wakaf</span><span style="color:#4A5A53"><strong style="color:#17231E">Rp 8.690.000</strong> · 38%</span></div><div style="margin-top:6px;height:10px;border-radius:999px;background:#EDEBE3"><div style="width:100%;height:100%;border-radius:999px;background:#135C43"></div></div></li>
</ul>
</article>
</div>
</div>
</section>

<section id="galeri" style="padding:96px 0">
<div style="max-width:1440px;margin:0 auto;padding-inline:clamp(20px,4.5vw,64px)">
<div style="display:flex;flex-wrap:wrap;align-items:flex-end;justify-content:space-between;gap:16px 32px">
<div>
<p style="margin:0;display:flex;align-items:center;gap:10px;font-size:12px;font-weight:700;letter-spacing:0.14em;color:#8A6A12"><span style="width:24px;height:2px;background:#C9971C"></span>GALERI</p>
<h2 style="margin:14px 0 0;font-size:clamp(30px,3.4vw,44px);line-height:1.15;font-weight:800;letter-spacing:-0.02em;color:#0B3B2C">Momen kegiatan jamaah</h2>
</div>
<a href="#galeri" style="display:inline-flex;align-items:center;gap:8px;min-height:44px;font-size:15px;font-weight:700;color:#0B3B2C;text-decoration:none">Lihat semua galeri<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg></a>
</div>
<div style="margin-top:40px;display:flex;flex-wrap:wrap;gap:14px">
<figure style="position:relative;flex:1 1 520px;min-width:0;height:400px;margin:0;overflow:hidden;border-radius:24px;background-color:#E4ECE6">
<img src="{{ asset('images/beranda/galeri-silaturahmi-pengurus-1600.jpg') }}" srcset="{{ asset('images/beranda/galeri-silaturahmi-pengurus-960.jpg') }} 960w, {{ asset('images/beranda/galeri-silaturahmi-pengurus-1600.jpg') }} 1600w" sizes="(min-width: 1100px) 640px, calc(100vw - 40px)" width="1600" height="713" alt="Pengurus Yayasan Nurul Haqq dan Takmir Masjid Nurul Haqq berfoto bersama di dalam masjid" loading="lazy" decoding="async" style="display:block;width:100%;height:100%;object-fit:cover">
<figcaption style="position:absolute;left:0;right:0;bottom:0;padding:56px 24px 20px;background:linear-gradient(to top,rgba(8,45,34,0.88),rgba(8,45,34,0));color:#FFFFFF;font-size:15px;font-weight:700;line-height:1.4">Silaturahmi pengurus Yayasan Nurul Haqq dengan Takmir Masjid Nurul Haqq</figcaption>
</figure>
<div style="flex:1 1 520px;min-width:0;display:grid;grid-template-columns:repeat(2,minmax(0,1fr));grid-template-rows:repeat(2,193px);gap:14px">
<figure style="position:relative;margin:0;overflow:hidden;border-radius:20px;background-color:#E4ECE6"><img src="{{ asset('images/beranda/galeri-safari-dakwah-isra-miraj.jpg') }}" width="720" height="542" alt="Warga berfoto di depan spanduk Safari Dakwah Peringatan Isra Mi'raj 1446 H" loading="lazy" decoding="async" style="display:block;width:100%;height:100%;object-fit:cover;object-position:50% 100%"><figcaption style="position:absolute;left:0;right:0;bottom:0;padding:32px 14px 12px;background:linear-gradient(to top,rgba(8,45,34,0.88),rgba(8,45,34,0));color:#FFFFFF;font-size:13px;font-weight:700;line-height:1.35">Safari Dakwah Isra Mi'raj 1446 H</figcaption></figure>
<figure style="position:relative;margin:0;overflow:hidden;border-radius:20px;background-color:#E4ECE6"><img src="{{ asset('images/beranda/galeri-prasasti-peresmian.jpg') }}" width="720" height="538" alt="Prasasti Masjid Nurul Haqq: dibangun 13 Juni 2010 dan diresmikan 15 April 2018" loading="lazy" decoding="async" style="display:block;width:100%;height:100%;object-fit:cover"><figcaption style="position:absolute;left:0;right:0;bottom:0;padding:32px 14px 12px;background:linear-gradient(to top,rgba(8,45,34,0.88),rgba(8,45,34,0));color:#FFFFFF;font-size:13px;font-weight:700;line-height:1.35">Prasasti peresmian masjid, 2018</figcaption></figure>
<figure style="position:relative;margin:0;overflow:hidden;border-radius:20px;background-color:#E4ECE6"><img src="{{ asset('images/beranda/galeri-spanduk-isra-miraj.jpg') }}" width="720" height="860" alt="Warga di depan spanduk Peringatan Isra Mi'raj dan menyambut Ramadhan 1446 H di Masjid Nurul Haqq" loading="lazy" decoding="async" style="display:block;width:100%;height:100%;object-fit:cover;object-position:50% 45%"><figcaption style="position:absolute;left:0;right:0;bottom:0;padding:32px 14px 12px;background:linear-gradient(to top,rgba(8,45,34,0.88),rgba(8,45,34,0));color:#FFFFFF;font-size:13px;font-weight:700;line-height:1.35">Menyambut Ramadhan 1446 H</figcaption></figure>
<a href="#galeri" style="border-radius:20px;background:#0B3B2C;color:#FFFFFF;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:2px;text-decoration:none"><span style="font-size:34px;font-weight:800;line-height:1.1">+4</span><span style="font-size:14px;font-weight:600;color:#C9D8D1">foto lainnya</span></a>
</div>
</div>
</div>
</section>

<section id="kontak" style="padding:96px 0;background:#FFFFFF">
<div style="max-width:1440px;margin:0 auto;padding-inline:clamp(20px,4.5vw,64px)">
<div style="display:flex;flex-wrap:wrap;gap:48px 64px">
<div style="flex:1 1 460px;min-width:0">
<p style="margin:0;display:flex;align-items:center;gap:10px;font-size:12px;font-weight:700;letter-spacing:0.14em;color:#8A6A12"><span style="width:24px;height:2px;background:#C9971C"></span>KONTAK &amp; LOKASI</p>
<h2 style="margin:14px 0 0;font-size:clamp(30px,3.4vw,44px);line-height:1.15;font-weight:800;letter-spacing:-0.02em;color:#0B3B2C">Hubungi pengurus masjid</h2>
<p style="margin:14px 0 0;font-size:17px;line-height:1.7;color:#42524B">Untuk layanan ZISWAF, pendaftaran TPQ, dan kebutuhan ibadah lainnya.</p>
<ul style="list-style:none;margin:28px 0 0;padding:0;display:flex;flex-direction:column;gap:10px">
<li><a href="tel:081234567890" style="display:flex;align-items:center;gap:16px;padding:14px 18px;border-radius:16px;background:#F8F6EF;color:#17231E;text-decoration:none"><span style="width:44px;height:44px;border-radius:12px;background:#FFFFFF;color:#0B3B2C;display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg></span><span style="flex:1;min-width:0"><span style="display:block;font-size:13px;color:#4A5A53">Kontak takmir</span><span style="display:block;font-size:17px;font-weight:700">0812-3456-7890</span></span><span style="color:#6E7F77;display:flex"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg></span></a></li>
<li><a href="https://wa.me/6281239567390" style="display:flex;align-items:center;gap:16px;padding:14px 18px;border-radius:16px;background:#F8F6EF;color:#17231E;text-decoration:none"><span style="width:44px;height:44px;border-radius:12px;background:#FFFFFF;color:#0B3B2C;display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path></svg></span><span style="flex:1;min-width:0"><span style="display:block;font-size:13px;color:#4A5A53">Sekretariat (WhatsApp)</span><span style="display:block;font-size:17px;font-weight:700">0812-3956-7390</span></span><span style="color:#6E7F77;display:flex"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg></span></a></li>
<li><a href="mailto:takmirmnh22@gmail.com" style="display:flex;align-items:center;gap:16px;padding:14px 18px;border-radius:16px;background:#F8F6EF;color:#17231E;text-decoration:none"><span style="width:44px;height:44px;border-radius:12px;background:#FFFFFF;color:#0B3B2C;display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"></rect><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path></svg></span><span style="flex:1;min-width:0"><span style="display:block;font-size:13px;color:#4A5A53">Email takmir</span><span style="display:block;font-size:clamp(14px,3.9vw,17px);font-weight:700;overflow-wrap:anywhere">takmirmnh22@gmail.com</span></span><span style="color:#6E7F77;display:flex"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg></span></a></li>
<li><a href="https://instagram.com/masjidnurulhaqq" style="display:flex;align-items:center;gap:16px;padding:14px 18px;border-radius:16px;background:#F8F6EF;color:#17231E;text-decoration:none"><span style="width:44px;height:44px;border-radius:12px;background:#FFFFFF;color:#0B3B2C;display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="2" width="20" height="20" rx="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><path d="M17.5 6.5h.01"></path></svg></span><span style="flex:1;min-width:0"><span style="display:block;font-size:13px;color:#4A5A53">Instagram</span><span style="display:block;font-size:17px;font-weight:700">@masjidnurulhaqq</span></span><span style="color:#6E7F77;display:flex"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg></span></a></li>
</ul>
<p style="margin:18px 0 0;display:flex;flex-wrap:wrap;align-items:center;gap:8px;font-size:15px;color:#4A5A53"><span style="color:#8A6A12;display:flex"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg></span>Layanan sekretariat: <strong style="color:#17231E">[hari &amp; jam layanan]</strong></p>
</div>
<div style="flex:1 1 460px;min-width:0">
<div style="height:340px;overflow:hidden;border-radius:24px;background-color:#E9EEE8;border:1px solid #E6E2D6">
<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3956.9991590634045!2d112.67715659999999!3d-7.3539886999999995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7e30053c2643f%3A0x4d32a9da57f07b9e!2sMasjid%20Nurul%20Haqq!5e0!3m2!1sid!2sid!4v1793194123166!5m2!1sid!2sid" title="Peta lokasi Masjid Nurul Haqq di Google Maps" width="600" height="340" loading="lazy" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen style="display:block;width:100%;height:100%;border:0"></iframe>
</div>
<div style="margin-top:16px;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:12px 20px">
<p style="margin:0;flex:1 1 260px;display:flex;gap:10px;align-items:flex-start;font-size:15px;line-height:1.6;color:#42524B"><span style="flex-shrink:0;margin-top:1px;color:#8A6A12;display:flex"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg></span>Griya Taman Asri Blok FF 08-09 RT 32 RW 08, Tawangsari, Kec. Taman, Sidoarjo</p>
<a href="https://maps.google.com/?cid=5562695244999326622" target="_blank" rel="noopener noreferrer" style="display:inline-flex;align-items:center;gap:8px;min-height:48px;padding:0 20px;border-radius:14px;border:1.5px solid #0B3B2C;color:#0B3B2C;font-size:15px;font-weight:700;text-decoration:none"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 3h6v6"></path><path d="M10 14 21 3"></path><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path></svg>Buka di Google Maps</a>
</div>
</div>
</div>

<div style="position:relative;overflow:hidden;margin-top:72px;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:24px 40px;padding:clamp(28px,4vw,48px);border-radius:28px;background:#0B3B2C;color:#FFFFFF">
<svg aria-hidden="true" viewBox="0 0 200 200" fill="none" stroke="#E2B84B" stroke-width="1.4" style="position:absolute;left:-90px;bottom:-110px;width:300px;height:300px;opacity:0.14"><rect x="45" y="45" width="110" height="110"></rect><rect x="45" y="45" width="110" height="110" transform="rotate(45 100 100)"></rect><circle cx="100" cy="100" r="16"></circle></svg>
<div style="position:relative;flex:1 1 420px;min-width:0">
<h2 style="margin:0;font-size:clamp(26px,2.6vw,34px);line-height:1.2;font-weight:800;letter-spacing:-0.01em">Mari bersama memakmurkan masjid</h2>
<p style="margin:8px 0 0;font-size:17px;color:#C9D8D1">Untuk kebaikan dunia dan akhirat.</p>
</div>
<div style="position:relative;display:flex;flex-wrap:wrap;gap:12px">
<a href="#ziswaf" style="display:inline-flex;align-items:center;gap:10px;min-height:52px;padding:0 24px;border-radius:14px;background:#E2B84B;color:#0B3B2C;font-size:16px;font-weight:800;text-decoration:none"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path></svg>Donasi ZISWAF</a>
<a href="#jadwal" style="display:inline-flex;align-items:center;gap:10px;min-height:52px;padding:0 24px;border-radius:14px;border:1.5px solid rgba(255,255,255,0.5);color:#FFFFFF;font-size:16px;font-weight:700;text-decoration:none">Lihat jadwal</a>
</div>
</div>
</div>
</section>

</main>

<x-site-footer />

</div>
</body>
</html>