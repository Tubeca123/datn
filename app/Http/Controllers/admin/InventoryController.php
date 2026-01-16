<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Product;
use Carbon\Carbon;

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
            ->orderBy('date_end', 'desc')
            ->get();
        return view('admin.pages.inventory.batch', compact('product', 'batches'));
    }

    public function toggleBatch($batchId)
    {
        $batch = Inventory::findOrFail($batchId);
        $batch->isactive = $batch->isactive ? 0 : 1;
        $batch->save();

        $action = $batch->isactive ? 'Kích hoạt' : 'Vô hiệu hóa';

        return response()->json([
            'success' => true,
            'message' => "$action lô hàng thành công",
            'isactive' => $batch->isactive
        ]);
    }

    public function importHistory(Request $request)
    {
        $today = Carbon::today();
        $thirtyDaysFromNow = $today->copy()->addDays(30);

        $query = Inventory::with(['product', 'creator'])
            ->orderBy('create_date', 'desc');


        if ($request->filled('date_from')) {
            $query->whereDate('create_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('create_date', '<=', $request->date_to);
        }

        if ($request->filled('create_by')) {
            $query->where('create_by', $request->create_by);
        }

        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'expired') {
                $query->where('date_end', '<', $today)
                    ->where('stock_quantity', '>', 0);
            } elseif ($status === 'expiring') {
                $query->where('date_end', '>=', $today)
                    ->where('date_end', '<=', $thirtyDaysFromNow)
                    ->where('stock_quantity', '>', 0);
            }
        }

        $histories = $query->paginate(20);

        return view('admin.pages.inventory.history', compact('histories'));
    }


    public function getExpiryNotifications()
    {
        $today = Carbon::today();
        $thirtyDaysFromNow = $today->copy()->addDays(30);


        $expiredBatches = Inventory::with(['product', 'creator'])
            ->where('isactive', 1)
            ->where('date_end', '<', $today)
            ->where('stock_quantity', '>', 0)
            ->orderBy('date_end', 'asc')
            ->limit(5)
            ->get();


        $expiringBatches = Inventory::with(['product', 'creator'])
            ->where('isactive', 1)
            ->where('date_end', '>=', $today)
            ->where('date_end', '<=', $thirtyDaysFromNow)
            ->where('stock_quantity', '>', 0)
            ->orderBy('date_end', 'asc')
            ->limit(5)
            ->get();


        $expiredCount = Inventory::where('date_end', '<', $today)
            ->where('isactive', 1)
            ->where('stock_quantity', '>', 0)
            ->count();


        $expiringCount = Inventory::whereBetween('date_end', [$today, $thirtyDaysFromNow])
            ->where('isactive', 1)
            ->where('stock_quantity', '>', 0)
            ->count();

        return response()->json([
            'expired' => [
                'batches' => $expiredBatches,
                'count' => $expiredCount
            ],
            'expiring' => [
                'batches' => $expiringBatches,
                'count' => $expiringCount
            ],
            'total_alerts' => $expiredCount + $expiringCount
        ]);
    }
}
