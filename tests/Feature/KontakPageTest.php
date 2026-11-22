<?php

use App\Models\ContactPhone;
use App\Models\ContactSetting;
use App\Models\ZiswafContact;

test('the contact page shows contact details from the database', function () {
    ContactSetting::factory()->create([
        'email' => 'takmirmnh22@gmail.com',
        'instagram' => 'masjid.nurulhaqq',
        'address' => 'Griya Taman Asri Blok FF 08-09 RT 32 RW 08',
        'maps_embed_url' => 'https://www.google.com/maps/embed?pb=peta-masjid',
        'service_hours' => 'Setiap hari setelah Maghrib',
        'youtube_url' => 'https://www.youtube.com/@nurulhaqq',
    ]);
    ContactPhone::factory()->create(['label' => 'Koordinator Ambulans', 'number' => '0822-3061-5186', 'sort_order' => 1]);
    ContactPhone::factory()->whatsapp()->create(['label' => 'Sekretaris Takmir', 'number' => '0812-3028-185', 'sort_order' => 2]);

    $this->get(route('kontak'))
        ->assertOk()
        ->assertSee('Griya Taman Asri Blok FF 08-09 RT 32 RW 08')
        ->assertSee('href="mailto:takmirmnh22@gmail.com"', false)
        ->assertSee('href="https://instagram.com/masjid.nurulhaqq"', false)
        ->assertSee('@masjid.nurulhaqq')
        ->assertSee('src="https://www.google.com/maps/embed?pb=peta-masjid"', false)
        ->assertSee('Setiap hari setelah Maghrib')
        ->assertSee('href="tel:082230615186"', false)
        ->assertSee('href="https://wa.me/628123028185"', false)
        ->assertSee('https://www.youtube.com/@nurulhaqq', false);
});

test('the contact page hides details that have not been filled in', function () {
    ContactSetting::factory()->create(['email' => null, 'instagram' => null, 'maps_embed_url' => null, 'service_hours' => null]);

    $this->get(route('kontak'))
        ->assertOk()
        ->assertDontSee('mailto:', false)
        ->assertDontSee('instagram.com', false)
        ->assertDontSee('<iframe', false)
        ->assertDontSee('Layanan sekretariat')
        ->assertDontSee('Tanya via WhatsApp');
});

test('the contact page renders before any contact is saved', function () {
    $this->get(route('kontak'))
        ->assertOk()
        ->assertSee('Kontak pengurus sedang diperbarui.');
});

test('the main whatsapp number is used for contact buttons across the site', function () {
    ContactPhone::factory()->create(['label' => 'Telepon', 'number' => '031-111', 'is_whatsapp' => false, 'sort_order' => 1]);
    ContactPhone::factory()->whatsapp()->create(['label' => 'Sekretaris', 'number' => '0812-3028-185', 'sort_order' => 2]);
    ContactPhone::factory()->whatsapp()->create(['label' => 'Wakil', 'number' => '0812-3058-294', 'sort_order' => 3]);

    foreach (['profil', 'jadwal', 'galeri', 'kontak'] as $route) {
        $this->get(route($route))
            ->assertOk()
            ->assertSee('href="https://wa.me/628123028185"', false)
            ->assertDontSee('wa.me/6281239567390', false);
    }
});

test('contact buttons fall back to the contact page without a whatsapp number', function () {
    $this->get(route('profil'))
        ->assertOk()
        ->assertSee('href="'.route('kontak').'">☎ Hubungi takmir', false);
});

test('beranda contact section uses the contact settings even after ziswaf contacts are listed', function () {
    ContactSetting::factory()->create(['email' => 'takmirmnh22@gmail.com', 'instagram' => 'masjid.nurulhaqq']);
    ZiswafContact::factory()->create(['name' => 'Pak Gatot']);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('href="mailto:takmirmnh22@gmail.com"', false)
        ->assertSee('href="https://instagram.com/masjid.nurulhaqq"', false);
});

test('pasted instagram links and map embed codes are normalized', function () {
    expect(ContactSetting::normalizeInstagram('@masjid'))->toBe('masjid')
        ->and(ContactSetting::normalizeInstagram('https://instagram.com/masjid/?hl=id'))->toBe('masjid')
        ->and(ContactSetting::normalizeInstagram('  '))->toBeNull()
        ->and(ContactSetting::extractEmbedUrl('<iframe src="https://www.google.com/maps/embed?pb=1&amp;x=2"></iframe>'))
        ->toBe('https://www.google.com/maps/embed?pb=1&x=2')
        ->and(ContactSetting::isValidEmbedUrl('https://www.google.com/maps/embed?pb=1'))->toBeTrue()
        ->and(ContactSetting::isValidEmbedUrl('https://evil.example/maps/embed'))->toBeFalse();
});
