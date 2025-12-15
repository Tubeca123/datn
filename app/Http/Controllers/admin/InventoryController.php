<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Product;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand'])
            ->withSum('inventory as total_stock', 'stock_quantity');

        $products = $query->orderBy('name')->paginate(20);

        return view('admin.pages.inventory.index', compact('products'));
    }
    public function batchDetails($productId)
    {
        $product =  Product::with(['category', 'brand'])->findOrFail($productId);
        $batches =  Inventory::where('product_id', $productId)
            ->orderBy('date_end', 'asc')
            ->get();
        return view('admin.pages.inventory.batch', compact('product', 'batches'));
    }
}
