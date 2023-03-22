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
    


    /**
     * Calculate the current amount of products in the stock
     *
     * @return int
     */
    public function getAmountInStock() {
        return $this->supplierStocks->reduce(fn ($stock, $supplierStock) => $stock + $supplierStock->stock, 0);
    }


    /**
     * Calculates the current price of the product
     *
     * @return int
     */
    public function getCurrentPrice() {

        // Check for any current active seasonal prices
        $assignedSeasonsForCurrentDate = Season::whereHas('seasonDates', function (Builder $query) {
                                                    $query->where('season_dates.date_from', '<=', now());
                                                    $query->where('season_dates.date_until', '>=', now());
                                                })
                                                ->whereIn('seasons.id', $this->seasons->map(fn ($season) => $season->id))
                                                ->get();

        // Did we find any?
        if ($assignedSeasonsForCurrentDate->count() > 0) {

            // Yes -> return that price
            return $this->seasonPrices()->where('season_id', $assignedSeasonsForCurrentDate->first()->id)->first()->seasonal_price;
        }

        // We got no seasonal price.. maybe any discounts?
        $discount = $this->discounts()->where('discounts.discount_from', '<=', now())
                                        ->where('discounts.discount_until', '>=', now())
                                        ->orderBy('discounts.discount_price', 'ASC')
                                        ->first();

        // Is there an active discount
        if ($discount != NULL) {

            // Yes -> return the discount price
            return $discount->discount_price;
        }

        // Return the default price
        return $this->price;
    }


    /**
     * Categories for this product
     *
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany {
        return $this->belongsToMany(Category::class, 'category_product', 'product_id', 'category_id');
    }


    /**
     * Discounts for this product
     *
     * @return HasMany
     */
    public function discounts(): HasMany {
        return $this->hasMany(Discount::class);
    }


    /**
     * Seasonal prices
     *
     * @return HasMany
     */
    public function seasonPrices(): HasMany {
        return $this->hasMany(ProductSeason::class);
    }


    /**
     * Assigned seasons
     *
     * @return BelongsToMany
     */
    public function seasons(): BelongsToMany {
        return $this->belongsToMany(Season::class, 'product_season', 'product_id', 'season_id')->withPivot(['seasonal_price']);
    }


    /**
     * Suppliers
     *
     * @return BelongsToMany
     */
    public function suppliers(): BelongsToMany {
        return $this->belongsToMany(Supplier::class, 'product_supplier', 'product_id', 'supplier_id');
    }


    /**
     * Supplier stocks
     *
     * @return HasMany
     */
    public function supplierStocks(): HasMany {
        return $this->hasMany(ProductSupplier::class);
    }


    /**
     * Allergies
     *
     * @return BelongsToMany
     */
    public function allergies(): BelongsToMany {
        return $this->belongsToMany(Allergy::class, 'allergy_product', 'product_id', 'allergy_id');
    }

}
