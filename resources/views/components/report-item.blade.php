{{-- Satu baris laporan keuangan (PDF) dengan tautan buka dan unduh. --}}
@props(['report', 'showCategory' => false])

@php
    $file = $report->reportFile;
    $size = $file?->size;
    $sizeLabel = match (true) {
        $size === null => null,
        $size >= 1024 * 1024 => str_replace('.', ',', (string) round($size / 1024 / 1024, 1)).' MB',
        default => max(1, (int) round($size / 1024)).' KB',
    };
    $meta = array_filter([
        $report->date_label,
        $showCategory ? $report->category->shortLabel() : null,
        $sizeLabel ? 'PDF '.$sizeLabel : 'PDF',
    ]);
@endphp

<li {{ $attributes->class(['report-item']) }}>
    <span class="report-icon" aria-hidden="true">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
            <path d="M14 2v6h6"></path>
            <path d="M8 13h8M8 17h5"></path>
        </svg>
    </span>
    <div class="report-copy">
        @if ($file)
            <a href="{{ $file->url() }}" target="_blank" rel="noopener">{{ $report->title }}</a>
        @else
            <strong>{{ $report->title }}</strong>
        @endif
        <small>{{ implode(' · ', $meta) }}</small>
        @if ($report->description)
            <p>{{ $report->description }}</p>
        @endif
    </div>
    @if ($file)
        <a class="report-download" href="{{ $file->url() }}" download="{{ $file->original_name ?: 'laporan.pdf' }}"
            aria-label="Unduh {{ $report->title }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <path d="M7 10l5 5 5-5"></path>
                <path d="M12 15V3"></path>
            </svg>
            <span>Unduh</span>
        </a>
    @endif
</li>
