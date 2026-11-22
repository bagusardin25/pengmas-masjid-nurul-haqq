import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

/**
 * Isian foto di dashboard: menampilkan pratinjau dan memperkecil foto besar di browser
 * (sisi terpanjang maks. 1600 px, JPEG) sebelum diunggah, karena batas unggah server 2 MB.
 * Bila browser tidak bisa membaca berkasnya (mis. HEIC), berkas asli dikirim apa adanya
 * dan validasi server yang memberi tahu.
 */
Alpine.data('photoUpload', ({ maxSide = 1600, quality = 0.85, targetBytes = 1.5 * 1024 * 1024 } = {}) => ({
    preview: null,
    info: '',
    busy: false,

    async pick(event) {
        const input = event.target;
        const file = input.files?.[0];

        if (this.preview) {
            URL.revokeObjectURL(this.preview);
        }
        this.preview = null;
        this.info = '';

        if (!file) {
            return;
        }

        this.busy = true;

        try {
            const bitmap = await createImageBitmap(file);
            const scale = Math.min(1, maxSide / Math.max(bitmap.width, bitmap.height));
            let result = file;

            if (scale < 1 || file.size > targetBytes) {
                const canvas = document.createElement('canvas');
                canvas.width = Math.round(bitmap.width * scale);
                canvas.height = Math.round(bitmap.height * scale);

                const context = canvas.getContext('2d');
                context.fillStyle = '#ffffff';
                context.fillRect(0, 0, canvas.width, canvas.height);
                context.drawImage(bitmap, 0, 0, canvas.width, canvas.height);

                const blob = await new Promise((resolve) => canvas.toBlob(resolve, 'image/jpeg', quality));

                if (blob) {
                    const name = file.name.replace(/\.[^.]+$/, '') + '.jpg';
                    result = new File([blob], name, { type: 'image/jpeg', lastModified: Date.now() });

                    const transfer = new DataTransfer();
                    transfer.items.add(result);
                    input.files = transfer.files;
                }
            }

            const width = Math.round(bitmap.width * scale);
            const height = Math.round(bitmap.height * scale);
            bitmap.close?.();

            this.preview = URL.createObjectURL(result);
            this.info = `${width} × ${height} px · ${Math.max(1, Math.round(result.size / 1024))} KB`;
        } catch {
            this.info = 'Pratinjau tidak tersedia untuk berkas ini.';
        } finally {
            this.busy = false;
        }
    },
}));

/**
 * Perkecil satu foto (sisi terpanjang maks. maxSide px, JPEG). Berkas yang sudah kecil dikirim apa adanya.
 * Mengembalikan null bila browser tidak bisa membaca berkasnya (mis. HEIC).
 */
async function shrinkPhoto(file, { maxSide = 1600, quality = 0.85, targetBytes = 1.5 * 1024 * 1024 } = {}) {
    let bitmap;

    try {
        bitmap = await createImageBitmap(file);
    } catch {
        return null;
    }

    const scale = Math.min(1, maxSide / Math.max(bitmap.width, bitmap.height));

    if (scale === 1 && file.size <= targetBytes) {
        bitmap.close?.();
        return file;
    }

    const canvas = document.createElement('canvas');
    canvas.width = Math.round(bitmap.width * scale);
    canvas.height = Math.round(bitmap.height * scale);

    const context = canvas.getContext('2d');
    context.fillStyle = '#ffffff';
    context.fillRect(0, 0, canvas.width, canvas.height);
    context.drawImage(bitmap, 0, 0, canvas.width, canvas.height);
    bitmap.close?.();

    const blob = await new Promise((resolve) => canvas.toBlob(resolve, 'image/jpeg', quality));

    if (!blob) {
        return file;
    }

    const name = file.name.replace(/\.[^.]+$/, '') + '.jpg';

    return new File([blob], name, { type: 'image/jpeg', lastModified: Date.now() });
}

/**
 * Unggah banyak foto sekaligus (album galeri): setiap foto diperkecil di browser, lalu total ukurannya
 * diperiksa agar tidak melebihi batas kiriman server (post_max_size bawaan PHP 8 MB).
 */
