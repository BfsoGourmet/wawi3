<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'sku',
        'title',
        'short_description',
        'description',
        'is_vegetarian',
        'is_vegan',
        'calories',
        'sugar_in_calories',
        'slug',
        'price',
        'image'
    ];


    public function categories(): BelongsToMany {
        return $this->belongsToMany(Category::class, 'category_product', 'category_id', 'product_id');
    }


    public function discounts(): HasMany {
        return $this->hasMany(Discount::class);
    }

    public function seasonPrices(): HasMany {
        return $this->hasMany(ProductSeason::class);
    }

    public function seasons(): BelongsToMany {
        return $this->belongsToMany(Product::class, 'product_season', 'season_id', 'product_id');
    }

    public function suppliers(): BelongsToMany {
        return $this->belongsToMany(Supplier::class, 'product_supplier', 'supplier_id', 'product_id');
    }

    public function supplierStocks(): HasMany {
        return $this->hasMany(ProductSupplier::class);
    }

    public function product(): BelongsToMany{
        return $this->BelongsToMany(Delivery::class, 'delivery_product', 'product_id', 'delievery_id');
    }

    
}
