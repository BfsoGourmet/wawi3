<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Delivery extends Model
{
    use HasFactory;

    protected $table = 'deliveries';

    protected $fillable = ['courier_id', 'supplier_id', 'product_id'];

    public function product(): HasMany{
        return $this->hasMany(Product::class);
    }
}
