<?php

namespace App\Models;

use App\Models\Concerns\HasSingleRow;
use Database\Factories\TpqPageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TpqPage extends Model
{
    /** @use HasFactory<TpqPageFactory> */
    use HasFactory, HasSingleRow;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'hero_title',
        'hero_description',
        'summary',
        'info_description',
        'info_age_range',
        'info_schedule',
        'info_fee',
        'registration_title',
        'registration_description',
        'registration_phone',
        'registration_form_url',
    ];

    /**
     * Tautan WhatsApp dari nomor pendaftaran, mis. "0812-3956-7390" → https://wa.me/6281239567390.
     */
    public function whatsappUrl(): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $this->registration_phone);

        if ($digits === '') {
            return null;
        }

        if (str_starts_with($digits, '0')) {
            $digits = '62'.substr($digits, 1);
        }

        return 'https://wa.me/'.$digits;
    }

    /**
     * Tujuan tombol "Daftar TPQ": formulir online bila ada, selain itu WhatsApp pengurus.
     */
    public function registrationUrl(): ?string
    {
        return $this->registration_form_url ?: $this->whatsappUrl();
    }
}
