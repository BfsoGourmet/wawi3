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
     * Returns the products as 
     * [
     *      SKU => AMOUNT 
     * ]
     */
    private function _parse_incoming_products(array $products) {
        return array_reduce($products, function ($reduced, $requestedProduct) {

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
    }

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
            'address' => 'required',
            'products' => ['required', function ($field, $value, $fail) {

                // Get the products correctly formatted
                $value = $this->_parse_incoming_products($value);
                
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
                        return $fail('Product "' . $sku . '" was not found');
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
            return response(['error' => $validator->errors(), 'state' => FALSE], 400);
        }

        $delivery = new Delivery();
        $delivery->fill($request->all());

        // Parse products
        $parsedProducts = $this->_parse_incoming_products($request->products);
        
        $deliveryProducts = array_map(function ($sku, $amount) {

            // Get the product
            $product = Product::where('sku', $sku)->first();

            // Reduct the stock by an amount
            $product->reduceStock($amount);

            // Create a new DeliveryProduct
            return (new DeliveryProduct())
                    ->fill([
                        'amount' => $amount,
                        'product_id' => $product->id,
                        'price' => $product->getCurrentPrice()
                    ]);
        }, array_keys($parsedProducts), $parsedProducts);

        // Save the delivery
        $delivery->save();
        
        // Save the products
        $delivery->deliveryProducts()->saveMany($deliveryProducts);

        return response(['state' => 'OK', 'delivery_id' => $delivery->id], 200);
    }
}
