<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return ($users);
        // return view('admin.pages.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function register()
    {
        return view('admin.pages.user.register');
    }
    public function store(Request $request)
    {
        // Xác thực dữ liệu bắt buộc
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'nullable|regex:/^[0-9]{9,11}$/|unique:users,phone',
            'address' => 'nullable|string|max:255',
            'password' => 'required|min:6|confirmed',
        ], [
            'name.required' => 'Tên là bắt buộc.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã tồn tại.',
            'phone.regex' => 'Số điện thoại không hợp lệ (9-11 số).',
            'phone.unique' => 'Số điện thoại đã tồn tại.',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        // Kiểm tra phải có email hoặc phone
        if (!$request->email && !$request->phone) {
            return back()->withErrors(['contact' => 'Vui lòng nhập email hoặc số điện thoại.'])->withInput();
        }

        // Tạo người dùng mới
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role_id' => 2,
            'isactive' => 1,
        ]);

        return redirect('/login')->with('success', 'Đăng kí thành công! Vui lòng đăng nhập.');
    }

    public function login()
    {
        return view('admin.pages.user.login');
    }

    public function logout(Request $request)
    {

        $request->session()->flush();


        return redirect('/admin/login');
    }
    public function loginIn(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ], [
            'email.required' => 'Vui lòng nhập email hoặc số điện thoại.',
            'password.required' => 'Mật khẩu là bắt buộc.',
        ]);

        $loginInput = $request->email;
        $user = null;

        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $loginInput)->first();
        } else {
            if (!preg_match('/^[0-9]{9,11}$/', $loginInput)) {
                return back()->withErrors(['email' => 'Số điện thoại không hợp lệ (9–11 số).'])
                    ->withInput();
            }
            $user = User::where('phone', $loginInput)->first();
        }
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);

            return redirect()->route('trang_chu')->with('success', 'Đăng nhập thành công!');
        }

        return back()->withErrors(['login_error' => 'Thông tin đăng nhập không hợp lệ.'])->withInput();
    }
}
