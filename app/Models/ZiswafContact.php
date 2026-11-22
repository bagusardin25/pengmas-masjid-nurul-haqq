<?php

namespace App\Models;

use App\Models\Concerns\HasSortOrder;
use App\Support\WhatsApp;
use Database\Factories\ZiswafContactFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Pengurus yang menerima konfirmasi donasi lewat WhatsApp.
 */
class ZiswafContact extends Model
{
    /** @use HasFactory<ZiswafContactFactory> */
    use HasFactory, HasSortOrder;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'role',
        'phone',
        'sort_order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function whatsappUrl(): ?string
    {
        return WhatsApp::url($this->phone);
    }
}
