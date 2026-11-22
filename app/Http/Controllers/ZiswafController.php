<?php

namespace App\Http\Controllers;

use App\Enums\ReportCategory;
use App\Models\ZiswafContact;
use App\Models\ZiswafFund;
use App\Models\ZiswafPage;
use App\Models\ZiswafProgram;
use App\Models\ZiswafReport;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ZiswafController extends Controller
{
    /** Jumlah laporan terbaru per kategori yang tampil di halaman ZISWAF. */
    private const LATEST_PER_CATEGORY = 3;

    /** Jumlah laporan per halaman di daftar semua laporan. */
    private const REPORTS_PER_PAGE = 12;

    public function index(): View
    {
        $categories = ReportCategory::cases();

        $latestReports = collect($categories)->mapWithKeys(fn (ReportCategory $category) => [
            $category->value => ZiswafReport::query()
                ->published()
                ->where('category', $category->value)
                ->with('reportFile')
                ->latestFirst()
                ->limit(self::LATEST_PER_CATEGORY)
                ->get(),
        ]);

        return view('pages.ziswaf', [
            'ziswaf' => ZiswafPage::query()->with('qrisImage')->first(),
            'funds' => ZiswafFund::query()->where('is_active', true)->ordered()->get(),
            'programs' => ZiswafProgram::query()->where('is_active', true)->ordered()->get(),
            'contacts' => ZiswafContact::query()->ordered()->get(),
            'categories' => $categories,
            'latestReports' => $latestReports,
        ]);
    }

    public function reports(Request $request): View
    {
        $kategori = $request->query('kategori');
        $category = is_string($kategori) ? ReportCategory::tryFrom($kategori) : null;

        $reports = ZiswafReport::query()
            ->published()
            ->when($category, fn ($query) => $query->where('category', $category->value))
            ->with('reportFile')
            ->latestFirst()
            ->paginate(self::REPORTS_PER_PAGE)
            ->withQueryString();

        return view('pages.ziswaf-laporan', [
            'ziswaf' => ZiswafPage::current(),
            'categories' => ReportCategory::cases(),
            'activeCategory' => $category,
            'reports' => $reports,
        ]);
    }
}
