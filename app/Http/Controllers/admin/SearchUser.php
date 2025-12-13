<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use Exception;

class SearchUser extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // --- MẶC ĐỊNH role = 2 ---
        $role = $request->input('role', 2);

        // Filter theo trạng thái
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('isactive', 1);
            } elseif ($request->status == 'inactive') {
                $query->where('isactive', 0);
            }
        }

        // Filter theo loại tài khoản
        if (!empty($role)) {
            $query->where('role_id', $role);
        }

        $users = $query->get();
        $roles = Role::all();

        return view('admin.pages.searchuser.index', compact('users', 'roles', 'role'));
    }


    public function create()
    {
        return view('admin.pages.searchuser.create');
    }

    public function store(Request $request)
    {
        

        $request->validate([
            'name'  => 'required|max:255',
            'phone' => 'required|max:255|unique:users,phone',
        ], [
            'name.required' => 'Tên là bắt buộc.',
            'phone.required' => 'Số diện thoại là bắt buộc.',
            'phone.regex' => 'Số điện thoại không hợp lệ (9-11 số).',
            'phone.unique' => 'Số điện thoại đã tồn tại.'
        ]);

        User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'isactive' => 1,
            'role_id' => 2,
            'password'=>null,
            'create_date' => now(),
            'create_by' => Auth::user()->id,
        ]);

        return redirect()->route('list_user')->with('success', 'Tạo tài khoản thành công!');
    }
    public function show($id)
    {
        $user = User::findOrFail($id);
        $status = request('status', 'all');
        $from_date = request('from_date');
        $to_date = request('to_date');

        $query = Order::where('user_id', $id);

        // Lọc theo trạng thái
        if ($status == 'success') {
            $query->where('isactive', 1);
        } elseif ($status == 'cancelled') {
            $query->where('isactive', 0);
        }

        // 👉 Thêm lọc theo thời gian
        if ($from_date) {
            $query->whereDate('create_date', '>=', $from_date);
        }

        if ($to_date) {
            $query->whereDate('create_date', '<=', $to_date);
        }

        $orders = $query->orderBy('create_date', 'desc')->get();

        return view(
            'admin.pages.searchuser.show',
            compact('user', 'orders', 'status')
        );
    }


    public function getDetail($userId)
    {
        $user = User::findOrFail($userId);
        $status = request('status', 'all');

        $query = Order::where('user_id', $userId);

        // Filter theo trạng thái đơn
        if ($status == 'success') {
            $query->where('isactive', 1);
        } elseif ($status == 'cancelled') {
            $query->where('isactive', 0);
        }

        $orders = $query->orderBy('create_date', 'desc')->with('details')->get();

        return response()->json([
            'user' => $user,
            'orders' => $orders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'create_date' => $order->create_date,
                    'total' => $order->total,
                    'item_count' => $order->details->count(),
                    'isactive' => $order->isactive,
                    'status' => $order->isactive ? 'Thành công' : 'Hủy'
                ];
            })
        ]);
    }

    public function toggleStatus($userId)
    {
        $user = User::findOrFail($userId);
        $user->isactive = !$user->isactive;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => $user->isactive ? 'Mở khóa tài khoản thành công' : 'Khóa tài khoản thành công',
            'isactive' => $user->isactive
        ]);
    }

    public function createUser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name'  => 'required|max:255',
                'phone' => 'required|max:255|unique:users,phone',
            ], [
                'name.required' => 'Tên là bắt buộc.',
                'phone.required' => 'Số điện thoại là bắt buộc.',
                'phone.unique' => 'Số điện thoại đã tồn tại.'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = User::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'isactive' => 1,
                'role_id' => 2,
                'password' => null,
                'create_date' => now(),
                'create_by' => Auth::user()->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tạo tài khoản thành công!',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'phone' => $user->phone
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
