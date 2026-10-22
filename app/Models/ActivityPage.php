<?php

namespace App\Models;

use App\Models\Concerns\HasSingleRow;
use Database\Factories\ActivityPageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityPage extends Model
{
    /** @use HasFactory<ActivityPageFactory> */
    use HasFactory, HasSingleRow;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'hero_title',
        'hero_description',
        'routine_note',
        'kitab_note',
    ];
}
