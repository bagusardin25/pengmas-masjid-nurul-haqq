<?php

use App\Models\ContactPhone;
use App\Models\ContactSetting;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('guests cannot open the contact management page', function () {
    $this->get(route('dashboard.kontak'))->assertRedirect(route('login'));
    $this->put(route('dashboard.kontak.update'), ['email' => 'x@y.z'])->assertRedirect(route('login'));
    $this->post(route('dashboard.kontak.phones.store'), ['label' => 'x'])->assertRedirect(route('login'));
});

test('the contact management page lists current content and is linked from the sidebar', function () {
    ContactSetting::factory()->create(['email' => 'takmirmnh22@gmail.com']);
    ContactPhone::factory()->create(['label' => 'Sekretaris Takmir', 'number' => '0812-3028-185']);

    $this->actingAs($this->user)
        ->get(route('dashboard.kontak'))
        ->assertOk()
        ->assertSee('href="'.route('dashboard.kontak').'"', false)
        ->assertSee('takmirmnh22@gmail.com')
        ->assertSee('Sekretaris Takmir')
        ->assertSee('0812-3028-185');
});

test('the contact management page works before any content exists', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.kontak'))
        ->assertOk()
        ->assertSee('Belum ada nomor telepon.');
});

test('contact information is saved and pasted values are tidied up', function () {
    $this->actingAs($this->user)
        ->put(route('dashboard.kontak.update'), [
            'address' => 'Griya Taman Asri Blok FF 08-09',
            'email' => 'takmirmnh22@gmail.com',
            'instagram' => 'https://www.instagram.com/masjid.nurulhaqq/',
            'maps_embed_url' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18&amp;z=1" width="600" height="450" style="border:0;" allowfullscreen=""></iframe>',
            'youtube_url' => '',
            'service_hours' => '',
        ])
        ->assertRedirect(route('dashboard.kontak'))
        ->assertSessionHas('status', 'Informasi kontak berhasil disimpan.');

    $contact = ContactSetting::current();

    expect($contact->instagram)->toBe('masjid.nurulhaqq')
        ->and($contact->maps_embed_url)->toBe('https://www.google.com/maps/embed?pb=!1m18&z=1')
        ->and($contact->youtube_url)->toBeNull()
        ->and($contact->service_hours)->toBeNull();
});

test('contact information is validated with indonesian messages', function () {
    $this->actingAs($this->user)
        ->from(route('dashboard.kontak'))
        ->put(route('dashboard.kontak.update'), [
            'email' => 'bukan email',
            'instagram' => '@nama masjid!',
            'maps_embed_url' => 'https://example.com/peta',
            'youtube_url' => 'youtube',
        ])
        ->assertRedirect(route('dashboard.kontak'))
        ->assertSessionHasErrors([
            'email' => 'Email harus berupa alamat email yang valid, mis. nama@gmail.com.',
            'instagram' => 'Username Instagram hanya boleh berisi huruf, angka, titik, dan garis bawah.',
            'youtube_url' => 'Tautan kanal YouTube harus berupa tautan lengkap, diawali http:// atau https://.',
        ], null, 'contact')
        ->assertSessionHasErrors(['maps_embed_url'], null, 'contact');

    expect(ContactSetting::current())->toBeNull();
});

test('phone numbers can be added, updated, and deleted', function () {
    $this->actingAs($this->user)
        ->post(route('dashboard.kontak.phones.store'), [
            'label' => 'Sekretaris Takmir',
            'number' => '0812-3028-185',
            'is_whatsapp' => '1',
        ])
        ->assertRedirect(route('dashboard.kontak').'#telepon');

    $phone = ContactPhone::query()->sole();

    expect($phone->is_whatsapp)->toBeTrue()
        ->and($phone->sort_order)->toBe(1);

    $this->actingAs($this->user)
        ->put(route('dashboard.kontak.phones.update', $phone), [
            'label' => 'Koordinator Ambulans',
            'number' => '0822-3061-5186',
            'is_whatsapp' => '0',
            'sort_order' => '',
        ])
        ->assertRedirect(route('dashboard.kontak').'#telepon');

    expect($phone->fresh())
        ->label->toBe('Koordinator Ambulans')
        ->is_whatsapp->toBeFalse()
        ->sort_order->toBe(1);

    $this->actingAs($this->user)
        ->delete(route('dashboard.kontak.phones.destroy', $phone))
        ->assertRedirect(route('dashboard.kontak').'#telepon');

    expect(ContactPhone::query()->count())->toBe(0);
});

test('phone numbers are validated in their own error bag', function () {
    $this->actingAs($this->user)
        ->post(route('dashboard.kontak.phones.store'), ['label' => '', 'number' => 'nomor'])
        ->assertRedirect(route('dashboard.kontak').'#telepon')
        ->assertSessionHasErrors([
            'label' => 'Keterangan wajib diisi.',
            'number' => 'Nomor hanya boleh berisi angka, spasi, +, -, dan tanda kurung.',
        ], null, 'phone-new');
});
