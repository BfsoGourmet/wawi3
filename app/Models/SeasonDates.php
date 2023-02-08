<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeasonDates extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_from',
        'date_until'
    ];


    /**
     * Get the category that owns the SeasonDates
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Season::class, 'season_id');
    }
}
