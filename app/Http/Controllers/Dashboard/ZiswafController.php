<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\ProfileIcon;
use App\Enums\PublicationStatus;
use App\Enums\ReportCategory;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Dashboard\Concerns\IndonesianValidationMessages;
use App\Models\ZiswafContact;
use App\Models\ZiswafFund;
use App\Models\ZiswafPage;
use App\Models\ZiswafProgram;
use App\Models\ZiswafReport;
use App\Services\MediaUploader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ZiswafController extends Controller
{
    use IndonesianValidationMessages;

    public function edit(): View
    {
        return view('dashboard.ziswaf', [
            'ziswaf' => ZiswafPage::query()->with('qrisImage')->first(),
            'funds' => ZiswafFund::query()->ordered()->get(),
            'programs' => ZiswafProgram::query()->ordered()->get(),
            'contacts' => ZiswafContact::query()->ordered()->get(),
            'reports' => ZiswafReport::query()->with('reportFile')->latestFirst()->get(),
            'icons' => ProfileIcon::cases(),
            'categories' => ReportCategory::cases(),
            'statuses' => PublicationStatus::cases(),
        ]);
    }

    public function update(Request $request, MediaUploader $uploader): RedirectResponse
    {
        $data = $request->validateWithBag('ziswaf', [
            'hero_title' => ['required', 'string', 'max:255'],
            'hero_description' => ['nullable', 'string', 'max:1000'],
            'summary' => ['nullable', 'string', 'max:1000'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'account_number' => ['nullable', 'string', 'max:50', 'regex:/^[0-9\-\s.]+$/'],
            'account_holder' => ['nullable', 'string', 'max:255'],
            'account_note' => ['nullable', 'string', 'max:255'],
            'qris_image' => ['nullable', ...MediaUploader::rules()],
            'qris_note' => ['nullable', 'string', 'max:255'],
            'remove_qris_image' => ['boolean'],
            'infaq_box_description' => ['nullable', 'string', 'max:2000'],
            'report_description' => ['nullable', 'string', 'max:1000'],
        ], [
            ...$this->validationMessages(),
            ...MediaUploader::messages('qris_image'),
            'account_number.regex' => 'Nomor rekening hanya boleh berisi angka, spasi, titik, dan tanda -.',
        ], [
            'hero_title' => 'judul halaman',
            'hero_description' => 'deskripsi halaman',
            'summary' => 'ringkasan di Beranda',
            'bank_name' => 'nama bank',
            'account_number' => 'nomor rekening',
            'account_holder' => 'nama pemilik rekening',
            'account_note' => 'keterangan rekening',
            'qris_image' => 'gambar QRIS',
            'qris_note' => 'keterangan QRIS',
            'remove_qris_image' => 'pilihan hapus QRIS',
            'infaq_box_description' => 'keterangan kotak infak',
            'report_description' => 'pengantar laporan',
        ]);

        $ziswaf = ZiswafPage::current() ?? new ZiswafPage;
        $oldQris = $ziswaf->qrisImage;

        $ziswaf->fill(collect($data)->except(['qris_image', 'remove_qris_image'])->all());

        if ($request->hasFile('qris_image')) {
            $ziswaf->qris_image_id = $uploader->store($request->file('qris_image'), 'ziswaf', 'Kode QRIS')->id;
        } elseif ($request->boolean('remove_qris_image')) {
            $ziswaf->qris_image_id = null;
        }

        $ziswaf->save();

        // QRIS lama yang sudah diganti atau dihapus ikut dihapus beserta berkasnya.
        if ($oldQris !== null && $oldQris->id !== $ziswaf->qris_image_id) {
            $oldQris->delete();
        }

        return to_route('dashboard.ziswaf')
            ->with('status', 'Informasi ZISWAF berhasil disimpan.');
    }
}
