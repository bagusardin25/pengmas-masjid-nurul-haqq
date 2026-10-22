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
}
