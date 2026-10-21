@extends('layouts.app')

@section('title', 'Galeri')
@section('description', 'Album foto, video kajian, dan artikel kegiatan Masjid Jami Nurul Haqq.')

@section('content')
    <x-page-hero eyebrow="Galeri" title="Momen kebersamaan jamaah" description="Album foto, video kajian, dan artikel kegiatan Masjid Nurul Haqq. Seluruh konten merupakan ilustrasi demo." />

    <section class="section section-muted">
        <div class="container">
            <div class="gallery-heading">
                <x-section-heading eyebrow="Galeri kegiatan" title="Dari ibadah hingga belajar mengaji" />
                <span class="muted-text">Konten demo · visual ilustrasi</span>
            </div>
            <div class="filter-list" aria-label="Kategori galeri">
                <span class="filter-chip filter-chip-active">Semua</span>
                <span class="filter-chip">Kajian rutin</span>
                <span class="filter-chip">Sholat berjamaah</span>
                <span class="filter-chip">Kegiatan TPQ</span>
                <span class="filter-chip">Pengajian Ahad malam</span>
            </div>
            <div class="gallery-grid">
                @foreach ([['Kajian Rutin', 'Dokumentasi kajian Islam rutin jamaah bersama ustaz di masjid.', 'Rabu · 19.30 WIB', '7 Oktober 2026'], ['Sholat Berjamaah', 'Momen kekhusyukan ibadah berjamaah di Masjid Nurul Haqq.', 'Sholat fardhu · 5 waktu sehari', '9 Oktober 2026'], ['Belajar TPQ', 'Semangat santri TPQ dalam mempelajari Al-Qur’an bersama ustaz.', 'Program pendidikan Al-Qur’an', '8 Oktober 2026'], ['Pengajian Ahad Malam', 'Suasana pengajian rutin yang penuh kekhidmatan bersama jamaah.', 'Ahad · 19.30 WIB', '11 Oktober 2026']] as [$title, $description, $meta, $date])
                    <article class="gallery-card">
                        <div class="gallery-placeholder" role="img" aria-label="Placeholder foto {{ $title }}"><span>{{ $date }}</span></div>
                        <div class="gallery-copy"><p class="eyebrow">Album foto · 20–24 gambar</p><h3>{{ $title }}</h3><p>{{ $description }}</p><small>{{ $meta }}</small></div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="gallery-heading">
                <x-section-heading eyebrow="Video kajian" title="Tonton kajian pilihan" />
                <a class="channel-card" href="https://youtube.com"><span class="channel-avatar">NH</span><span><strong>Nurul Haqq</strong><small>Kanal demo · 12 playlist</small></span><span class="tag">Kunjungi kanal</span></a>
            </div>
            <div class="card-grid card-grid-three">
                @foreach ([['Adab Menuntut Ilmu', 'Pentingnya menjaga adab dan akhlak dalam setiap langkah menuntut ilmu.', '24:18', '5 Oktober 2026'], ['Belajar Tajwid Dasar', 'Panduan praktis membaca Al-Qur’an dengan tajwid yang benar untuk pemula.', '16:40', '6 Oktober 2026'], ['Merawat Ukhuwah Jamaah', 'Mempererat persaudaraan dan kebersamaan sebagai pilar kehidupan jamaah.', '32:10', '8 Oktober 2026']] as [$title, $description, $duration, $date])
                    <article class="video-card">
                        <a class="video-placeholder" href="https://youtube.com" aria-label="Tonton {{ $title }}"><span>▶</span><small>{{ $duration }}</small></a>
                        <div class="video-copy"><p class="eyebrow">Kajian · demo video</p><h3>{{ $title }}</h3><p>{{ $description }}</p><small>Nurul Haqq · {{ $date }}</small></div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section section-muted">
        <div class="container">
            <x-section-heading eyebrow="Artikel kegiatan" title="Tulisan dari jamaah" />
            <div class="card-grid card-grid-two">
                <article class="article-card"><div class="article-image" role="img" aria-label="Placeholder artikel Adab Menuntut Ilmu"></div><div class="article-copy"><p class="article-meta">Kajian · 5 Oktober 2026 · 4 menit baca</p><h3>Adab Menuntut Ilmu</h3><p>Rasulullah ﷺ bersabda bahwa menuntut ilmu adalah kewajiban setiap Muslim. Menjaga adab dalam perjalanan mencari ilmu sama pentingnya dengan ilmu itu sendiri.</p><strong>● Ust. Ahmad Fauzi</strong></div></article>
                <article class="article-card"><div class="article-image" role="img" aria-label="Placeholder artikel Gotong Royong Memakmurkan Masjid"></div><div class="article-copy"><p class="article-meta">Kegiatan · 10 Oktober 2026 · 5 menit baca</p><h3>Gotong Royong Memakmurkan Masjid</h3><p>Masjid bukan sekadar tempat sholat, melainkan pusat peradaban umat. Ketika jamaah bahu-membahu merawat dan memakmurkan masjid, terbentuk ikatan ukhuwah yang kuat.</p><strong>● Panitia Masjid</strong></div></article>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container gallery-contact">
            <div><h2>Ingin mengetahui kegiatan masjid?</h2><p>Lihat jadwal kegiatan rutin atau hubungi pengurus untuk informasi dokumentasi yang tersedia.</p></div>
            <div class="button-row"><a class="button button-dark" href="{{ route('jadwal') }}">Lihat jadwal</a><a class="button button-outline" href="https://wa.me/6281239567390">Hubungi pengurus</a></div>
        </div>
    </section>
    <x-donation-cta />
@endsection
