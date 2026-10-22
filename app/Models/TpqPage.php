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
}
