<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSeason extends Model
{
    use HasFactory;

    protected $table = 'product_season';

    protected $fillable = [
        'product_id',
        'season_id',
        'seasonal_price'
    ];
}
