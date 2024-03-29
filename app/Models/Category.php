<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = ['name'];


    /**
     * Get the products for this season
     *
     * @return BelongsToMany
     */
    public function products(): BelongsToMany {
        return $this->belongsToMany(Product::class, 'category_product', 'product_id', 'category_id');
    }
}
