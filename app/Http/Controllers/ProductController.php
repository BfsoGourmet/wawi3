<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{

    /**
     * Return all Products
     */
    public function index() {
        return ProductResource::collection(Product::orderBy('title')->get());
    }



    /**
     * Get a product my sku or slug
     */
    public function getBySkuOrSlug(string $sku_or_slug) {
        return new ProductResource(Product::where('sku', $sku_or_slug)
                                            ->orWhere('slug', $sku_or_slug)
                                            ->firstOrFail());
    }


    /**
     * Search a product by a term
     */
    public function search(string $term = '') {
        return ProductResource::collection(Product::where('title', 'LIKE', "%$term%")
                                                    ->orWhere('description', 'LIKE', "%$term%")
                                                    ->orWhere('short_description', 'LIKE', "%$term%")
                                                    ->get());
    }
}
