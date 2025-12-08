<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\User;

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

    
    public function getInventories($productId, $productUnitId)
    {
        $product = Product::findOrFail($productId);
        $productUnit = $product->units()->findOrFail($productUnitId);

        
        $inventories = $product->inventory()
            ->where('stock_quantity', '>', 0)
            ->orderBy('date_end', 'asc')
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($inv) use ($productUnit) {
                $qtyPerUnit = max(1, $productUnit->quantity_per_unit);
                return [
                    'id' => $inv->id,
                    'code' => $inv->code,
                    'date_end' => $inv->date_end ? \Carbon\Carbon::parse($inv->date_end)->format('d/m/Y') : 'N/A',
                    'stock_base' => $inv->stock_quantity,  // số viên
                    'stock_units' => floor($inv->stock_quantity / $qtyPerUnit),  // quy đổi thành unit
                    'remainder' => $inv->stock_quantity % $qtyPerUnit  
                ];
            });

        return response()->json($inventories);
    }


    public function store(Request $request)
    {
        // Validate request
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:product,id',
            'items.*.unit_id' => 'required|integer|exists:product_unit,id',
            'items.*.inventory_id' => 'required|integer|exists:inventory,id', 
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.price' => 'required|numeric|min:0',
            'customer_id' => 'nullable|integer|exists:users,id'
        ]);

        $items = $request->items;
        $userId = $request->customer_id;

        try {
            return DB::transaction(function () use ($items, $userId) {

                // 1. Kiểm tra tồn kho từ lô cụ thể
                foreach ($items as $item) {
                    $inventory = Inventory::findOrFail($item['inventory_id']);
                    $product = Product::findOrFail($item['product_id']);
                    $productUnit = $product->units()->findOrFail($item['unit_id']);

                    // Tính số viên cần
                    $qtyPerUnit = max(1, $productUnit->quantity_per_unit);
                    $neededBase = $item['quantity'] * $qtyPerUnit;

                    // Kiểm tra lô này có đủ không
                    if ($inventory->stock_quantity < $neededBase) {
                        throw new Exception(
                            "Lô {$inventory->code} của sản phẩm '{$product->name}' không đủ. " .
                            "Còn lại: {$inventory->stock_quantity} viên (= " . floor($inventory->stock_quantity / $qtyPerUnit) . " {$productUnit->unit->name}), " .
                            "yêu cầu: {$neededBase} viên"
                        );
                    }
                }

                // 2. Tính tổng tiền
                $total = collect($items)->sum(function ($item) {
                    return $item['price'] * $item['quantity'];
                });

                // 3. Tạo đơn hàng
                $order = Order::create([
                    'user_id' => $userId,
                    'total' => $total,
                    'create_date' => now(),
                    'create_by' => Auth::user()->id,
                    'isactive' => 1
                ]);

                // 4. Trừ tồn kho từ lô cụ thể
                foreach ($items as $item) {
                    $inventory = Inventory::findOrFail($item['inventory_id']);
                    $product = Product::findOrFail($item['product_id']);
                    $productUnit = $product->units()->findOrFail($item['unit_id']);

                    $qtyPerUnit = max(1, $productUnit->quantity_per_unit);
                    $neededBase = $item['quantity'] * $qtyPerUnit;

                    // Trừ tồn kho từ lô này
                    $inventory->stock_quantity -= $neededBase;
                    $inventory->save();

                    // Tạo 1 OrderDetail cho lô này
                    OrderDetail::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'],
                        'code' => $inventory->code,
                        'product_unit_id' => $item['unit_id'],
                        'inventory_id' => $item['inventory_id'],
                        'quantity' => $item['quantity'],  // ← Số lượng mà user yêu cầu
                        'price' => $item['price'],
                        'isactive' => 1
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'order_id' => $order->id,
                    'total' => $total,
                    'message' => 'Tạo đơn thuốc thành công!'
                ]);
            });
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }
    public function index(Request $request)
    {
        $query = Order::with(['user', 'details.product'])
            ->where('isactive', 1);

        // Filter theo ngày
        if ($request->filled('date_from')) {
            $query->whereDate('create_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('create_date', '<=', $request->date_to);
        }

        

        $orders = $query->orderBy('create_date', 'desc')
            ->paginate(20)
            ->appends($request->all()); 

        return view('admin.pages.order.index', compact('orders'));
    }

    /**
     * Xem chi tiết đơn hàng
     */
    public function show($id)
    {
        $order = Order::with([
            'user',
            'details' => function ($query) {
                $query->where('isactive', 1);
            },
            'details.product' => function ($query) {
                $query->select('id', 'name', 'description');
            },
            'details.inventory'
        ])->findOrFail($id);

        // Tính toán thống kê
        $totalItems = $order->details->count();
        $totalQuantity = $order->details->sum('quantity');
        $calculatedTotal = $order->details->sum(function ($detail) {
            return $detail->quantity * $detail->getPrice(); // Sử dụng method getPrice()
        });

        return view('admin.pages.order.show', compact('order', 'totalItems', 'totalQuantity', 'calculatedTotal'));
    }

    /**
     * Hủy đơn hàng (hoàn trả tồn kho đúng lô)
     */
    public function cancel($id)
    {
        try {
            return DB::transaction(function () use ($id) {
                $order = Order::with('details')->findOrFail($id);

                if ($order->isactive == 0) {
                    throw new Exception('Đơn hàng đã bị hủy trước đó');
                }

                // Hoàn trả tồn kho theo từng lô
                foreach ($order->details as $detail) {
                    if ($detail->inventory_id) {
                        // Hoàn trả vào lô nguyên bản
                        $inventory = Inventory::find($detail->inventory_id);
                        if ($inventory) {
                            // Quy đổi quantity → quantity_base
                            $productUnit = $detail->productUnit;
                            $qtyPerUnit = max(1, $productUnit->quantity_per_unit ?? 1);
                            $baseQty = $detail->quantity * $qtyPerUnit;

                            $inventory->stock_quantity += $baseQty;
                            $inventory->save();
                        }
                    } else {
                        // Fallback: nếu không có inventory_id (dữ liệu cũ), tạo lô hoàn trả mới
                        $product = Product::find($detail->product_id);
                        $product->increaseStock(
                            $detail->product_unit_id,
                            $detail->quantity,
                            'RETURN-' . $order->id,
                            null,
                            Auth::user()->id
                        );
                    }
                }

                // Đánh dấu đơn hàng đã hủy
                $order->update([
                    'isactive' => 0,
                    'update_date' => now(),
                    'update_by' => Auth::user()->id
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Đã hủy đơn hàng và hoàn trả tồn kho'
                ]);
            });
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }
    public function findByPhone(Request $request)
    {
        $phone = $request->phone;
        $customer = User::where('phone', $phone)->first();

        if (!$customer) {
            return response()->json(['message' => 'not found'], 404);
        }

        return response()->json([
            'id' => $customer->id,
            'name' => $customer->name
        ]);
    }
}
