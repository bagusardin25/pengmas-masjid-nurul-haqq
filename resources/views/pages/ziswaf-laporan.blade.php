@extends('layouts.app')

@section('title', 'Laporan Keuangan')
@section('description', 'Laporan keuangan Masjid Jami Nurul Haqq: rutin mingguan, kegiatan, dan pembangunan masjid.')

@section('content')
    <x-page-hero eyebrow="Laporan keuangan" title="Laporan keuangan masjid"
        :description="$ziswaf?->report_description ?? 'Laporan pemasukan dan pengeluaran Masjid Nurul Haqq untuk jamaah.'" />

    <section class="section">
        <div class="site-container">
            <nav data-reveal class="filter-list" aria-label="Kategori laporan">
                <a href="{{ route('ziswaf.laporan') }}" @class(['filter-chip', 'filter-chip-active' => $activeCategory === null])
                    @if ($activeCategory === null) aria-current="true" @endif>Semua</a>
                @foreach ($categories as $category)
                    <a href="{{ route('ziswaf.laporan', ['kategori' => $category->value]) }}"
                        @class(['filter-chip', 'filter-chip-active' => $activeCategory === $category])
                        @if ($activeCategory === $category) aria-current="true" @endif>{{ $category->label() }}</a>
                @endforeach
            </nav>

            @if ($activeCategory)
                <p class="section-description report-filter-note">{{ $activeCategory->description() }}</p>
            @endif

            @if ($reports->isEmpty())
                <p class="report-empty">
                    {{ $activeCategory ? 'Belum ada laporan kategori '.$activeCategory->shortLabel().' yang diterbitkan.' : 'Belum ada laporan yang diterbitkan.' }}
                </p>
            @else
                <ul class="report-list report-list-page" data-reveal-group>
                    @foreach ($reports as $report)
                        <x-report-item data-reveal :report="$report" :show-category="$activeCategory === null" />
                    @endforeach
                </ul>

                <div class="report-pagination">
                    {{ $reports->links(null, ['label' => 'laporan']) }}
                </div>
            @endif

            <p class="report-back"><a href="{{ route('ziswaf') }}#cara-menyalurkan"><i class="fa-solid fa-arrow-left text-[11px]" aria-hidden="true"></i> Kembali ke cara menyalurkan dana</a></p>
        </div>
    </section>
@endsection
