@extends('layouts.app')

@section('title', 'ZISWAF')
@section('description', 'Penyaluran sedekah, infak, zakat, dan wakaf serta laporan keuangan Masjid Jami Nurul Haqq.')

@php
    $step = 0;
    $hasBankAccount = (bool) $ziswaf?->hasBankAccount();
    $hasAnyReport = $latestReports->contains(fn ($reports) => $reports->isNotEmpty());
@endphp

@section('content')
    <x-page-hero eyebrow="ZISWAF" :title="$ziswaf?->hero_title ?? 'ZISWAF'" :description="$ziswaf?->hero_description"
        :action-url="$hasBankAccount ? '#cara-menyalurkan' : null" action-label="Cara menyalurkan" />

    @if ($funds->isNotEmpty() || $programs->isNotEmpty())
        <section class="section">
            <div class="site-container">
                @if ($funds->isNotEmpty())
                    <div data-reveal>
                        <p class="step-title"><span>{{ ++$step }}</span>Pilih jenis dana</p>
                        <x-section-heading title="Setiap kebaikan punya peran" />
                    </div>
                    <div class="fund-grid" data-reveal-group>
                        @foreach ($funds as $fund)
                            <article data-reveal class="fund-card">
                                <span aria-hidden="true">{{ $fund->icon?->glyph() ?? '♡' }}</span>
                                <h3>{{ $fund->name }}</h3>
                                @if ($fund->description)
                                    <p>{{ $fund->description }}</p>
                                @endif
                            </article>
                        @endforeach
                    </div>
                @endif

                @if ($programs->isNotEmpty())
                    <div class="program-block">
                        <div data-reveal>
                            <x-section-heading eyebrow="Program sedekah" title="Pilih program penyaluran"
                                description="Sebutkan program yang dipilih saat konfirmasi agar sedekah disalurkan sesuai niat Anda." />
                        </div>
                        <div class="card-grid card-grid-three" data-reveal-group>
                            @foreach ($programs as $program)
                                <article data-reveal class="info-card">
                                    <span class="card-icon" aria-hidden="true"><i class="fa-solid fa-hand-holding-heart" aria-hidden="true"></i></span>
                                    <h3>{{ $program->title }}</h3>
                                    @if ($program->description)
                                        <p>{{ $program->description }}</p>
                                    @endif
                                </article>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </section>
    @endif

    @if ($hasBankAccount || $contacts->isNotEmpty() || $ziswaf?->infaq_box_description)
        <section id="cara-menyalurkan" class="section section-muted">
            <div class="site-container">
                <div data-reveal><x-section-heading eyebrow="Cara menyalurkan dana" title="Mudah disalurkan, jelas dikonfirmasi" /></div>
                <div data-reveal-group @class(['transfer-grid', 'transfer-grid-single' => ! ($hasBankAccount && $contacts->isNotEmpty())])>
                    @if ($hasBankAccount)
                        <div data-reveal>
                            <p class="step-title"><span>{{ ++$step }}</span>{{ $ziswaf->qrisImage ? 'Transfer atau scan QRIS' : 'Transfer ke rekening masjid' }}</p>
                            <article class="content-panel transfer-card">
                                @if ($ziswaf->qrisImage)
                                    <figure class="qris-image">
                                        <a href="{{ $ziswaf->qrisImage->url() }}" target="_blank" rel="noopener">
                                            <img src="{{ $ziswaf->qrisImage->url() }}" alt="Kode QRIS {{ $ziswaf->account_holder ? 'a.n. '.$ziswaf->account_holder : 'Masjid Nurul Haqq' }}" loading="lazy">
                                        </a>
                                        @if ($ziswaf->qris_note)
                                            <figcaption>{{ $ziswaf->qris_note }}</figcaption>
                                        @endif
                                    </figure>
                                @endif
                                <div x-data="{ copied: false }">
                                    <p class="eyebrow">Transfer bank</p>
                                    @if ($ziswaf->bank_name)
                                        <h3>{{ $ziswaf->bank_name }}</h3>
                                    @endif
                                    <strong class="account-number">{{ $ziswaf->account_number }}</strong>
                                    @if ($ziswaf->account_holder)
                                        <p>a.n. {{ $ziswaf->account_holder }}</p>
                                    @endif
                                    @if ($ziswaf->account_note)
                                        <small>{{ $ziswaf->account_note }}</small>
                                    @endif
                                    <button type="button" class="copy-button" :class="{ 'is-copied': copied }" x-cloak
                                        @click="navigator.clipboard.writeText(@js($ziswaf->account_number)).then(() => { copied = true; setTimeout(() => copied = false, 2000) })">
                                        <span class="copy-icon-stack" :class="{ 'is-copied': copied }" aria-hidden="true">
                                            <svg class="icon-copy" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="8" y="8" width="14" height="14" rx="2"></rect>
                                                <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"></path>
                                            </svg>
                                            <svg class="icon-check" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20 6 9 17l-5-5"></path>
                                            </svg>
                                        </span>
                                        <span x-text="copied ? 'Nomor disalin' : 'Salin nomor rekening'">Salin nomor rekening</span>
                                    </button>
                                </div>
                            </article>
                        </div>
                    @endif

                    @if ($contacts->isNotEmpty())
                        <div data-reveal>
                            <p class="step-title"><span>{{ ++$step }}</span>Konfirmasi via WhatsApp</p>
                            <article class="content-panel content-panel-dark confirmation-card">
                                <h3>Sampaikan konfirmasi donasi Anda</h3>
                                <p>Kirim bukti transfer beserta jenis dana atau program yang dipilih agar donasi dicatat sesuai niat Anda.</p>
                                <ul class="confirmation-contacts">
                                    @foreach ($contacts as $contact)
                                        <li>
                                            <span>
                                                <strong>{{ $contact->name }}</strong>
                                                @if ($contact->role)
                                                    <small>{{ $contact->role }}</small>
                                                @endif
                                            </span>
                                            <a class="button button-gold" href="{{ $contact->whatsappUrl() }}" target="_blank" rel="noopener"
                                                aria-label="Konfirmasi lewat WhatsApp ke {{ $contact->name }}, {{ $contact->phone }}">{{ $contact->phone }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </article>
                        </div>
                    @endif
                </div>

                @if ($ziswaf?->infaq_box_description)
                    <div data-reveal class="report-note">
                        <strong>Kotak infak</strong>
                        <p>{{ $ziswaf->infaq_box_description }}</p>
                    </div>
                @endif
            </div>
        </section>
    @endif

    <section id="laporan" class="section">
        <div class="site-container">
            <div data-reveal class="report-heading">
                <x-section-heading eyebrow="Amanah & transparan" title="Laporan keuangan masjid" :description="$ziswaf?->report_description" />
                @if ($hasAnyReport)
                    <a class="button button-outline" href="{{ route('ziswaf.laporan') }}">Semua laporan</a>
                @endif
            </div>
            <div class="report-columns" data-reveal-group>
                @foreach ($categories as $category)
                    @php $reports = $latestReports[$category->value]; @endphp
                    <article data-reveal class="report-group">
                        <h3>{{ $category->label() }}</h3>
                        <p>{{ $category->description() }}</p>
                        @if ($reports->isEmpty())
                            <p class="report-empty">Belum ada laporan yang diterbitkan.</p>
                        @else
                            <ul class="report-list">
                                @foreach ($reports as $report)
                                    <x-report-item :report="$report" />
                                @endforeach
                            </ul>
                            <a class="report-more link-arrow" href="{{ route('ziswaf.laporan', ['kategori' => $category->value]) }}">
                                Lihat semua {{ Str::lower($category->shortLabel()) }} <i class="fa-solid fa-arrow-right text-[11px]" aria-hidden="true"></i>
                            </a>
                        @endif
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <x-donation-cta />
@endsection
