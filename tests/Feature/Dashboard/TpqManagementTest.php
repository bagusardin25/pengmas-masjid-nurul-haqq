<?php

use App\Enums\ProfileIcon;
use App\Models\TpqFaq;
use App\Models\TpqPage;
use App\Models\TpqPillar;
use App\Models\User;
use Database\Seeders\TpqPageSeeder;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('guests cannot open the tpq management page', function () {
    $this->get(route('dashboard.tpq'))->assertRedirect(route('login'));
    $this->put(route('dashboard.tpq.update'), ['hero_title' => 'x'])->assertRedirect(route('login'));
    $this->post(route('dashboard.tpq.faqs.store'), ['question' => 'x'])->assertRedirect(route('login'));
});

test('the tpq management page lists current content', function () {
    TpqPage::factory()->create(['hero_title' => 'Judul TPQ tersimpan']);
    TpqPillar::factory()->create(['title' => 'Program tersimpan']);
    TpqFaq::factory()->create(['question' => 'Pertanyaan tersimpan']);

    $this->actingAs($this->user)
        ->get(route('dashboard.tpq'))
        ->assertOk()
        ->assertSee('href="'.route('dashboard.tpq').'"', false)
        ->assertSee('Judul TPQ tersimpan')
        ->assertSee('Program tersimpan')
        ->assertSee('Pertanyaan tersimpan');
});

test('the tpq management page works before any content exists', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.tpq'))
        ->assertOk()
        ->assertSee('Belum ada program unggulan.')
        ->assertSee('Belum ada pertanyaan.');
});

test('tpq information can be saved and blank details are stored as empty', function () {
    $this->actingAs($this->user)
        ->put(route('dashboard.tpq.update'), [
            'hero_title' => 'Tumbuh bersama Al-Qur\'an',
            'info_schedule' => 'Senin–Jumat · 15.30–17.00 WIB',
            'info_age_range' => '',
            'info_fee' => '',
            'registration_phone' => '0812-3956-7390',
            'registration_form_url' => '',
        ])
        ->assertRedirect(route('dashboard.tpq'))
        ->assertSessionHas('status');

    $tpq = TpqPage::current();

    expect($tpq->info_schedule)->toBe('Senin–Jumat · 15.30–17.00 WIB')
        ->and($tpq->info_age_range)->toBeNull()
        ->and($tpq->info_fee)->toBeNull()
        ->and($tpq->registration_form_url)->toBeNull();
});

test('tpq information is validated with indonesian messages', function () {
    $this->actingAs($this->user)
        ->from(route('dashboard.tpq'))
        ->put(route('dashboard.tpq.update'), [
            'hero_title' => '',
            'registration_phone' => 'telepon saya',
            'registration_form_url' => 'formulir-tpq',
        ])
        ->assertRedirect(route('dashboard.tpq'))
        ->assertSessionHasErrors([
            'hero_title' => 'Judul halaman wajib diisi.',
            'registration_phone' => 'Nomor pendaftaran hanya boleh berisi angka, spasi, +, -, dan tanda kurung.',
            'registration_form_url' => 'Tautan formulir harus berupa tautan lengkap, diawali http:// atau https://.',
        ], null, 'tpq');

    expect(TpqPage::current())->toBeNull();
});

test('program pillars can be created, updated, and deleted', function () {
    $this->actingAs($this->user)
        ->post(route('dashboard.tpq.pillars.store'), [
            'title' => 'Munaqosah setiap enam bulan',
            'description' => 'Ujian internal dan tingkat kecamatan.',
            'icon' => ProfileIcon::Check->value,
        ])
        ->assertRedirect(route('dashboard.tpq').'#program');

    $pillar = TpqPillar::query()->firstOrFail();

    expect($pillar->icon)->toBe(ProfileIcon::Check)
        ->and($pillar->sort_order)->toBe(1);

    $this->actingAs($this->user)
        ->put(route('dashboard.tpq.pillars.update', $pillar), [
            'title' => '',
            'icon' => 'bintang',
        ])
        ->assertSessionHasErrors(['title', 'icon'], null, 'pillar-'.$pillar->id);

    $this->actingAs($this->user)
        ->put(route('dashboard.tpq.pillars.update', $pillar), [
            'title' => 'Munaqosah',
            'description' => '',
            'icon' => '',
            'sort_order' => 4,
        ]);

    $pillar->refresh();

    expect($pillar->title)->toBe('Munaqosah')
        ->and($pillar->description)->toBeNull()
        ->and($pillar->icon)->toBeNull()
        ->and($pillar->sort_order)->toBe(4);

    $this->actingAs($this->user)->delete(route('dashboard.tpq.pillars.destroy', $pillar));

    expect(TpqPillar::query()->count())->toBe(0);
});

test('faqs can be created, updated, and deleted', function () {
    $this->actingAs($this->user)
        ->post(route('dashboard.tpq.faqs.store'), [
            'question' => 'Apa syarat pendaftaran?',
            'answer' => 'Formulir, fotokopi KK, dan akta kelahiran.',
        ])
        ->assertRedirect(route('dashboard.tpq').'#pertanyaan');

    $faq = TpqFaq::query()->firstOrFail();

    $this->actingAs($this->user)
        ->put(route('dashboard.tpq.faqs.update', $faq), ['question' => 'Apa syarat pendaftaran?', 'answer' => ''])
        ->assertSessionHasErrors(['answer' => 'Jawaban wajib diisi.'], null, 'faq-'.$faq->id);

    $this->actingAs($this->user)
        ->put(route('dashboard.tpq.faqs.update', $faq), [
            'question' => 'Apa saja syarat pendaftaran?',
            'answer' => 'Formulir dan fotokopi KK serta akta kelahiran, masing-masing 2 lembar.',
        ]);

    expect($faq->refresh()->question)->toBe('Apa saja syarat pendaftaran?');

    $this->actingAs($this->user)->delete(route('dashboard.tpq.faqs.destroy', $faq));

    expect(TpqFaq::query()->count())->toBe(0);
});

test('the seeder loads real tpq data without invented age or fee', function () {
    Storage::fake('public');

    $this->seed(TpqPageSeeder::class);
    $this->seed(TpqPageSeeder::class);

    $tpq = TpqPage::current();

    expect($tpq->info_schedule)->toBe('Senin–Jumat · 15.30–17.00 WIB')
        ->and($tpq->info_age_range)->toBeNull()
        ->and($tpq->info_fee)->toBeNull()
        ->and($tpq->info_description)->toContain('115 santri aktif')
        ->and($tpq->whatsappUrl())->toBe('https://wa.me/6281239567390')
        ->and(TpqPillar::query()->count())->toBe(4)
        ->and(TpqPillar::query()->whereNull('icon')->count())->toBe(0)
        ->and(TpqFaq::query()->count())->toBe(4);
});
