<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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
    


    public function getAmountInStock() {
        return $this->supplierStocks->reduce(fn ($stock, $supplierStock) => $stock + $supplierStock->stock, 0);
    }


    public function getCurrentPrice() {        
        $assignedSeasonsForCurrentDate = Season::whereHas('seasonDates', function (Builder $query) {
                                                    $query->where('season_dates.date_from', '<=', now());
                                                    $query->where('season_dates.date_until', '>=', now());
                                                })
                                                ->whereIn('seasons.id', $this->seasons->map(fn ($season) => $season->id))
                                                ->get();

        if ($assignedSeasonsForCurrentDate->count() > 0) {
            return $this->seasonPrices()->where('season_id', $assignedSeasonsForCurrentDate->first()->id)->first()->seasonal_price;
        }

        // Check for discounts
        $discount = $this->discounts()->where('discounts.discount_from', '<=', now())
                                        ->where('discounts.discount_until', '>=', now())
                                        ->orderBy('discounts.discount_price', 'ASC')
                                        ->first();

        if ($discount != NULL) {
            return $discount->discount_price;
        }

        // Return the default price
        return $this->price;
    }


    public function categories(): BelongsToMany {
        return $this->belongsToMany(Category::class, 'category_product', 'product_id', 'category_id');
    }


    public function discounts(): HasMany {
        return $this->hasMany(Discount::class);
    }

    public function seasonPrices(): HasMany {
        return $this->hasMany(ProductSeason::class);
    }

    public function seasons(): BelongsToMany {
        return $this->belongsToMany(Season::class, 'product_season', 'product_id', 'season_id')->withPivot(['seasonal_price']);
    }

    public function suppliers(): BelongsToMany {
        return $this->belongsToMany(Supplier::class, 'product_supplier', 'product_id', 'supplier_id');
    }

    public function supplierStocks(): HasMany {
        return $this->hasMany(ProductSupplier::class);
    }

}
