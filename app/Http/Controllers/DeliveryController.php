<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\DeliveryProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeliveryController extends Controller
{
    
    /**
     * Create delivery
     */
    public function create(Request $request) {

        $validator = Validator::make($request->all(), [
            'fname' => 'required',
            'lname' => 'required',
            'city' => 'required',
            'zip' => 'required|integer',
            'country' => 'required',
            'products' => ['required', function ($field, $value, $fail) {

                $value = array_reduce($value, function ($reduced, $requestedProduct) {

                    if (empty($requestedProduct['sku']) || empty($requestedProduct['amount'])) {
                        return $reduced;
                    }

                    if (!isset($reduced[$requestedProduct['sku']])) {
                        $reduced[$requestedProduct['sku']] = intval($requestedProduct['amount']);
                        return $reduced;
                    }

                    $reduced[$requestedProduct['sku']] += intval($requestedProduct['amount']);

                    return $reduced;
                }, []);
                
                // Get the needed products
                $products = Product::whereIn('sku', array_keys($value))->get();

                // Check if the products exist and if there are enough in stock
                foreach ($value as $sku => $amount) {

                    // Check if the required fields are filled
                    if (empty($sku) || empty($amount)) {
                        return $fail();
                    }

                    // Get the product
                    $product = $products->first(fn ($product) => $product->sku == $sku);

                    // Does it exist?
                    if (empty($product)) {
                        return $fail('Product ' . $sku . ' was not found');
                    }

                    // Are there enough in stock?
                    if ($product->getAmountInStock() < $amount) {
                        return $fail('There are not enough items of ' . $sku . ' in stock');
                    }
                }

                return true;
            }],
            'products.*.amount' => 'required|integer',
            'products.*.sku' => 'required',
        ]);

        if ($validator->fails()) {
            return response($validator->errors(), 400);
        }

        $delivery = new Delivery();
        $delivery->fill($request->all());
        
        $delivery->deliveryProducts = array_map(function ($requestedProduct) {
            $product = Product::where('sku', $requestedProduct['sku'])->first();

            return (new DeliveryProduct())
                    ->fill([
                        ...$requestedProduct,
                        'product_id' => $product->id,
                        'price' => $product->getCurrentPrice()
                    ]);
            } , $request->products);
       
        $delivery->save();

        return response('ok', 200);
    }
}
