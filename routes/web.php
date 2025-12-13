<?php

use App\Http\Controllers\admin\BannerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\RoleController;
use App\Http\Middleware\CheckLogin;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\admin\SearchUser;

use App\Http\Controllers\users\HomeController;


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [HomeController::class, 'shop'])->name('shop');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/blog', [HomeController::class, 'blog'])->name('blog');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/cart', [HomeController::class, 'cart'])->name('cart');
Route::get('/checkout', [HomeController::class, 'checkout'])->name('checkout');
Route::get('/compare', [HomeController::class, 'compare'])->name('compare');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/user-login', [HomeController::class, 'login'])->name('user_login');
Route::post('/handle-user-login', [HomeController::class, 'handleLogin'])->name('handle_user_login');
Route::post('/handle-user-register', [HomeController::class, 'handleRegister'])->name('handle_user_register');
Route::get('/user-register', [HomeController::class, 'register'])->name('user_register');
Route::get('/user-logout', [HomeController::class, 'logout'])->name('user_logout');

Route::get('/product', [HomeController::class, 'product'])->name('product');

// =======================================================================
Route::get('/register', [UserController::class, 'register'])->name('register');
Route::post('/register', [UserController::class, 'store'])->name('register.store');

Route::get('/login', [UserController::class, 'login'])->name('login');
Route::post('/login', [UserController::class, 'loginIn'])->name('login.in');

Route::get('/test', [RoleController::class, 'index'])->name('test');
Route::get('/test2', [UserController::class, 'index']);

Route::prefix('admin')->middleware(CheckLogin::class)->group(function () {
    Route::get('/trangchu_admin', [DashboardController::class, 'index'])->name('trang_chu');



    Route::get('/orders/create', [OrderController::class, 'create'])->name('order.create');
    Route::get('/api/product/{productId}/unit/{unitId}/inventories', [OrderController::class, 'getInventories'])->name('api.product.inventories');
    Route::post('/orders/store', [OrderController::class, 'store'])->name('order.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('admin.orders.show');
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('admin.orders.cancel');
    Route::get('/api/products/search', [OrderController::class, 'searchProduct'])->name('admin.api.products.search');
    Route::get('/api/product/{id}/units', [OrderController::class, 'getUnits'])->name('admin.api.product.units');
    Route::get('/api/customer', [OrderController::class, 'findByPhone']);
    Route::get('/edit_order/{id}', [OrderController::class, 'edit'])->name('edit_order');
    Route::post('/edit_order/{id}/update', [OrderController::class, 'update'])->name('edit_order.update');
    Route::post('/edit_order/{id}/detail/{detailId}/delete', [OrderController::class, 'deleteDetail'])->name('orders.detail.delete');
    Route::post('/edit_orders/{id}/detail/{detailId}/update', [OrderController::class, 'updateDetail'])->name('orders.detail.update');


    Route::get('/list_product', [ProductController::class, 'index'])->name('list_product');
    Route::get('/edit_product/{id}', [ProductController::class, 'edit'])->name('edit_product');
    Route::post('/update_product/{id}', [ProductController::class, 'update'])->name('update_product');
    Route::get('/toggle_product/{id}', [ProductController::class, 'toggle'])->name('toggle_product');
    Route::get('/api/product/{productId}/inventories', [ProductController::class, 'getProductInventories'])->name('api.product.inventories');

    Route::get('/list_category', [CategoryController::class, 'index'])->name('list_category');
    Route::get('/create_category', [CategoryController::class, 'create'])->name('create_category');
    Route::post('/store_category', [CategoryController::class, 'store'])->name('store_category');
    Route::get('/edit_category/{id}', [CategoryController::class, 'edit'])->name('edit_category');
    Route::post('/update_category/{id}', [CategoryController::class, 'update'])->name('update_category');
    Route::get('/delete_category/{id}', [CategoryController::class, 'delete'])->name('delete_category');
    Route::get('/toggle_category/{id}', [CategoryController::class, 'toggle'])->name('toggle_category');

    Route::get('/list_brand',               [BrandController::class, 'index'])->name('list_brand');
    Route::get('/create_brand',         [BrandController::class, 'create'])->name('create_brand');
    Route::post('/store_brand',         [BrandController::class, 'store'])->name('store_brand');
    Route::get('/edit_brand/{id}',      [BrandController::class, 'edit'])->name('edit_brand');
    Route::put('/update_brand/{id}',    [BrandController::class, 'update'])->name('update_brand');
    Route::get('/delete_brand/{id}',    [BrandController::class, 'destroy'])->name('delete_brand');
    Route::get('/toggle_brand/{id}',    [BrandController::class, 'toggleActive'])->name('toggle_brand');


    Route::get('/list_user', [SearchUser::class, 'index'])->name('list_user');
    Route::get('/api/user/{userId}/detail', [SearchUser::class, 'getDetail'])->name('api.user.detail');
    Route::get('/create_user', [SearchUser::class, 'create'])->name('create_user');
    Route::post('/store_user', [SearchUser::class, 'store'])->name('store_user');
    Route::post('/api/user/create', [SearchUser::class, 'createUser'])->name('api.user.create');
    Route::get('/oder_user/{id}', [SearchUser::class, 'show'])->name('orders_user_show');
    Route::get('/api/user/{userId}/orders', [SearchUser::class, 'getDetail'])->name('api.user.orders');
    Route::post('/api/user/{userId}/toggle-status', [SearchUser::class, 'toggleStatus']);

    Route::get('/list_banner', [BannerController::class, 'index'])->name('list_banner');
    Route::get('/create_banner', [BannerController::class, 'create'])->name('create_banner');
    Route::post('/store_banner', [BannerController::class, 'store'])->name('store_banner');
    Route::get('/edit_banner/{id}', [BannerController::class, 'edit'])->name('edit_banner');
    Route::post('/update_banner/{id}', [BannerController::class, 'update'])->name('update_banner');
    Route::get('/delete_banner/{id}', [BannerController::class, 'destroy'])->name('delete_banner');
    Route::get('/toggle_banner/{id}', [BannerController::class, 'toggleActive'])->name('toggle_banner');

    Route::post('/inventory/import', [ProductController::class, 'importExcel']);
});
