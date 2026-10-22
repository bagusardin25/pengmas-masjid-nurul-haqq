<?php

namespace App\Models;

use App\Models\Concerns\OrdersBySortOrder;
use Database\Factories\ContactPhoneFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactPhone extends Model
{
    /** @use HasFactory<ContactPhoneFactory> */
    use HasFactory, OrdersBySortOrder;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'label',
        'number',
        'is_whatsapp',
        'sort_order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_whatsapp' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
