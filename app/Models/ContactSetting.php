<?php

namespace App\Models;

use App\Models\Concerns\HasSingleRow;
use Database\Factories\ContactSettingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactSetting extends Model
{
    /** @use HasFactory<ContactSettingFactory> */
    use HasFactory, HasSingleRow;

    /** Awalan alamat sematan peta yang diterima (hanya Google Maps). */
    public const MAPS_EMBED_PREFIX = 'https://www.google.com/maps/embed';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'instagram',
        'address',
        'maps_url',
        'maps_embed_url',
        'service_hours',
        'youtube_url',
    ];

    public function instagramUrl(): ?string
    {
        return filled($this->instagram) ? 'https://instagram.com/'.$this->instagram : null;
    }

    /**
     * Tautan Google Maps untuk tombol "Buka di Google Maps". Bila belum diisi, pakai pencarian alamat.
     */
    public function mapsLink(): ?string
    {
        if (filled($this->maps_url)) {
            return $this->maps_url;
        }

        return filled($this->address)
            ? 'https://www.google.com/maps/search/?api=1&query='.rawurlencode('Masjid Nurul Haqq, '.$this->address)
            : null;
    }

    /**
     * Ubah isian Instagram menjadi username saja: "@masjid", "instagram.com/masjid/" → "masjid".
     */
    public static function normalizeInstagram(?string $value): ?string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        if (preg_match('~instagram\.com/([^/?#\s]+)~i', $value, $matches) === 1) {
            $value = $matches[1];
        }

        return ltrim($value, '@');
    }

    /**
     * Ambil alamat sematan dari kode <iframe> yang disalin dari Google Maps ("Bagikan → Sematkan peta").
     * Bila yang ditempel sudah berupa alamat, alamat itu dipakai apa adanya.
     */
    public static function extractEmbedUrl(?string $value): ?string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        if (preg_match('~<iframe[^>]*\ssrc=["\']([^"\']+)["\']~i', $value, $matches) === 1) {
            $value = $matches[1];
        }

        return html_entity_decode($value, ENT_QUOTES | ENT_HTML5);
    }

    public static function isValidEmbedUrl(?string $value): bool
    {
        return $value !== null
            && str_starts_with($value, self::MAPS_EMBED_PREFIX)
            && filter_var($value, FILTER_VALIDATE_URL) !== false;
    }
}
