<?php

namespace App\Providers;

use App\Support\SiteContact;
use App\Support\SiteTraffic;
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
        $this->app->scoped(SiteTraffic::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Semua pagination memakai desain situs (lihat resources/views/partials/pagination.blade.php).
        Paginator::defaultView('partials.pagination');

        // Kontak masjid & statistik pengunjung tersedia di semua halaman publik.
        View::composer(['pages.*', 'layouts.app', 'components.site-footer'], function ($view): void {
            $view->with('siteContact', $this->app->make(SiteContact::class));
            $view->with('siteTraffic', $this->app->make(SiteTraffic::class));
        });
    }
}
