<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart_detail;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderDetail;
class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:product,id',
            'product_unit_id' => 'required|integer|exists:product_unit,id',
            'quantity' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('user_login')->withErrors('Bạn cần đăng nhập để thêm vào giỏ hàng!');
        }

        $product = Product::findOrFail($request->product_id);
        $productUnit = ProductUnit::findOrFail($request->product_unit_id);
        $quantity = (float) $request->quantity;
        $qtyPerUnit = max(1, $productUnit->quantity_per_unit);
        $neededBase = $quantity * $qtyPerUnit;



        $inventory = $product->inventory()
            ->where('stock_quantity', '>=', $neededBase)
            ->whereDate('date_end', '>=', Carbon::today())
            ->orderBy('date_end', 'asc')
            ->orderBy('id', 'asc')
            ->first();
        // dd($inventory);
        if (!$inventory) {
            return back()->withErrors('Không đủ tồn kho cho đơn vị/lô đã chọn!');
        }

        // Kiểm tra đã có sp này với inventory này, đơn vị này trong giỏ chưa
        $cartItem = Cart_detail::where([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'product_unit_id' => $productUnit->id,
            'inventory_id' => $inventory->id,
            'isactive' => 1,
        ])->first();

        if ($cartItem) {
            // Nếu đã có, tăng quantity
            $cartItem->quantity += $quantity;
            $cartItem->price = $productUnit->price_sale;
            $cartItem->update_date = now();
            $cartItem->update_by = $user->id;
            $cartItem->save();
        } else {
            // Nếu chưa có, tạo mới
            Cart_detail::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'product_unit_id' => $productUnit->id,
                'inventory_id' => $inventory->id,
                'code' => $inventory->code,
                'price' => $productUnit->price_sale,
                'quantity' => $quantity,
                'create_date' => now(),
                'create_by' => $user->id,
                'isactive' => 1
            ]);
        }

        return back()->with('success', 'Đã thêm vào giỏ hàng!');
    }
    public function cart()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('user_login')->withErrors('Bạn cần đăng nhập để xem giỏ hàng.');
        }

        // lấy các item trong giỏ của user, chỉ lấy item active và eager load quan hệ
        $cartItems = Cart_detail::where('user_id', $user->id)
            ->where('isactive', 1)
            ->with(['product', 'productUnit', 'inventory'])
            ->get();

        // subtotal
        $subtotal = $cartItems->reduce(function ($carry, $item) {
            return $carry + ($item->price * $item->quantity);
        }, 0.0);

        // bạn có thể tính phí ship ở đây hoặc để 0
        $delivery = 0.0;
        $total = $subtotal + $delivery;

        return view('users.pages.cart', compact('cartItems', 'subtotal', 'delivery', 'total'));
    }
    public function update(Request $request)
    {
        $request->validate([
            'cart_id'  => 'required|exists:cart_detail,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart_detail::findOrFail($request->cart_id);

        // Kiểm tra tồn kho trước khi update
        $productUnit = $cart->productUnit;
        $neededBase = $request->quantity * $productUnit->quantity;

        $inventory = $cart->product->inventory()
            ->where('date_end', '>=', now()->startOfDay())
            ->where('stock_quantity', '>=', $neededBase)
            ->where('isactive', 1)
            ->orderBy('date_end', 'asc')
            ->orderBy('id', 'asc')
            ->first();

        if (!$inventory) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không đủ tồn kho còn hạn sử dụng!'
                ], 400);
            }
            return back()->withErrors('Không đủ tồn kho còn hạn sử dụng!');
        }

        $cart->quantity = $request->quantity;
        $cart->update_date = now();
        $cart->update_by = Auth::id();
        $cart->save();



        // Nếu là form submit thông thường
        return back()->with('success', 'Cập nhật số lượng thành công!');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|integer|exists:cart_detail,id',
        ]);

        $cart = Cart_detail::findOrFail($request->cart_id);
        // bạn có thể xóa (delete) hoặc set isactive = 0
        $cart->isactive = 0;
        $cart->update_date = now();
        $cart->update_by = Auth::id();
        $cart->save();

        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }

    public function checkout()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('user_login')->withErrors('Bạn cần đăng nhập để thanh toán.');
        }

        // Lấy các item trong giỏ
        $cartItems = Cart_detail::where('user_id', $user->id)
            ->where('isactive', 1)
            ->with(['product', 'productUnit', 'inventory'])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->withErrors('Giỏ hàng trống!');
        }

        // Tính tổng tiền
        $subtotal = $cartItems->reduce(function ($carry, $item) {
            return $carry + ($item->price * $item->quantity);
        }, 0.0);

        $delivery = 0.0;
        $total = $subtotal + $delivery;

        return view('users.pages.checkout', compact('cartItems', 'subtotal', 'delivery', 'total'));
    }

    public function processOrder(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('user_login')->withErrors('Bạn cần đăng nhập.');
        }

        DB::beginTransaction();
        try {
            // Lấy cart items
            $cartItems = Cart_detail::where('user_id', $user->id)
                ->where('isactive', 1)
                ->with(['product', 'productUnit', 'inventory'])
                ->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('cart')->withErrors('Giỏ hàng trống!');
            }

            // Kiểm tra tồn kho cho tất cả sản phẩm trước khi tạo đơn
            foreach ($cartItems as $item) {
                $productUnit = $item->productUnit;
                $neededBase = $item->quantity * $productUnit->quantity;

                $inventory = $item->product->inventory()
                    ->where('id', $item->inventory_id)
                    ->where('date_end', '>=', now()->startOfDay())
                    ->where('stock_quantity', '>=', $neededBase)
                    ->where('isactive', 1)
                    ->first();

                if (!$inventory) {
                    DB::rollBack();
                    return back()->withErrors("Sản phẩm '{$item->product->name}' không đủ tồn kho hoặc đã hết hạn!");
                }
            }

            // Tính tổng tiền
            $total = $cartItems->reduce(function ($carry, $item) {
                return $carry + ($item->price * $item->quantity);
            }, 0.0);

            // Tạo Order với isactive = 2
            $order = Order::create([
                'user_id' => $user->id,
                'total' => $total,
                'create_date' => now(),
                'create_by' => $user->id,
                'update_date' => now(),
                'update_by' => $user->id,
                'isactive' => 2, // Trạng thái đơn hàng mới tạo
            ]);

            // Tạo OrderDetail và trừ tồn kho
            foreach ($cartItems as $item) {
                // Tạo order detail
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'inventory_id' => $item->inventory_id,
                    'code' => $item->code,
                    'product_unit_id' => $item->product_unit_id,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'isactive' => 1,
                ]);

                // Trừ tồn kho
                $inventory = Inventory::find($item->inventory_id);
                if ($inventory) {
                    $productUnit = $item->productUnit;
                    $neededBase = $item->quantity * $productUnit->quantity;

                    $inventory->stock_quantity -= $neededBase;
                    $inventory->save();
                }

                // Xóa item khỏi giỏ hàng (set isactive = 0)
                $item->isactive = 0;
                $item->update_date = now();
                $item->update_by = $user->id;
                $item->save();
            }

            DB::commit();

            return redirect()->route('order.success', ['order_id' => $order->id])
                ->with('success', 'Đặt hàng thành công! Mã đơn hàng: #' . $order->id);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function orderSuccess($order_id)
    {
        $order = Order::with(['details.product', 'details.productUnit.unit'])
            ->where('user_id', Auth::id())
            ->findOrFail($order_id);

        return view('users.pages.order_success', compact('order'));
    }
}