Alpine.data('multiPhotoUpload', ({ max = 10, maxTotalBytes = 7.5 * 1024 * 1024 } = {}) => ({
    previews: [],
    message: '',
    blocked: false,
    busy: false,

    async pick(event) {
        const input = event.target;
        const files = Array.from(input.files ?? []);

        this.previews.forEach((item) => URL.revokeObjectURL(item.url));
        this.previews = [];
        this.message = '';
        this.blocked = false;

        if (files.length === 0) {
            return;
        }

        if (files.length > max) {
            this.blocked = true;
            this.message = `Terpilih ${files.length} foto. Maksimal ${max} foto sekali unggah, pilih ulang sebagian dulu.`;
            return;
        }

        this.busy = true;

        try {
            const results = [];
            const unreadable = [];

            for (const file of files) {
                const result = await shrinkPhoto(file);

                if (result === null) {
                    unreadable.push(file.name);
                } else {
                    results.push(result);
                }
            }

            if (unreadable.length > 0) {
                this.blocked = true;
                this.message = `Berkas ini bukan foto JPG, PNG, atau WebP: ${unreadable.join(', ')}.`;
                return;
            }

            const transfer = new DataTransfer();
            results.forEach((file) => transfer.items.add(file));
            input.files = transfer.files;

            const total = results.reduce((sum, file) => sum + file.size, 0);
            const megabytes = (total / 1024 / 1024).toLocaleString('id-ID', { maximumFractionDigits: 1 });

            this.previews = results.map((file) => ({ url: URL.createObjectURL(file) }));

            if (total > maxTotalBytes) {
                this.blocked = true;
                this.message = `Total ${megabytes} MB terlalu besar untuk sekali unggah. Kurangi jumlah fotonya.`;
            } else {
                this.message = `${results.length} foto siap diunggah · ${megabytes} MB`;
            }
        } finally {
            this.busy = false;
        }
    },
}));

/**
 * Penampil foto album di halaman Galeri: klik foto untuk memperbesar, panah kiri/kanan untuk berpindah.
 */
Alpine.data('lightbox', (items = []) => ({
    items,
    open: null,
    trigger: null,

    get current() {
        return this.open === null ? null : this.items[this.open];
    },

    show(index) {
        this.trigger = document.activeElement;
        this.open = index;
        document.body.style.overflow = 'hidden';
        this.$nextTick(() => this.$root.querySelector('.lightbox-close')?.focus());
    },

    close() {
        if (this.open === null) {
            return;
        }

        this.open = null;
        document.body.style.overflow = '';
        this.trigger?.focus();
    },

    next() {
        this.open = (this.open + 1) % this.items.length;
    },

    prev() {
        this.open = (this.open - 1 + this.items.length) % this.items.length;
    },
}));

/**
 * Pratinjau halaman publik versi HP di sisi kanan dashboard. Iframe hanya dimuat bila panelnya
 * terlihat (layar lebar dan tidak disembunyikan), lalu diperkecil agar seluruh layar HP muat.
 * Pilihan sembunyikan/tampilkan diingat di browser.
 */
const PREVIEW_STORAGE_KEY = 'dashboard-preview-collapsed';
const PREVIEW_PHONE = { width: 390, height: 844 };

Alpine.data('livePreview', (url) => ({
    url,
    collapsed: false,
    loading: false,
    scale: 1,

    init() {
        try {
            this.collapsed = localStorage.getItem(PREVIEW_STORAGE_KEY) === '1';
        } catch {
            this.collapsed = false;
        }

        const wide = window.matchMedia('(min-width: 1480px)');
        const update = () => {
            this.fit();
            this.load();
        };

        wide.addEventListener('change', update);
        window.addEventListener('resize', () => this.fit());
        this.$nextTick(update);
    },

    isVisible() {
        return !this.collapsed && window.matchMedia('(min-width: 1480px)').matches;
    },

    load(force = false) {
        const frame = this.$refs.frame;

        if (!frame || !this.isVisible() || (frame.getAttribute('src') && !force)) {
            return;
        }

        this.loading = true;
        frame.src = force ? `${this.url}${this.url.includes('?') ? '&' : '?'}_preview=${Date.now()}` : this.url;
    },

    reload() {
        this.load(true);
    },

    fit() {
        const stage = this.$refs.stage;

        if (!stage || !this.isVisible()) {
            return;
        }

        const frameBorder = 12 + 8; // bingkai HP (2 × 6 px) + jarak aman
        const scaleX = (stage.clientWidth - frameBorder) / PREVIEW_PHONE.width;
        const scaleY = (stage.clientHeight - frameBorder) / PREVIEW_PHONE.height;
        this.scale = Math.max(0.4, Math.min(1, scaleX, scaleY));
    },

    toggle() {
        this.collapsed = !this.collapsed;

        try {
            localStorage.setItem(PREVIEW_STORAGE_KEY, this.collapsed ? '1' : '0');
        } catch {
            // Penyimpanan browser tidak tersedia; pilihan hanya berlaku sampai halaman dimuat ulang.
        }

        this.$nextTick(() => {
            this.fit();
            this.load();
        });
    },
}));

/**
 * Daftar panjang di dashboard ditampilkan bertahap (mis. 5 per klik "Tampilkan lebih banyak").
 * Jumlah yang sudah dibuka diingat selama tab terbuka, agar setelah menyimpan item ke-12
 * daftar tidak kembali tertutup. Item yang punya error validasi selalu ikut terlihat (min).
 */
