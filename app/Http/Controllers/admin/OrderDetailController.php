<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;
class OderDetailController extends Controller
{
    /**
     * Form sửa đơn hàng
     */
    public function edit($id)
    {
        $order = Order::with([
            'user',
            'details' => function ($query) {
                $query->where('isactive', 1);
            },
            'details.product',
            'details.product.brand',
            'details.product.category',
            'details.productUnit',
            'details.productUnit.unit',
            'details.inventory'
        ])->findOrFail($id);

        if ($order->isactive == 0) {
            return redirect()->route('admin.orders.show', $id)
                ->with('error', 'Không thể sửa đơn hàng đã hủy');
        }

        return view('admin.pages.order.edit', compact('order'));
    }

    /**
     * Cập nhật đơn hàng
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.detail_id' => 'nullable|integer', // null = item mới
            'items.*.product_id' => 'required|integer|exists:product,id',
            'items.*.unit_id' => 'required|integer|exists:product_unit,id',
            'items.*.inventory_id' => 'required|integer|exists:inventory,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        try {
            return DB::transaction(function () use ($request, $id) {
                $order = Order::with('details')->findOrFail($id);

                if ($order->isactive == 0) {
                    throw new Exception('Không thể sửa đơn hàng đã hủy');
                }

                $items = $request->items;
                $existingDetailIds = $order->details->pluck('id')->toArray();
                $updatedDetailIds = [];

                // 1. Xử lý từng item
                foreach ($items as $item) {
                    $productUnit = \App\Models\ProductUnit::findOrFail($item['unit_id']);
                    $qtyPerUnit = max(1, $productUnit->quantity_per_unit);
                    $neededBase = $item['quantity'] * $qtyPerUnit;

                    // Kiểm tra tồn kho
                    $inventory = Inventory::findOrFail($item['inventory_id']);

                    if (isset($item['detail_id']) && $item['detail_id']) {
                        // CẬP NHẬT item cũ
                        $detail = OrderDetail::findOrFail($item['detail_id']);
                        $oldQtyBase = $detail->quantity * max(1, $detail->productUnit->quantity_per_unit ?? 1);

                        // Hoàn trả tồn kho cũ
                        if ($detail->inventory_id) {
                            $oldInventory = Inventory::find($detail->inventory_id);
                            if ($oldInventory) {
                                $oldInventory->stock_quantity += $oldQtyBase;
                                $oldInventory->save();
                            }
                        }

                        // Kiểm tra tồn mới
                        if ($inventory->stock_quantity < $neededBase) {
                            throw new Exception(
                                "Lô {$inventory->code} không đủ tồn kho. " .
                                    "Còn: {$inventory->stock_quantity} viên, cần: {$neededBase} viên"
                            );
                        }

                        // Trừ tồn mới
                        $inventory->stock_quantity -= $neededBase;
                        $inventory->save();

                        // Cập nhật detail
                        $detail->update([
                            'product_id' => $item['product_id'],
                            'code' => $inventory->code,
                            'product_unit_id' => $item['unit_id'],
                            'inventory_id' => $item['inventory_id'],
                            'quantity' => $item['quantity'],
                            'price' => $item['price'],
                        ]);

                        $updatedDetailIds[] = $detail->id;
                    } else {
                        // THÊM MỚI item
                        if ($inventory->stock_quantity < $neededBase) {
                            throw new Exception(
                                "Lô {$inventory->code} không đủ tồn kho. " .
                                    "Còn: {$inventory->stock_quantity} viên, cần: {$neededBase} viên"
                            );
                        }

                        // Trừ tồn
                        $inventory->stock_quantity -= $neededBase;
                        $inventory->save();

                        // Tạo detail mới
                        $newDetail = OrderDetail::create([
                            'order_id' => $order->id,
                            'product_id' => $item['product_id'],
                            'code' => $inventory->code,
                            'product_unit_id' => $item['unit_id'],
                            'inventory_id' => $item['inventory_id'],
                            'quantity' => $item['quantity'],
                            'price' => $item['price'],
                            'isactive' => 1
                        ]);

                        $updatedDetailIds[] = $newDetail->id;
                    }
                }

                // 2. XÓA các item không còn trong danh sách (soft delete)
                $deletedIds = array_diff($existingDetailIds, $updatedDetailIds);
                foreach ($deletedIds as $delId) {
                    $detail = OrderDetail::find($delId);
                    if ($detail && $detail->isactive == 1) {
                        // Hoàn trả tồn kho
                        if ($detail->inventory_id) {
                            $inventory = Inventory::find($detail->inventory_id);
                            if ($inventory) {
                                $qtyBase = $detail->quantity * max(1, $detail->productUnit->quantity_per_unit ?? 1);
                                $inventory->stock_quantity += $qtyBase;
                                $inventory->save();
                            }
                        }

                        // Đánh dấu xóa
                        $detail->update(['isactive' => 0]);
                    }
                }

                // 3. Cập nhật tổng tiền
                $newTotal = $order->details()
                    ->where('isactive', 1)
                    ->get()
                    ->sum(function ($d) {
                        return $d->quantity * $d->price;
                    });

                $order->update([
                    'total' => $newTotal,
                    'update_date' => now(),
                    'update_by' => Auth::user()->id
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Cập nhật đơn hàng thành công!',
                    'order_id' => $order->id,
                    'new_total' => $newTotal
                ]);
            });
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Xóa 1 sản phẩm khỏi đơn hàng
     */
    public function deleteDetail($orderId, $detailId)
    {
        try {
            return DB::transaction(function () use ($orderId, $detailId) {
                $order = Order::findOrFail($orderId);
                $detail = OrderDetail::where('order_id', $orderId)
                    ->where('id', $detailId)
                    ->firstOrFail();

                if ($order->isactive == 0) {
                    throw new Exception('Không thể xóa sản phẩm từ đơn đã hủy');
                }

                // Hoàn trả tồn kho
                if ($detail->inventory_id) {
                    $inventory = Inventory::find($detail->inventory_id);
                    if ($inventory) {
                        $qtyBase = $detail->quantity * max(1, $detail->productUnit->quantity_per_unit ?? 1);
                        $inventory->stock_quantity += $qtyBase;
                        $inventory->save();
                    }
                }

                // Xóa detail
                $detail->update(['isactive' => 0]);

                // Cập nhật tổng tiền
                $newTotal = $order->details()
                    ->where('isactive', 1)
                    ->sum(DB::raw('quantity * price'));

                $order->update([
                    'total' => $newTotal,
                    'update_date' => now(),
                    'update_by' => Auth::user()->id
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Đã xóa sản phẩm',
                    'new_total' => $newTotal
                ]);
            });
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Cập nhật 1 chi tiết đơn hàng
     */
    public function updateDetail(Request $request, $orderId, $detailId)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:0.01',
            'unit_id' => 'required|integer|exists:product_unit,id',
            'inventory_id' => 'required|integer|exists:inventory,id',
        ]);

