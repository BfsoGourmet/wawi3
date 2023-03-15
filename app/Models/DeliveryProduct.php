<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryProduct extends Model
{
    use HasFactory;

    protected $table = 'delivery_product';

    protected $fillable = [
        'product_id',
        'delivery_id',
        'amount',
        'price'
    ];
}
