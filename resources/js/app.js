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