        try {
            return DB::transaction(function () use ($request, $orderId, $detailId) {
                $order = Order::findOrFail($orderId);
                $detail = OrderDetail::where('order_id', $orderId)
                    ->where('id', $detailId)
                    ->with('productUnit')
                    ->firstOrFail();

                if ($order->isactive == 0) {
                    throw new Exception('Không thể sửa đơn đã hủy');
                }

                // Hoàn trả tồn cũ
                if ($detail->inventory_id) {
                    $oldInventory = Inventory::find($detail->inventory_id);
                    if ($oldInventory) {
                        $oldQtyBase = $detail->quantity * max(1, $detail->productUnit->quantity_per_unit ?? 1);
                        $oldInventory->stock_quantity += $oldQtyBase;
                        $oldInventory->save();
                    }
                }

                // Kiểm tra và trừ tồn mới
                $newUnit = \App\Models\ProductUnit::findOrFail($request->unit_id);
                $newInventory = Inventory::findOrFail($request->inventory_id);
                $newQtyBase = $request->quantity * max(1, $newUnit->quantity_per_unit);

                if ($newInventory->stock_quantity < $newQtyBase) {
                    throw new Exception('Không đủ tồn kho');
                }

                $newInventory->stock_quantity -= $newQtyBase;
                $newInventory->save();

                // Cập nhật detail
                $detail->update([
                    'product_unit_id' => $request->unit_id,
                    'inventory_id' => $request->inventory_id,
                    'code' => $newInventory->code,
                    'quantity' => $request->quantity,
                    'price' => $newUnit->price_sale,
                ]);

                // Cập nhật tổng tiền
                $newTotal = $order->details()
                    ->where('isactive', 1)
                    ->sum(DB::raw('quantity * price'));

                $order->update([
                    'total' => $newTotal,
                    'update_date' => now(),
                    'update_by' => Auth::user()->id
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Cập nhật thành công',
                    'new_total' => $newTotal
                ]);
            });
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
