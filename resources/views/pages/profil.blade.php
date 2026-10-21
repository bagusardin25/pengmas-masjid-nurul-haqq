@extends('layouts.app')

@section('title', 'Profil Masjid')
@section('description', 'Profil, fasilitas, sejarah, dan kepengurusan Masjid Jami Nurul Haqq.')

@section('content')
    <x-page-hero eyebrow="Profil" title="Rumah ibadah warga Griya Taman Asri" description="Mengenal Masjid Jami Nurul Haqq, di bawah naungan Yayasan Nurul Haqq, yang melayani jamaah lingkungan Griya Taman Asri, Tawangsari." />

    <section class="section">
        <div class="container profile-overview">
            <div>
                <x-section-heading eyebrow="Tentang Nurul Haqq" title="Pusat ibadah, pendidikan, dan pemberdayaan umat" description="Menjadi pusat ibadah, pendidikan, dan pemberdayaan umat menuju masyarakat yang berakhlak mulia dan sejahtera di tengah keharmonisan lingkungan Griya Taman Asri." />
            </div>
            <div class="stat-grid">
                <article class="stat-card"><span class="card-icon">⌗</span><strong>270 m²</strong><span>Luas tanah</span></article>
                <article class="stat-card"><span class="card-icon">⌂</span><strong>150 m²</strong><span>Luas bangunan</span></article>
                <article class="stat-card"><span class="card-icon">♧</span><strong>600</strong><span>Daya tampung jamaah</span></article>
                <article class="stat-card"><span class="card-icon">✓</span><strong>Wakaf</strong><span>Status tanah</span></article>
            </div>
        </div>
    </section>

    <section class="section section-muted">
        <div class="container">
            <x-section-heading eyebrow="Bersama jamaah" title="Lebih dari tempat sholat" />
            <div class="card-grid card-grid-three">
                <article class="info-card"><span class="card-icon">▤</span><h3>Ibadah &amp; pengajian</h3><p>Sholat berjamaah dan pengajian menjadi bagian dari kegiatan masjid untuk jamaah lingkungan Griya Taman Asri.</p></article>
                <article class="info-card"><span class="card-icon">▣</span><h3>Pendidikan Al-Qur'an</h3><p>Program TPQ mendampingi tumbuh kembang spiritual anak-anak dan remaja di lingkungan Tawangsari.</p></article>
                <article class="info-card"><span class="card-icon">♡</span><h3>Pengelolaan dana umat</h3><p>Zakat, infak, sedekah, dan wakaf dikelola Yayasan Nurul Haqq secara amanah dan dilaporkan setiap bulan.</p></article>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <x-section-heading eyebrow="Fasilitas" title="Nyaman untuk beribadah" description="Sarana yang tersedia untuk kenyamanan ibadah jamaah." />
            <div class="facility-grid">
                @foreach (['Ruang sholat', 'Tempat wudhu', 'Perpustakaan', 'Akses internet', 'Area parkir', 'Toilet bersih'] as $facility)
                    <div class="facility-item"><span class="facility-mark">✓</span>{{ $facility }}</div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section section-muted">
        <div class="container">
            <x-section-heading eyebrow="Sejarah & kepengurusan" title="Mengenal perjalanan masjid" />
            <div class="history-grid">
                <article class="content-panel">
                    <h3>Sejarah</h3>
                    <p>Masjid Nurul Haqq dirintis pada 2008 melalui gotong royong warga Griya Taman Asri. Ruang ibadah dikembangkan pada 2012, disusul program TPQ pada 2015 dan layanan sosial jamaah pada 2020. Catatan ini merupakan contoh ringkas dan dapat dilengkapi dengan data resmi masjid.</p>
                </article>
                <article class="content-panel">
                    <h3>Struktur organisasi</h3>
                    @foreach ([['Ketua', 'Ahmad Fauzi'], ['Sekretaris', 'Siti Aminah'], ['Bendahara', 'Muhammad Ridwan'], ['Anggota', 'Nur Hidayat']] as [$role, $name])
                        <div class="organization-row"><span>{{ $role }}<small>Pengurus masjid</small></span><strong>{{ $name }}</strong></div>
                    @endforeach
                </article>
            </div>
            <a class="button button-dark section-action" href="https://wa.me/6281239567390">☎ Hubungi takmir</a>
        </div>
    </section>

    <x-donation-cta />
@endsection
