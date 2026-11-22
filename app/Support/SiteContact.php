<?php

namespace App\Support;

use App\Models\ContactPhone;
use App\Models\ContactSetting;
use Illuminate\Support\Collection;

/**
 * Data kontak masjid yang dipakai bersama oleh halaman publik (footer, tombol WhatsApp, halaman Kontak).
 * Dimuat sekali per request dan dikelola dari dashboard Kontak.
 */
class SiteContact
{
    private ?ContactSetting $setting = null;

    private bool $settingLoaded = false;

    /** @var Collection<int, ContactPhone>|null */
    private ?Collection $phones = null;

    public function setting(): ?ContactSetting
    {
        if (! $this->settingLoaded) {
            $this->setting = ContactSetting::current();
            $this->settingLoaded = true;
        }

        return $this->setting;
    }

    /**
     * @return Collection<int, ContactPhone>
     */
    public function phones(): Collection
    {
        return $this->phones ??= ContactPhone::query()->ordered()->get();
    }

    /**
     * Nomor WhatsApp utama: nomor WhatsApp dengan urutan paling atas.
     */
    public function whatsappPhone(): ?ContactPhone
    {
        return $this->phones()->firstWhere('is_whatsapp', true);
    }

    public function whatsappUrl(): ?string
    {
        return $this->whatsappPhone()?->whatsappUrl();
    }

    /**
     * Tautan "hubungi pengurus": WhatsApp utama bila ada, selain itu halaman Kontak.
     */
    public function contactUrl(): string
    {
        return $this->whatsappUrl() ?? route('kontak');
    }
}
