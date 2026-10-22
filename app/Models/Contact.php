<?php

namespace App\Models;

use App\Models\Concerns\HasSingleton;
use Database\Factories\ContactFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    /** @use HasFactory<ContactFactory> */
    use HasFactory, HasSingleton;

    protected $table = 'contact';

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
