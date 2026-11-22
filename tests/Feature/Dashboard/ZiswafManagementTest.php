<?php

use App\Enums\ProfileIcon;
use App\Models\Media;
use App\Models\User;
use App\Models\ZiswafContact;
use App\Models\ZiswafFund;
use App\Models\ZiswafPage;
use App\Models\ZiswafProgram;
use Database\Seeders\ZiswafPageSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('guests cannot open the ziswaf management page', function () {
    $this->get(route('dashboard.ziswaf'))->assertRedirect(route('login'));
    $this->put(route('dashboard.ziswaf.update'), ['hero_title' => 'x'])->assertRedirect(route('login'));
    $this->post(route('dashboard.ziswaf.funds.store'), ['name' => 'x'])->assertRedirect(route('login'));
    $this->post(route('dashboard.ziswaf.reports.store'), ['title' => 'x'])->assertRedirect(route('login'));
});

test('the ziswaf management page lists current content', function () {
    ZiswafPage::factory()->create(['hero_title' => 'Judul ZISWAF tersimpan', 'account_number' => '7211074177']);
    ZiswafFund::factory()->create(['name' => 'Jenis dana tersimpan']);
    ZiswafProgram::factory()->create(['title' => 'Program tersimpan']);
    ZiswafContact::factory()->create(['name' => 'Kontak tersimpan']);

    $this->actingAs($this->user)
        ->get(route('dashboard.ziswaf'))
        ->assertOk()
        ->assertSee('href="'.route('dashboard.ziswaf').'"', false)
        ->assertSee('Judul ZISWAF tersimpan')
        ->assertSee('7211074177')
        ->assertSee('Jenis dana tersimpan')
        ->assertSee('Program tersimpan')
        ->assertSee('Kontak tersimpan');
});

test('the ziswaf management page works before any content exists', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.ziswaf'))
        ->assertOk()
        ->assertSee('Belum ada QRIS')
        ->assertSee('Belum ada jenis dana.')
        ->assertSee('Belum ada program sedekah.')
        ->assertSee('Belum ada kontak konfirmasi.')
        ->assertSee('Belum ada laporan keuangan.');
});

test('ziswaf information can be saved and blank details are stored as empty', function () {
    $this->actingAs($this->user)
        ->put(route('dashboard.ziswaf.update'), [
            'hero_title' => 'Salurkan kebaikan',
            'bank_name' => 'Bank Syariah Indonesia (BSI)',
            'account_number' => '7211074177',
            'account_holder' => 'Budiyono',
            'account_note' => '',
            'qris_note' => '',
            'infaq_box_description' => 'Kotak infak dibuka setelah sholat Jumat.',
        ])
        ->assertRedirect(route('dashboard.ziswaf'))
        ->assertSessionHas('status', 'Informasi ZISWAF berhasil disimpan.');

    $ziswaf = ZiswafPage::current();

    expect($ziswaf->account_number)->toBe('7211074177')
        ->and($ziswaf->account_holder)->toBe('Budiyono')
        ->and($ziswaf->account_note)->toBeNull()
        ->and($ziswaf->qris_image_id)->toBeNull()
        ->and($ziswaf->infaq_box_description)->toBe('Kotak infak dibuka setelah sholat Jumat.');
});

test('ziswaf information is validated with indonesian messages', function () {
    $this->actingAs($this->user)
        ->from(route('dashboard.ziswaf'))
        ->put(route('dashboard.ziswaf.update'), [
            'hero_title' => '',
            'account_number' => 'nomor rekening',
        ])
        ->assertRedirect(route('dashboard.ziswaf'))
        ->assertSessionHasErrors([
            'hero_title' => 'Judul halaman wajib diisi.',
            'account_number' => 'Nomor rekening hanya boleh berisi angka, spasi, titik, dan tanda -.',
        ], null, 'ziswaf');

    expect(ZiswafPage::current())->toBeNull();
});

test('a qris image can be uploaded, replaced, and removed', function () {
    Storage::fake('public');

    $this->actingAs($this->user)->put(route('dashboard.ziswaf.update'), [
        'hero_title' => 'Salurkan kebaikan',
        'qris_image' => UploadedFile::fake()->createWithContent('qris.png', testPng(20, 20)),
    ])->assertSessionHasNoErrors();

    $first = ZiswafPage::current()->qrisImage;

    expect($first->path)->toStartWith('ziswaf/');
    Storage::disk('public')->assertExists($first->path);

    $this->actingAs($this->user)->put(route('dashboard.ziswaf.update'), [
        'hero_title' => 'Salurkan kebaikan',
        'qris_image' => UploadedFile::fake()->createWithContent('qris-baru.png', testPng(24, 24)),
    ]);

    $second = ZiswafPage::current()->qrisImage;

    expect($second->id)->not->toBe($first->id)
        ->and(Media::query()->whereKey($first->id)->exists())->toBeFalse();
    Storage::disk('public')->assertMissing($first->path);

    $this->actingAs($this->user)->put(route('dashboard.ziswaf.update'), [
        'hero_title' => 'Salurkan kebaikan',
        'remove_qris_image' => '1',
    ]);

    expect(ZiswafPage::current()->qris_image_id)->toBeNull()
        ->and(Media::query()->count())->toBe(0);
    Storage::disk('public')->assertMissing($second->path);
});

