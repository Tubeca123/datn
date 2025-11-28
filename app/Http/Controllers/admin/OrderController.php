<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function create()
    {
        return view('admin.pages.order.create');
    }
    public function searchProduct(Request $request)
    {
        $key = $request->q;

        $products = Product::where('name', 'LIKE', "%$key%")
            ->where('isactive', 1)
            ->limit(10)
            ->get();

        return response()->json($products);
    }


    public function getUnits($productId)
    {
        $product = Product::with(['units.unit'])->findOrFail($productId);

        $units = $product->units->map(function ($u) use ($product) {
            $stock = $product->stockByProductUnit($u->id);

            return [
                'id' => $u->id,
                'unit_name' => $u->unit->name,
                'price' => $u->price_sale,
                'quantity_per_unit' => $u->quantity_per_unit,
                'stock_units' => $stock['units'],
                'remainder' => $stock['remainder_base']
            ];
        });

        return response()->json($units);
    }

    public function store(Request $request)
    {
        $items = $request->items;
        $userId = auth::user()->id;

        if (!$items || count($items) == 0) {
            return response()->json(['error' => 'Đơn trống!'], 400);
        }

        $total = collect($items)->sum(function ($i) {
            return $i['price'] * $i['quantity'];
        });

        $order = Order::create([
            'user_id' => $userId,
            'total' => $total,
            'create_date' => now(),
            'create_by' => $userId,
            'isactive' => 1
        ]);

        foreach ($items as $i) {
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $i['product_id'],
                'quantity' => $i['quantity'],
                'isactive' => 1
            ]);

            // Trừ tồn FIFO
            $p = Product::find($i['product_id']);
            $p->decreaseStockFIFO($i['unit_id'], $i['quantity']);
        }

        return response()->json(['success' => true, 'order_id' => $order->id]);
    }
}
