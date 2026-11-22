<?php

namespace App\Providers;

use App\Support\SiteContact;
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
        // Kontak masjid (footer, tombol WhatsApp) tersedia di semua halaman publik sebagai $siteContact.
        View::composer(['pages.*', 'components.site-footer'], function ($view): void {
            $view->with('siteContact', $this->app->make(SiteContact::class));
        });
    }
}
