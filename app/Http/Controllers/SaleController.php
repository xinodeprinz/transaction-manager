<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\Sale;

class SaleController extends Controller
{
    public function sell(Request $request, $id)
    {
        $data = [
            'quantity' => $request->quantity,
            'date' => $request->date,
        ];
        $validator = Validator::make($data, [
            'quantity' => ['required', 'string'],
            'date' => ['required', 'date'],
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 400);
        }
        $user = auth()->user();
        $product = $user->products()->find($id);
        if ($product) {
            if ($product->quantity > $data['quantity']) {
                $sales = $user->sales()->where('product_name', $product->name)->get();
                $sale = $sales->where('date', $data['date'])->first();
                if ($sale) {
                    $sale->product_name = $product->name;
                    $sale->quantity += $data['quantity'];
                    $sale->update();
                } else {
                    $user->sales()->create([
                        'product_name' => $product->name,
                        'quantity' => $data['quantity'],
                        'date' => $data['date'],
                    ]);
                }
                $product->quantity -= $data['quantity'];
                $product->update();
                return response(['message' => 'Products sold successfully!']);
            }
            return response(['message' => 'Out of Stock'], 403);
        }
        return response(["message" => "Product doesn't exits!"], 404);
    }

    public function totalSales()
    {
        return auth()->user()->sales()->withTrashed()->get() ?? [];
    }

    public function expenses()
    {
        $profit = $quantity = $total_amount = 0;
        $user = auth()->user();
        $sales = $user->sales()->withTrashed()->get();
        if ($sales) {
            foreach ($sales as $sale) {
                $product = $user->products()->withTrashed()->where('name', $sale->product_name)->first();
                $quantity += $sale->quantity;
                $profit += $sale->quantity * ( $product->selling_price - $product->unit_price );
                $total_amount += $sale->quantity * $product->unit_price;
            }
            $percentage_profit = number_format($profit * 100 / $total_amount, 2);
            return response([
                'total_quantity_sold' => $quantity,
                'total_profit' => $profit,
                'percentage_profit' => $percentage_profit
            ]);
        }
        return [];
    }
}
