<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\News;
use App\Models\Banner;
use App\Models\Brand;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class HomeController extends Controller
{
    public function index()
    {
        $list_category = Category::where('position', 1)->get();
        foreach ($list_category as $category) {
            $products = $category->products()->where('isactive', 1)->with('images')->take(3)->get();
            $count = $category->products()->where('isactive', 1)->count();
            $images = [];
            foreach ($products as $p) {
                $images[] = $p->images[0]->src ?? 'default.jpg';
            }
            $category->products_image = $images;
            $category->count = $count;
        }
        $list_product_sold_count = Product::where('isactive', 1)->orderBy('sold_count', 'desc')->take(10)->with('images')->get();
        $list_product_new = Product::where('isactive', 1)->orderBy('create_date', 'desc')->take(10)->with('images')->get();
        // dd($list_product_new[1]->images[0]->src);
        $news = News::where('isactive', 1)->orderBy('create_date', 'desc')->with('news_categories')->take(10)->get();
        return view('users.pages.index', compact('list_category', 'list_product_sold_count', 'list_product_new', 'news'));
    }
    public function shop()
    {
        $banners = Banner::where('isactive', 1)->where('position', 2)->take(2)->get();
        $products = Product::where('isactive', 1)->orderBy('create_date', 'desc')->with('images')->paginate(12);
        $brand = Brand::where('isactive', 1)->take(10)->get();
        return view('users.pages.shop', compact('banners', 'products', 'brand'));
    }
    public function product(Request $request)
    {
        $product = Product::where('id', $request->product_id)->where('isactive', 1)->with('images', 'category', 'brand')->first();
        if (!$product) {
            return redirect()->route('shop')->with('error', 'Sản phẩm không tồn tại hoặc đã bị vô hiệu hóa.');
        }
        dd($product);
        return view('users.pages.product', compact('product'));
    }

    public function about()
    {
        return view('users.pages.about');
    }
    public function blog()
    {
        return view('users.pages.blog');
    }
    public function contact()
    {
        return view('users.pages.contact');
    }
    public function cart()
    {
        return view('users.pages.cart');
    }
    public function checkout()
    {
        return view('users.pages.checkout');
    }
    public function compare()
    {
        return view('users.pages.compare');
    }
    public function faq()
    {
        return view('users.pages.faq');
    }
    public function login()
    {
        return view('users.pages.login');
    }
    public function handleLogin(Request $request)
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

            return redirect()->route('home')->with('success', 'Đăng nhập thành công!');
        }

        return back()->withErrors(['login_error' => 'Thông tin đăng nhập không hợp lệ.'])->withInput();
    }
    public function register()
    {

        return view('users.pages.register');
    }
    public function handleRegister(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'password' => 'required|min:3|confirmed',
        ], [
            'email.unique' => 'Email đã tồn tại',
            'password.confirmed' => 'Mật khẩu nhập lại không khớp',
        ]);
        // tạo tài khoản
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'role_id' => 2,                 // ví dụ: 2 = user thường
            'password' => Hash::make($request->password),
            'isactive' => 1,
        ]);

        return redirect()->route('user_login')->with('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Đăng xuất thành công! Vui lòng đăng nhập.');
    }
}
