<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Delivery extends Model
{
    use HasFactory;

    protected $table = 'deliveries';

    protected $fillable = ['fname', 'lname', 'address', 'zip', 'country'];

    public function products(): BelongsToMany{
        return $this->BelongsToMany(Product::class, 'delivery_product', 'delivery_id', 'product_id')->withPivot(['price', 'amount']);
    }

    public function deliveryProducts(): HasMany {
        return $this->hasMany(DeliveryProduct::class);
    }

    public function getTotalPrice(): float{
        return $this->deliveryProducts->reduce(fn ($price, $deliveryProduct) => $price + $deliveryProduct->price * $deliveryProduct->amount, 0);
    }
}
