<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'price'
    ];


    public function categories(): BelongsToMany {
        return $this->belongsToMany(Category::class, 'category_product', 'category_id', 'product_id');
    }
}