Alpine.data('loadMore', ({ key, total, step = 5, min = 0 }) => ({
    shown: step,
    total,
    step,

    init() {
        let saved = 0;

        try {
            saved = Number(sessionStorage.getItem(`load-more:${key}`)) || 0;
        } catch {
            saved = 0;
        }

        this.shown = Math.min(this.total, Math.max(this.step, saved, min));
    },

    isShown(index) {
        return index < this.shown;
    },

    get remaining() {
        return Math.max(0, this.total - this.shown);
    },

    more() {
        this.shown = Math.min(this.total, this.shown + this.step);
        this.remember();
    },

    less() {
        this.shown = this.step;
        this.remember();
        this.$root.scrollIntoView({ behavior: 'smooth', block: 'start' });
    },

    remember() {
        try {
            sessionStorage.setItem(`load-more:${key}`, String(this.shown));
        } catch {
            // Penyimpanan tab tidak tersedia; jumlah kembali ke awal setelah halaman dimuat ulang.
        }
    },
}));

/**
 * Form video di dashboard: saat tautan YouTube ditempel, judul dan durasi yang masih kosong
 * diisi dari YouTube. Isian yang sudah diketik admin tidak ditimpa. Bila gagal, server tetap
 * mencoba lagi saat disimpan.
 */
Alpine.data('youtubeAutofill', (endpoint) => ({
    status: '',
    busy: false,

    async lookup(event) {
        if (event.target.name !== 'youtube_url') {
            return;
        }

        const form = this.$root;
        const url = event.target.value.trim();
        const title = form.elements.namedItem('title');
        const duration = form.elements.namedItem('duration');

        if (!url || (title?.value.trim() && duration?.value.trim())) {
            return;
        }

        this.busy = true;
        this.status = 'Mengambil judul dan durasi dari YouTube…';

        try {
            const response = await fetch(`${endpoint}?url=${encodeURIComponent(url)}`, {
                headers: { Accept: 'application/json' },
            });
            const data = await response.json();

            if (!response.ok) {
                this.status = data.message ?? 'Tautan YouTube tidak dikenali.';
                return;
            }

            if (title && !title.value.trim() && data.title) {
                title.value = data.title;
            }

            if (duration && !duration.value.trim() && data.duration) {
                duration.value = data.duration;
            }

            this.status = data.title ? 'Judul dan durasi terisi dari YouTube.' : 'Judul tidak bisa diambil; isi secara manual.';
        } catch {
            this.status = 'YouTube tidak bisa dihubungi; judul akan dicoba lagi saat disimpan.';
        } finally {
            this.busy = false;
        }
    },
}));

/**
 * Tombol "Muat lebih banyak" di halaman publik (video dan artikel Galeri).
 * Mengambil kartu berikutnya dari server lalu menambahkannya ke grid tanpa memuat ulang halaman.
 * Alamat di bilah browser ikut diperbarui, jadi saat halaman dimuat ulang jumlah kartunya tetap.
 * Bila permintaan gagal, tautan dibuka biasa (halaman dimuat ulang dengan kartu lebih banyak).
 */
Alpine.data('publicLoadMore', () => ({
    url: '',
    busy: false,
    status: '',

    init() {
        this.url = this.$root.dataset.url;
    },

    grid() {
        return this.$root.closest('section')?.querySelector('[data-load-more-grid]');
    },

    async load(event) {
        const grid = this.grid();

        if (!grid || !this.url) {
            return; // biarkan tautan terbuka biasa
        }

        event?.preventDefault();

        if (this.busy) {
            return;
        }

        this.busy = true;
        const before = grid.children.length;
        const request = new URL(this.url, window.location.href);
        request.hash = '';
        request.searchParams.set('partial', this.$root.dataset.param);
        request.searchParams.set('dari', String(before));

        try {
            const response = await fetch(request, { headers: { Accept: 'application/json' } });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            const data = await response.json();
            grid.insertAdjacentHTML('beforeend', data.html);

            // Pindahkan fokus ke kartu pertama yang baru, agar pengguna keyboard/pembaca layar tidak tersesat.
            grid.children[before]?.querySelector('a[href]:not([tabindex="-1"])')?.focus({ preventScroll: true });

            this.status = `${data.count} ${this.$root.dataset.param} ditambahkan.`;
            this.url = data.next ?? '';
            history.replaceState(history.state, '', data.url);
        } catch {
            window.location.href = this.url;
        } finally {
            this.busy = false;
        }
    },
}));

Alpine.start();

const navToggle = document.querySelector('.nav-toggle');
const primaryNav = document.querySelector('.primary-nav');

if (navToggle && primaryNav) {
    navToggle.addEventListener('click', () => {
        const isExpanded = navToggle.getAttribute('aria-expanded') === 'true';

        navToggle.setAttribute('aria-expanded', String(!isExpanded));
        primaryNav.classList.toggle('is-open', !isExpanded);
    });

    primaryNav.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            navToggle.setAttribute('aria-expanded', 'false');
            primaryNav.classList.remove('is-open');
        });
    });
}
