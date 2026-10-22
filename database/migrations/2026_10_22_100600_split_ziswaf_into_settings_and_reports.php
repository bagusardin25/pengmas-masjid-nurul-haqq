<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ziswaf_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('singleton')->default(1)->unique();
            $table->string('hero_title');
            $table->text('hero_description')->nullable();
            $table->string('zakat_note')->default('Mal & fitrah');
            $table->string('infaq_note')->default('Kas & operasional');
            $table->string('sedekah_note')->default('Kepedulian sosial');
            $table->string('wakaf_note')->default('Aset produktif umat');
            $table->string('bank_name')->nullable();
            $table->string('account_number', 50)->nullable();
            $table->string('account_holder')->nullable();
            $table->foreignId('qris_image_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('qris_note')->nullable();
            $table->string('confirmation_phone', 30)->nullable();
            $table->timestamps();
        });

        Schema::create('ziswaf_reports', function (Blueprint $table) {
            $table->id();
            // Hari pertama bulan laporan, misalnya 2026-05-01 untuk Mei 2026.
            $table->date('period')->unique();
            $table->unsignedBigInteger('zakat')->default(0);
            $table->unsignedBigInteger('infaq')->default(0);
            $table->unsignedBigInteger('sedekah')->default(0);
            $table->unsignedBigInteger('wakaf')->default(0);
            // Total penyaluran bulan itu. Saldo dihitung, tidak disimpan.
            $table->unsignedBigInteger('disbursed')->default(0);
            $table->foreignId('report_file_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('status', 20)->default('draft')->index();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        $this->copyExistingRows();

        Schema::dropIfExists('ziswaf');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $settings = DB::table('ziswaf_settings')->orderByDesc('id')->first();
        $report = DB::table('ziswaf_reports')->orderByDesc('period')->first();

        Schema::create('ziswaf', function (Blueprint $table) {
            $table->id();
            $table->string('hero_title');
            $table->text('hero_description')->nullable();
            $table->string('period')->nullable();
            $table->unsignedBigInteger('zakat')->default(0);
            $table->unsignedBigInteger('infaq')->default(0);
            $table->unsignedBigInteger('sedekah')->default(0);
            $table->unsignedBigInteger('wakaf')->default(0);
            $table->string('bank_name')->nullable();
            $table->string('account_number', 50)->nullable();
            $table->string('account_holder')->nullable();
            $table->foreignId('qris_image_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('qris_note')->nullable();
            $table->string('confirmation_phone', 30)->nullable();
            $table->timestamps();
        });

        if ($settings !== null) {
            DB::table('ziswaf')->insert([
                'hero_title' => $settings->hero_title,
                'hero_description' => $settings->hero_description,
                'period' => $report ? substr((string) $report->period, 0, 7) : null,
                'zakat' => $report->zakat ?? 0,
                'infaq' => $report->infaq ?? 0,
                'sedekah' => $report->sedekah ?? 0,
                'wakaf' => $report->wakaf ?? 0,
                'bank_name' => $settings->bank_name,
                'account_number' => $settings->account_number,
                'account_holder' => $settings->account_holder,
                'qris_image_id' => $settings->qris_image_id,
                'qris_note' => $settings->qris_note,
                'confirmation_phone' => $settings->confirmation_phone,
                'created_at' => $settings->created_at,
                'updated_at' => $settings->updated_at,
            ]);
        }

        Schema::dropIfExists('ziswaf_reports');
        Schema::dropIfExists('ziswaf_settings');
    }

    private function copyExistingRows(): void
    {
        if (! Schema::hasTable('ziswaf')) {
            return;
        }

        $rows = DB::table('ziswaf')->orderBy('id')->get();

        if ($rows->isEmpty()) {
            return;
        }

        $periods = [];

        foreach ($rows as $row) {
            $period = $this->periodDate($row->period, $row->created_at);

            if (isset($periods[$period])) {
                throw new RuntimeException("Dua baris ziswaf memetakan ke periode {$period}. Gabungkan sebelum migrasi ini.");
            }

            $periods[$period] = $row;
        }

        $latest = $rows->last();

        DB::table('ziswaf_settings')->insert([
            'singleton' => 1,
            'hero_title' => $latest->hero_title,
            'hero_description' => $latest->hero_description,
            'bank_name' => $latest->bank_name,
            'account_number' => $latest->account_number,
            'account_holder' => $latest->account_holder,
            'qris_image_id' => $latest->qris_image_id,
            'qris_note' => $latest->qris_note,
            'confirmation_phone' => $latest->confirmation_phone,
            'created_at' => $latest->created_at,
            'updated_at' => $latest->updated_at,
        ]);

        foreach ($periods as $period => $row) {
            DB::table('ziswaf_reports')->insert([
                'period' => $period,
                'zakat' => $row->zakat,
                'infaq' => $row->infaq,
                'sedekah' => $row->sedekah,
                'wakaf' => $row->wakaf,
                'disbursed' => 0,
                'status' => 'published',
                'published_at' => $row->updated_at ?? $row->created_at,
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at,
            ]);
        }
    }

    private function periodDate(?string $label, ?string $fallback): string
    {
        $label = trim((string) $label);

        if (preg_match('/^(\d{4})-(\d{2})(?:-\d{2})?$/', $label, $matches) === 1) {
            return sprintf('%04d-%02d-01', (int) $matches[1], (int) $matches[2]);
        }

        $months = [
            'januari' => 1,
            'februari' => 2,
            'maret' => 3,
            'april' => 4,
            'mei' => 5,
            'juni' => 6,
            'juli' => 7,
            'agustus' => 8,
            'september' => 9,
            'oktober' => 10,
            'november' => 11,
            'desember' => 12,
        ];

        $normalized = mb_strtolower($label);

        if (preg_match('/(januari|februari|maret|april|mei|juni|juli|agustus|september|oktober|november|desember)\s+(\d{4})/u', $normalized, $matches) === 1) {
            return sprintf('%04d-%02d-01', (int) $matches[2], $months[$matches[1]]);
        }

        $timestamp = $fallback ? strtotime($fallback) : false;

        return date('Y-m-01', $timestamp === false ? time() : $timestamp);
    }
};
