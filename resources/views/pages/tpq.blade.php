@extends('layouts.app')

@section('title', 'Program TPQ')
@section('description', 'Program Taman Pendidikan Al-Qur’an Masjid Jami Nurul Haqq untuk anak usia 6–15 tahun.')

@section('content')
    <x-page-hero eyebrow="Program TPQ" title="Tumbuh bersama Al-Qur'an sejak dini" description="Program TPQ Masjid Jami Nurul Haqq membantu anak-anak usia 6–15 tahun belajar membaca Al-Qur'an, membangun hafalan, dan mengamalkan akhlak mulia sejak dini." action-url="https://wa.me/6281239567390" action-label="Daftar TPQ" />

    <section class="section">
        <div class="container">
            <x-section-heading eyebrow="Program unggulan" title="Belajar mengaji, membangun akhlak" description="Program TPQ terdiri dari empat pilar utama: membaca Al-Qur'an, tahfidz, akhlak, dan kelas reguler/intensif." />
            <div class="card-grid card-grid-two">
                <article class="info-card"><span class="card-icon">▣</span><h3>Belajar Iqro' &amp; Al-Qur'an</h3><p>Pembelajaran membaca Al-Qur'an dimulai dari Iqro' dasar hingga Al-Qur'an dengan metode yang interaktif dan terstruktur.</p></article>
                <article class="info-card"><span class="card-icon">♧</span><h3>Tahfidzul Qur'an sejak dini</h3><p>Santri diajarkan hafalan Al-Qur'an secara bertahap agar menjadi kebiasaan harian dan penguatan spiritual.</p></article>
                <article class="info-card"><span class="card-icon">♡</span><h3>Akhlak &amp; doa harian</h3><p>Pembelajaran akhlak dan doa harian membantu anak memahami nilai-nilai baik dalam keseharian.</p></article>
                <article class="info-card"><span class="card-icon">✧</span><h3>Kelas reguler &amp; intensif</h3><p>Kelas reguler dan intensif disiapkan agar orang tua dapat memilih jadwal belajar yang sesuai dengan kebutuhan anak.</p></article>
            </div>
        </div>
    </section>

    <section class="section section-muted">
        <div class="container registration-grid">
            <div>
                <x-section-heading eyebrow="Informasi program" title="Kenali program sebelum mendaftar" description="Program TPQ Masjid Jami Nurul Haqq khusus anak usia 6–15 tahun. Kelas berlangsung Senin–Kamis pukul 15.30–17.00 WIB dengan biaya Rp50.000 per bulan." />
                <dl class="detail-list">
                    <div><dt>Usia</dt><dd>6–15 tahun</dd></div>
                    <div><dt>Jadwal</dt><dd>Senin–Kamis · 15.30–17.00 WIB</dd></div>
                    <div><dt>Biaya</dt><dd>Rp50.000 / bulan</dd></div>
                </dl>
            </div>
            <aside class="registration-card">
                <p class="eyebrow eyebrow-light">Pendaftaran TPQ</p>
                <h2>Mari mulai perjalanan belajar anak</h2>
                <p>Pendaftaran dibuka dengan syarat identitas wali dan formulir pendaftaran. Biaya pendaftaran kelas dapat dikonfirmasi kepada pengurus.</p>
                <strong>0812-3956-7390</strong>
                <a class="button button-gold button-full" href="https://wa.me/6281239567390">Daftar TPQ</a>
                <a class="button button-outline-light button-full" href="https://wa.me/6281239567390">Tanya via WhatsApp</a>
            </aside>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <x-section-heading eyebrow="Sebelum mendaftar" title="Apa yang perlu ditanyakan?" />
            <div class="card-grid card-grid-three">
                <article class="question-card"><h3>Usia &amp; pilihan kelas</h3><p>Tanyakan rentang usia serta informasi kelas reguler dan intensif yang sesuai untuk anak.</p></article>
                <article class="question-card"><h3>Hari &amp; jam belajar</h3><p>Konfirmasikan hari dan jam pembelajaran kepada sekretariat sebelum memilih kelas.</p></article>
                <article class="question-card"><h3>Biaya &amp; pendaftaran</h3><p>Tanyakan biaya per bulan dan informasi pendaftaran yang sudah terkonfirmasi.</p></article>
            </div>
            <div class="documentation-panel">
                <div class="documentation-head"><span class="eyebrow">Dokumentasi belajar</span><span>Program demo · visual ilustrasi</span></div>
                <div class="documentation-placeholder" role="img" aria-label="Placeholder dokumentasi kegiatan belajar TPQ"></div>
                <p>Visual ilustrasi ini menampilkan contoh kegiatan belajar bersama, membaca Al-Qur'an, dan pembiasaan doa harian untuk program TPQ.</p>
            </div>
        </div>
    </section>

    <x-donation-cta />
@endsection