test('funds can be created, hidden, updated, and deleted', function () {
    $this->actingAs($this->user)
        ->post(route('dashboard.ziswaf.funds.store'), [
            'name' => 'Wakaf',
            'description' => 'Aset produktif umat',
            'icon' => ProfileIcon::Building->value,
            'is_active' => '1',
        ])
        ->assertRedirect(route('dashboard.ziswaf').'#jenis-dana');

    $fund = ZiswafFund::query()->firstOrFail();

    expect($fund->icon)->toBe(ProfileIcon::Building)
        ->and($fund->is_active)->toBeTrue()
        ->and($fund->sort_order)->toBe(1);

    $this->actingAs($this->user)
        ->put(route('dashboard.ziswaf.funds.update', $fund), ['name' => '', 'icon' => 'bintang'])
        ->assertSessionHasErrors(['name' => 'Nama jenis dana wajib diisi.', 'icon'], null, 'fund-'.$fund->id);

    $this->actingAs($this->user)
        ->put(route('dashboard.ziswaf.funds.update', $fund), [
            'name' => 'Wakaf produktif',
            'description' => '',
            'icon' => '',
            'is_active' => '0',
            'sort_order' => 3,
        ]);

    $fund->refresh();

    expect($fund->name)->toBe('Wakaf produktif')
        ->and($fund->description)->toBeNull()
        ->and($fund->icon)->toBeNull()
        ->and($fund->is_active)->toBeFalse()
        ->and($fund->sort_order)->toBe(3);

    $this->actingAs($this->user)->delete(route('dashboard.ziswaf.funds.destroy', $fund));

    expect(ZiswafFund::query()->count())->toBe(0);
});

test('sedekah programs can be created, updated, and deleted', function () {
    $this->actingAs($this->user)
        ->post(route('dashboard.ziswaf.programs.store'), [
            'title' => 'Anak Yatim',
            'description' => 'Santunan anak yatim.',
            'is_active' => '1',
        ])
        ->assertRedirect(route('dashboard.ziswaf').'#program');

    $program = ZiswafProgram::query()->firstOrFail();

    $this->actingAs($this->user)
        ->put(route('dashboard.ziswaf.programs.update', $program), ['title' => ''])
        ->assertSessionHasErrors(['title' => 'Nama program wajib diisi.'], null, 'program-'.$program->id);

    $this->actingAs($this->user)
        ->put(route('dashboard.ziswaf.programs.update', $program), [
            'title' => 'Santunan Anak Yatim',
            'description' => 'Santunan dan bantuan bagi anak yatim.',
            'is_active' => '1',
        ]);

    expect($program->refresh()->title)->toBe('Santunan Anak Yatim');

    $this->actingAs($this->user)->delete(route('dashboard.ziswaf.programs.destroy', $program));

    expect(ZiswafProgram::query()->count())->toBe(0);
});

test('confirmation contacts can be created, validated, updated, and deleted', function () {
    $this->actingAs($this->user)
        ->post(route('dashboard.ziswaf.contacts.store'), [
            'name' => 'Gatot Bambang',
            'role' => 'Sekretaris Takmir',
            'phone' => '0812-3028-185',
        ])
        ->assertRedirect(route('dashboard.ziswaf').'#kontak');

    $contact = ZiswafContact::query()->firstOrFail();

    expect($contact->whatsappUrl())->toBe('https://wa.me/628123028185');

    $this->actingAs($this->user)
        ->put(route('dashboard.ziswaf.contacts.update', $contact), ['name' => 'Gatot Bambang', 'phone' => 'nomor wa'])
        ->assertSessionHasErrors([
            'phone' => 'Nomor WhatsApp hanya boleh berisi angka, spasi, +, -, dan tanda kurung.',
        ], null, 'contact-'.$contact->id);

    $this->actingAs($this->user)
        ->put(route('dashboard.ziswaf.contacts.update', $contact), [
            'name' => 'Pak Gatot',
            'role' => '',
            'phone' => '0812 3028 185',
        ]);

    $contact->refresh();

    expect($contact->name)->toBe('Pak Gatot')
        ->and($contact->role)->toBeNull();

    $this->actingAs($this->user)->delete(route('dashboard.ziswaf.contacts.destroy', $contact));

    expect(ZiswafContact::query()->count())->toBe(0);
});

test('the seeder loads the data agreed in the 30 october meeting', function () {
    $this->seed(ZiswafPageSeeder::class);
    $this->seed(ZiswafPageSeeder::class);

    $ziswaf = ZiswafPage::current();

    expect($ziswaf->bank_name)->toBe('Bank Syariah Indonesia (BSI)')
        ->and($ziswaf->account_number)->toBe('7211074177')
        ->and($ziswaf->account_holder)->toBe('Budiyono')
        ->and($ziswaf->qris_image_id)->toBeNull()
        ->and($ziswaf->infaq_box_description)->toContain('setelah sholat Jumat')
        ->and(ZiswafFund::query()->ordered()->pluck('name')->all())->toBe(['Sedekah & Infak', 'Zakat', 'Wakaf'])
        ->and(ZiswafFund::query()->where('is_active', false)->count())->toBe(0)
        ->and(ZiswafProgram::query()->ordered()->pluck('title')->all())->toBe(['Peduli Palestina', 'Anak Yatim', 'Kotak Ambulans'])
        ->and(ZiswafContact::query()->ordered()->get()->map->whatsappUrl()->all())->toBe([
            'https://wa.me/628123028185',
            'https://wa.me/628123058294',
        ]);
});
