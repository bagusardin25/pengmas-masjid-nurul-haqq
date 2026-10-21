@extends('layouts.app')

@section('title', 'ZISWAF')
@section('description', 'Informasi penyaluran zakat, infak, sedekah, dan wakaf melalui Yayasan Nurul Haqq.')

@section('content')
    <x-page-hero eyebrow="ZISWAF" title="Salurkan kebaikan, kuatkan kebersamaan" description="Salurkan zakat, infak, sedekah, dan wakaf melalui Yayasan Nurul Haqq. Dana dikelola amanah dan dilaporkan setiap bulan kepada jamaah." action-url="https://wa.me/6281239567390" action-label="Tanya layanan ZISWAF" />

    <section class="section">
        <div class="container">
            <p class="step-title"><span>1</span>Pilih jenis dana</p>
            <x-section-heading title="Setiap kebaikan punya peran" />
            <div class="fund-grid">
                <article class="fund-card fund-card-active"><span>♧</span><h3>Zakat</h3><p>Mal &amp; fitrah</p></article>
                <article class="fund-card"><span>▱</span><h3>Infaq</h3><p>Kas &amp; operasional</p></article>
                <article class="fund-card"><span>♡</span><h3>Sedekah</h3><p>Kepedulian sosial</p></article>
                <article class="fund-card"><span>▤</span><h3>Wakaf</h3><p>Aset produktif umat</p></article>
            </div>
        </div>
    </section>

    <section class="section section-muted">
        <div class="container">
            <x-section-heading eyebrow="Cara menyalurkan dana" title="Mudah disalurkan, jelas dikonfirmasi" />
            <div class="transfer-grid">
                <div>
                    <p class="step-title"><span>2</span>Transfer atau scan QRIS</p>
                    <article class="content-panel transfer-card">
                        <div class="qris-placeholder"><span>▧</span><strong>Area QRIS</strong><small>QRIS demo · tidak untuk pembayaran</small></div>
                        <div>
                            <p class="eyebrow">Transfer bank</p>
                            <h3>Bank Demo Syariah</h3>
                            <strong class="account-number">DEMO-0000-0000</strong>
                            <p>a.n. Yayasan Nurul Haqq</p>
                            <small>Rekening dan QRIS merupakan contoh desain, bukan tujuan pembayaran.</small>
                        </div>
                    </article>
                </div>
                <div>
                    <p class="step-title"><span>3</span>Konfirmasi via WhatsApp</p>
                    <article class="content-panel content-panel-dark confirmation-card">
                        <h3>Sampaikan konfirmasi donasi Anda</h3>
                        <p>Kirimkan konfirmasi kepada sekretariat agar donasi dapat dicatat sesuai jenis dana yang dipilih.</p>
                        <strong>0812-3956-7390</strong>
                        <a class="button button-gold button-full" href="https://wa.me/6281239567390">Konfirmasi via WhatsApp</a>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container report-grid">
            <div>
                <x-section-heading eyebrow="Amanah & transparan" title="Laporan dana untuk jamaah" description="Dana dikelola Yayasan Nurul Haqq secara amanah dan dilaporkan setiap bulan kepada jamaah, lengkap dengan rincian penyalurannya." />
                <div class="report-note"><strong>Periode Mei 2026</strong><p>Simulasi laporan Mei 2026. Terkumpul Rp23.122.000, tersalurkan Rp18.000.000, saldo Rp5.122.000.</p></div>
                <a class="button button-outline" href="https://wa.me/6281239567390">▤ Tanya laporan dana</a>
            </div>
            <article class="report-card">
                <p class="eyebrow">Laporan dana</p>
                <h3>Periode Mei 2026</h3>
                <p>Total terkumpul</p>
                <strong class="report-total">Rp 23.122.000</strong>
                <div class="report-summary"><div><span>Tersalurkan</span><strong>Rp 18.000.000</strong></div><div><span>Saldo</span><strong>Rp 5.122.000</strong></div></div>
                <p class="eyebrow report-breakdown-title">Rincian per jenis</p>
                @foreach ([['Zakat', '4.875.000', '20%'], ['Infaq', '6.342.000', '28%'], ['Sedekah', '3.215.000', '14%'], ['Wakaf', '8.690.000', '38%']] as [$name, $amount, $percent])
                    <div class="report-line"><div><strong>{{ $name }}</strong><span>Rp {{ $amount }}</span></div><span class="progress-track"><span style="width: {{ $percent }}"></span></span></div>
                @endforeach
            </article>
        </div>
    </section>

    <x-donation-cta />
@endsection
