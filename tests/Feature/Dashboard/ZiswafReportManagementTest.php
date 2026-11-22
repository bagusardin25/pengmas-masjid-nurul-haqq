<?php

use App\Enums\PublicationStatus;
use App\Enums\ReportCategory;
use App\Models\Media;
use App\Models\User;
use App\Models\ZiswafReport;
use App\Services\MediaUploader;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
    $this->user = User::factory()->create();
});

function reportPdf(string $name = 'laporan-kas.pdf'): UploadedFile
{
    return UploadedFile::fake()->createWithContent($name, testPdf());
}

test('a financial report pdf can be uploaded', function () {
    $this->actingAs($this->user)
        ->post(route('dashboard.ziswaf.reports.store'), [
            'title' => 'Laporan kas pekan ke-3 November 2026',
            'category' => ReportCategory::Weekly->value,
            'report_date' => '2026-11-20',
            'description' => 'Kotak infak Jumat 20 November.',
            'status' => PublicationStatus::Published->value,
            'file' => reportPdf(),
        ])
        ->assertRedirect(route('dashboard.ziswaf').'#laporan')
        ->assertSessionHas('status', 'Laporan keuangan diunggah.');

    $report = ZiswafReport::query()->with('reportFile')->firstOrFail();
    $file = $report->reportFile;

    expect($report->category)->toBe(ReportCategory::Weekly)
        ->and($report->report_date->toDateString())->toBe('2026-11-20')
        ->and($report->status)->toBe(PublicationStatus::Published)
        ->and($report->published_at)->not->toBeNull()
        ->and($file->path)->toStartWith('laporan/')
        ->and($file->original_name)->toBe('laporan-kas.pdf')
        ->and($file->mime_type)->toBe('application/pdf')
        ->and($file->width)->toBeNull();

    Storage::disk('public')->assertExists($file->path);
});

test('uploading a report requires a pdf within the size limit and valid details', function () {
    $this->actingAs($this->user)
        ->post(route('dashboard.ziswaf.reports.store'), [
            'title' => '',
            'category' => 'bulanan',
            'report_date' => '20-11-2026',
            'status' => PublicationStatus::Published->value,
        ])
        ->assertRedirect(route('dashboard.ziswaf').'#laporan')
        ->assertSessionHasErrors([
            'title' => 'Judul laporan wajib diisi.',
            'category' => 'Pilihan kategori tidak valid.',
            'report_date' => 'Tanggal laporan tidak sesuai format tanggal.',
            'file' => 'Pilih berkas PDF yang akan diunggah.',
        ], null, 'report-new');

    $valid = [
        'title' => 'Laporan Ramadan',
        'category' => ReportCategory::Event->value,
        'report_date' => '2026-03-30',
        'status' => PublicationStatus::Draft->value,
    ];

    $this->actingAs($this->user)
        ->post(route('dashboard.ziswaf.reports.store'), [...$valid, 'file' => UploadedFile::fake()->createWithContent('laporan.png', testPng(4, 4))])
        ->assertSessionHasErrors(['file' => 'Berkas harus berupa dokumen PDF.'], null, 'report-new');

    $this->actingAs($this->user)
        ->post(route('dashboard.ziswaf.reports.store'), [...$valid, 'file' => UploadedFile::fake()->create('besar.pdf', MediaUploader::MAX_KILOBYTES + 500, 'application/pdf')])
        ->assertSessionHasErrors(['file' => 'Ukuran PDF maksimal 2 MB.'], null, 'report-new');

    expect(ZiswafReport::query()->count())->toBe(0)
        ->and(Media::query()->count())->toBe(0);
});

test('report details can be updated without uploading again, and the pdf can be replaced', function () {
    $this->actingAs($this->user)->post(route('dashboard.ziswaf.reports.store'), [
        'title' => 'Laporan pembangunan',
        'category' => ReportCategory::Construction->value,
        'report_date' => '2026-06-30',
        'status' => PublicationStatus::Draft->value,
        'file' => reportPdf('pembangunan.pdf'),
    ]);

    $report = ZiswafReport::query()->firstOrFail();
    $oldFile = $report->reportFile;

    $this->actingAs($this->user)
        ->put(route('dashboard.ziswaf.reports.update', $report), [
            'title' => 'Laporan pembangunan masjid tahap II',
            'category' => ReportCategory::Construction->value,
            'report_date' => '2026-06-30',
            'status' => PublicationStatus::Published->value,
        ])
        ->assertRedirect(route('dashboard.ziswaf').'#laporan');

    $report->refresh();

    expect($report->title)->toBe('Laporan pembangunan masjid tahap II')
        ->and($report->status)->toBe(PublicationStatus::Published)
        ->and($report->report_file_id)->toBe($oldFile->id)
        ->and($oldFile->refresh()->alt)->toBe('Laporan pembangunan masjid tahap II');

    $this->actingAs($this->user)->put(route('dashboard.ziswaf.reports.update', $report), [
        'title' => 'Laporan pembangunan masjid tahap II',
        'category' => ReportCategory::Construction->value,
        'report_date' => '2026-06-30',
        'status' => PublicationStatus::Published->value,
        'file' => reportPdf('pembangunan-revisi.pdf'),
    ]);

    $report->refresh();

    expect($report->report_file_id)->not->toBe($oldFile->id)
        ->and($report->reportFile->original_name)->toBe('pembangunan-revisi.pdf')
        ->and(Media::query()->whereKey($oldFile->id)->exists())->toBeFalse();
    Storage::disk('public')->assertMissing($oldFile->path);
});

test('deleting a report also deletes its pdf', function () {
    $this->actingAs($this->user)->post(route('dashboard.ziswaf.reports.store'), [
        'title' => 'Laporan kas',
        'category' => ReportCategory::Weekly->value,
        'report_date' => '2026-11-13',
        'status' => PublicationStatus::Published->value,
        'file' => reportPdf(),
    ]);

    $report = ZiswafReport::query()->firstOrFail();
    $path = $report->reportFile->path;

    $this->actingAs($this->user)
        ->delete(route('dashboard.ziswaf.reports.destroy', $report))
        ->assertRedirect(route('dashboard.ziswaf').'#laporan');

    expect(ZiswafReport::query()->count())->toBe(0)
        ->and(Media::query()->count())->toBe(0);
    Storage::disk('public')->assertMissing($path);
});

test('the management page lists reports with draft ones marked as hidden', function () {
    ZiswafReport::factory()->published()->create(['title' => 'Laporan terbit']);
    ZiswafReport::factory()->create(['title' => 'Laporan draf']);

    $this->actingAs($this->user)
        ->get(route('dashboard.ziswaf'))
        ->assertOk()
        ->assertSee('Laporan terbit')
        ->assertSee('Laporan draf')
        ->assertSee('dash-item is-hidden', false);
});
