<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Season extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];


    /**
     * Get all of the seasonDates for the Season
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function seasonDates(): HasMany
    {
        return $this->hasMany(SeasonDates::class, 'season_id');
    }

}
