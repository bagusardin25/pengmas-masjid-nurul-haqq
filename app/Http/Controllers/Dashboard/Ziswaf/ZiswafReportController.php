<?php

namespace App\Http\Controllers\Dashboard\Ziswaf;

use App\Enums\PublicationStatus;
use App\Enums\ReportCategory;
use App\Models\ZiswafReport;
use App\Services\MediaUploader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ZiswafReportController extends ZiswafListController
{
    protected string $section = 'laporan';

    protected string $bag = 'report';

    public function __construct(private readonly MediaUploader $uploader) {}

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::enum(ReportCategory::class)],
            'report_date' => ['required', 'date_format:Y-m-d'],
            'description' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', Rule::enum(PublicationStatus::class)],
            // Saat mengubah laporan, PDF boleh dikosongkan (berkas lama tetap dipakai).
            'file' => ['nullable', ...MediaUploader::documentRules()],
        ];
    }

    protected function createRules(): array
    {
        return [
            'file' => ['required', ...MediaUploader::documentRules()],
        ];
    }

    protected function messages(): array
    {
        return MediaUploader::documentMessages('file');
    }

    protected function attributes(): array
    {
        return [
            'title' => 'judul laporan',
            'category' => 'kategori',
            'report_date' => 'tanggal laporan',
            'description' => 'keterangan',
            'status' => 'status',
            'file' => 'berkas PDF',
        ];
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateForm($request);

        $media = $this->uploader->store($request->file('file'), 'laporan', $data['title']);

        ZiswafReport::create([
            ...collect($data)->except('file')->all(),
            'report_file_id' => $media->id,
        ]);

        return $this->backToSection('Laporan keuangan diunggah.');
    }

    public function update(Request $request, ZiswafReport $report): RedirectResponse
    {
        $data = $this->validateForm($request, $report);
        $oldFile = $report->reportFile;

        $report->fill(collect($data)->except('file')->all());

        if ($request->hasFile('file')) {
            $report->report_file_id = $this->uploader->store($request->file('file'), 'laporan', $data['title'])->id;
        } else {
            $oldFile?->update(['alt' => $data['title']]);
        }

        $report->save();

        // Berkas lama yang sudah diganti ikut dihapus.
        if ($oldFile !== null && $oldFile->id !== $report->report_file_id) {
            $oldFile->delete();
        }

        return $this->backToSection('Laporan keuangan diperbarui.');
    }

    public function destroy(ZiswafReport $report): RedirectResponse
    {
        $file = $report->reportFile;

        $report->delete();
        $file?->delete();

        return $this->backToSection('Laporan keuangan dihapus.');
    }
}
