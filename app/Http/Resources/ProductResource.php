<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // Check if we got a resource assigned
        if (empty($this->resource)) {
            // Nope -> return null
            return null;
        }

        // Yes -> convert to array
        return [
            "sku" => $this->sku,
            "in_stock" => $this->in_stock,
            "title" => $this->title,
            "short_description" => $this->short_description,
            "description" => $this->description,
            "is_vegeterian" => $this->is_vegeterian,
            "is_vegan" => $this->is_vegan,
            "calories" => $this->calories,
            "sugar_in_calories" => $this->sugar_in_calories,
            "slug" => $this->slug,
            "price" => $this->getCurrentPrice(),
            "image" => !empty($this->image) ? URL::to('/') . '/storage/' . $this->image : '',
            "categories" => $this->categories,
            "allergies" => $this->allergies,
            "suppliers" => $this->suppliers
        ];
    }
}
