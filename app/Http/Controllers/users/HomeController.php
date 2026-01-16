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
use App\Models\News_category;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class HomeController extends Controller
{
    public function index()
    {
        $list_category = Category::where('position', 2)->take(6)->get();
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


        $topProductIds = OrderDetail::selectRaw('product_id, SUM(quantity) as total_sold')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(10)
            ->pluck('product_id');

        $list_product_sold_count = Product::with('images')
            ->whereIn('id', $topProductIds)
            ->with([
                'units.unit'
            ])
            ->get();

        // $list_product_sold_count2 = Product::where('isactive', 1)->orderBy('sold_count', 'desc')->take(10)->with('images')->get();
        $list_product_new = Product::where('isactive', 1)->orderBy('create_date', 'desc')->take(10)->with('images')->get();

        $news = News::where('isactive', 1)->orderBy('create_date', 'desc')->with('news_categories')->get();
        return view('users.pages.index', compact('list_category', 'list_product_sold_count', 'list_product_new', 'news'));
    }
    // public function shop()
    // {
    //     $products = Product::where('isactive', 1)->orderBy('create_date', 'desc')->with('images')->paginate(12);
    //     $brand = Brand::where('isactive', 1)->get();
    //     $category = Category::where('isactive', 1)->get();
    //     return view('users.pages.shop', compact('category', 'products', 'brand'));
    // }
    public function shop(Request $request)
    {
        
        $sort = $request->get('sort'); 
        $brandIds = (array) $request->get('brand', []); 
        $categoryIds = (array) $request->get('category', []); 

        
        $query = Product::where('isactive', 1)
            ->with(['images', 'units.unit', 'brand', 'category']);

        
        if (!empty($brandIds)) {
            $query->whereIn('brand_id', $brandIds);
        }

        if (!empty($categoryIds)) {
            $query->whereIn('category_id', $categoryIds);
        }

        // Nếu sort = hot => lấy top ids rồi whereIn + FIELD để giữ thứ tự
        if ($sort === 'hot') {
            $topProductIds = OrderDetail::selectRaw('product_id, SUM(quantity) as total_sold')
                ->groupBy('product_id')
                ->orderByDesc('total_sold')
                ->take(100) // chọn nhiều hơn 10 để filter an toàn; bạn có thể để 10
                ->pluck('product_id');

            if ($topProductIds->isNotEmpty()) {
                // chỉ lấy các product nằm trong top list, giữ thứ tự FIELD
                $query->whereIn('id', $topProductIds)
                    ->orderByRaw('FIELD(id, ' . $topProductIds->implode(',') . ')');
            } else {
                // fallback nếu không có dữ liệu bán
                $query->orderBy('create_date', 'desc');
            }
        } elseif ($sort === 'new') {
            $query->orderBy('create_date', 'desc');
        } else {
            // default (all) - bạn có thể đổi theo ý
            $query->orderBy('create_date', 'desc');
        }

        // paginate (12 per page) - keep query string when rendering links
        $products = $query->paginate(12)->appends($request->query());

        
        $brand = Brand::where('isactive', 1)->get();
        $category = Category::where('isactive', 1)->where('position', 2)->get();

        // Dùng topProductIds để hiển thị list_product_hot (ví dụ top 10)
        $topProductIdsShort = OrderDetail::selectRaw('product_id, SUM(quantity) as total_sold')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(10)
            ->pluck('product_id');

        $list_product_hot = Product::with('images', 'units.unit')
            ->whereIn('id', $topProductIdsShort)
            ->orderByRaw('FIELD(id, ' . $topProductIdsShort->implode(',') . ')')
            ->get();

        $list_product_new = Product::where('isactive', 1)
            ->with('images', 'units.unit')
            ->orderBy('create_date', 'desc')
            ->take(10)
            ->get();

        return view('users.pages.shop', compact(
            'products',
            'brand',
            'category',
            'list_product_hot',
            'list_product_new'
        ));
    }
    public function product(Request $request)
    {
        $product = Product::where('id', $request->product_id)
            ->where('isactive', 1)
            ->with([
                'images' => function ($query) {
                    $query->where('isactive', 1)->orderBy('position', 'asc');
                },
                'category',
                'brand',
                'units.unit'
            ])
            ->first();

        if (!$product) {
            return redirect()->route('shop')->with('error', 'Sản phẩm không tồn tại hoặc đã bị vô hiệu hóa.');
        }

        // Lấy tổng tồn kho theo đơn vị cơ sở (viên)
        $totalStock = $product->totalStockBaseUnit();

        // Tính tồn kho theo từng đơn vị (hộp, vỉ, viên)
        $stockByUnits = [];
        foreach ($product->units as $productUnit) {
            $stockInfo = $product->stockByProductUnit($productUnit->id);
            $stockByUnits[$productUnit->id] = $stockInfo;
        }

        return view('users.pages.product', compact('product', 'totalStock', 'stockByUnits'));
    }

    public function about()
    {
        return view('users.pages.about');
    }

    public function blog(Request $request)
    {
        $query = News::where('isactive', 1)
            ->with('category')
            ->orderBy('create_date', 'desc');

        // 🔹 Filter theo thể loại bài viết
        if ($request->filled('category')) {
            $categoryIds = (array) $request->category;

            $query->whereIn('category_id', $categoryIds);
        }

        $blogs = $query->paginate(5)->appends($request->query());

        // Sản phẩm mới
        $productnew = Product::where('isactive', 1)
            ->orderBy('create_date', 'desc')
            ->with('images')
            ->take(6)
            ->get();

        // Danh mục bài viết
        $category = News_category::where('isactive', 1)->get();

        return view('users.pages.blog', compact(
            'blogs',
            'productnew',
            'category'
        ));
    }

    public function blogdetails(Request $request)
    {
        $topProductIds = OrderDetail::selectRaw('product_id, SUM(quantity) as total_sold')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(10)
            ->pluck('product_id');
        //sản phẩm hot
        $producthot = Product::with('images')
            ->whereIn('id', $topProductIds)
            ->with([
                'units.unit'
            ])
            ->get();

        $blog = News::with('category')->findOrFail($request->id);
        return view('users.pages.blog_detail', compact('blog', 'producthot'));
    }
    
    public function contact()
    {
        return view('users.pages.contact');
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
