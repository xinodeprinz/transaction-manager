<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function createProduct(Request $request)
    {
        $data = [
            'productName' => $request->productName,
            'quantity' => $request->quantity,
            'unitPrice' => $request->unitPrice,
            'sellingPrice' => $request->sellingPrice,
            'category' => $request->category,
            'description' => $request->description,
            'image' => $request->image,
        ];
        $validator = Validator::make($data, [
            'productName' => ['required', 'string'],
            'quantity' => ['required', 'string'],
            'unitPrice' => ['required', 'string'],
            'sellingPrice' => ['required', 'string'],
            'category' => ['required', 'string'],
            'description' => ['required', 'string'],
            'image' => ['required', 'image'],
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 400);
        }
        $user = auth()->user();
        $product = $user->products()->where('name', $request->productName)->first();
        if ($product) {
            return response(['message' => 'Product already exist'], 403);
        }
        $user->products()->create([
            'name' => strtoupper($data['productName']),
            'quantity' => $data['quantity'],
            'unit_price' => $data['unitPrice'],
            'selling_price' => $data['sellingPrice'],
            'category' => strtoupper($data['category']),
            'description' => $data['description'],
            'image' => $request->image->store('products', 'public'),
        ]);
        return response(['message' => 'Product Created Successfully!']);
    }

    public function searchByName($name)
    {
        return auth()->user()->products()->where('name', 'LIKE', "%{$name}%")->get() ?? [];
    }

    public function searchById($id)
    {
        return auth()->user()->products()->find($id) ?? [];
    }

    public function products()
    {
        return auth()->user()->products()->get() ?? [];
    }

    public function changeProductCategory(Request $request, $id)
    {
        $data = [
            'newCategory' => $request->newCategory,
        ];
        $validator = Validator::make($data, [
            'newCategory' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 400);
        }
        $user = auth()->user();
        $product = $user->products()->find($id);
        if ($product) {
            $product->update([
                'category' => strtoupper($request->newCategory)
            ]);
            return response(['message' => 'Product category updated successfully!']);
        }
        return response(["message" => "Product doesn't exist!"], 404);
    }

    public function updateQuantity(Request $request, $id)
    {
        $data = [
            'quantity' => $request->quantity,
        ];
        $validator = Validator::make($data, [
            'quantity' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 400);
        }
        $user = auth()->user();
        $product = $user->products()->find($id);
        if ($product) {
            $product->update([
                'quantity' => $request->quantity
            ]);
            return response(['message' => 'Quantity Updated Successfully!']);
        }
        return response(["message" => "Product doesn't exist!"], 404);
    }

    public function updateUnitPrice(Request $request, $id)
    {
        $data = [
            'unitPrice' => $request->unitPrice,
        ];
        $validator = Validator::make($data, [
            'unitPrice' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 400);
        }
        $user = auth()->user();
        $product = $user->products()->find($id);
        if ($product) {
            $product->update([
                'unit_price' => $request->unitPrice
            ]);
            return response(['message' => 'Unit price updated successfully!']);
        }
        return response(["message" => "Product doesn't exist!"], 404);
    }

    public function updateSellingPrice(Request $request, $id)
    {
        $data = [
            'sellingPrice' => $request->sellingPrice,
        ];
        $validator = Validator::make($data, [
            'sellingPrice' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 400);
        }
        $user = auth()->user();
        $product = $user->products()->find($id);
        if ($product) {
            $product->update([
                'selling_price' => $request->sellingPrice
            ]);
            return response(['message' => 'Selling price updated successfully!']);
        }
        return response(["message" => "Product doesn't exist!"], 404);
    }

    public function deleteProduct($id)
    {
        $user = auth()->user();
        $product = $user->products()->find($id);
        if ($product) {
            $user->sales()->where('product_name', $product->name)->delete();
            $product->delete();
            return response(['message' => 'Product deleted successfully!']);
        }
        return response(["message" => "Product doesn't exist"], 404);
    }

    public function expenses()
    {
        $data = [];
        $user = auth()->user();
        $products = $user->products()->get();
        if ($products) {
            foreach ($products as $product) {
                $profit = $product->selling_price - $product->unit_price ;
                $percentage_profit = number_format($profit * 100 / $product->unit_price, 2);
                $data[] = [
                    'product' => $product->name,
                    'profit' => $profit,
                    'percentage_profit' => $percentage_profit
                ];
            }
            return response($data);
        }
        return [];
    }
}
