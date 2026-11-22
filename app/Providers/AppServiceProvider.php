<?php

namespace App\Providers;

use App\Support\SiteContact;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->scoped(SiteContact::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Semua pagination memakai desain situs (lihat resources/views/partials/pagination.blade.php).
        Paginator::defaultView('partials.pagination');

        // Kontak masjid (footer, tombol WhatsApp) tersedia di semua halaman publik sebagai $siteContact.
        View::composer(['pages.*', 'layouts.app', 'components.site-footer'], function ($view): void {
            $view->with('siteContact', $this->app->make(SiteContact::class));
        });
    }
}
