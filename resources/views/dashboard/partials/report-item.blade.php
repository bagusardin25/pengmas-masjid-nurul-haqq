@php
    $bag = 'report-'.$report->id;
    $label = 'laporan “'.$report->title.'”';
    $file = $report->reportFile;
@endphp

<div @class(['dash-item', 'is-hidden' => $report->status === \App\Enums\PublicationStatus::Draft])>
    <form method="POST" action="{{ route('dashboard.ziswaf.reports.update', $report) }}" enctype="multipart/form-data" class="dash-item-form dash-grid-six">
        @csrf
        @method('PUT')
        <x-dashboard.field :bag="$bag" name="title" label="Judul laporan" :value="$report->title" required class="dash-span-2" />
        <x-dashboard.field :bag="$bag" name="category" label="Kategori" type="select" :options="$categoryOptions" :value="$report->category->value" required />
        <x-dashboard.field :bag="$bag" name="report_date" label="Tanggal laporan" type="date" :value="$report->report_date->toDateString()" required />
        <x-dashboard.field :bag="$bag" name="status" label="Status" type="select" :options="$statusOptions" :value="$report->status->value" required />
        <div class="dash-field dash-file-current">
            <span class="dash-check-label">Berkas saat ini</span>
            @if ($file)
                <a href="{{ $file->url() }}" target="_blank" rel="noopener">{{ $file->original_name ?: 'Lihat PDF' }}</a>
            @else
                <span class="dash-hint">Berkas tidak ditemukan.</span>
            @endif
        </div>
        <x-dashboard.field :bag="$bag" name="description" label="Keterangan" type="textarea" rows="1" :value="$report->description" class="dash-span-3" />
        <x-dashboard.file-field :bag="$bag" label="Ganti PDF" hint="Kosongkan bila berkas tidak diganti. PDF maksimal 2 MB." class="dash-span-3" />
        <x-dashboard.item-actions :delete-form="'hapus-'.$bag" :label="$label" class="dash-actions-row" />
    </form>
    <x-dashboard.delete-form :id="'hapus-'.$bag" :action="route('dashboard.ziswaf.reports.destroy', $report)" :label="$label.' beserta berkas PDF-nya'" />
</div>
