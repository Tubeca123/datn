<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\RoleController;
use App\Http\Middleware\CheckLogin;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\OrderController;

Route::get('/', function () {
    return view('admin/master_layout');
});

Route::get('/register', [UserController::class, 'register'])->name('register');
Route::post('/register', [UserController::class, 'store'])->name('register.store');

Route::get('/login', [UserController::class, 'login'])->name('login');
Route::post('/login', [UserController::class, 'loginIn'])->name('login.in');

Route::get('/test', [RoleController::class, 'index'])->name('test');
Route::get('/test2', [UserController::class, 'index']);

Route::prefix('admin')->middleware(CheckLogin::class)->group(function () {
    Route::get('/trangchu_admin', [DashboardController::class, 'index'])->name('trang_chu');

    
    
    Route::get('/orders/create', [OrderController::class, 'create'])->name('order.create');

    // API tìm kiếm thuốc
    Route::get('/api/products/search', [OrderController::class, 'searchProduct'])->name('api.products.search');

    // API lấy đơn vị – giá – tồn kho theo sản phẩm
    Route::get('/api/product/{id}/units', [OrderController::class, 'getUnits'])->name('api.product.units');

    // API tạo đơn
    Route::post('/orders/store', [OrderController::class, 'store'])->name('order.store');


    Route::get('/list_product', [ProductController::class, 'index'])->name('list_product');
    Route::get('/edit_product/{id}', [ProductController::class, 'edit'])->name('edit_product');
    Route::post('/update_product/{id}', [ProductController::class, 'update'])->name('update_product');

    Route::get('/list_category', [CategoryController::class, 'index'])->name('list_category');
    Route::get('/create_category', [CategoryController::class, 'create'])->name('create_category');
    Route::post('/store_category', [CategoryController::class, 'store'])->name('store_category');
    Route::get('/edit_category/{id}', [CategoryController::class, 'edit'])->name('edit_category');
    Route::post('/update_category/{id}', [CategoryController::class, 'update'])->name('update_category');
    Route::get('/delete_category/{id}', [CategoryController::class, 'delete'])->name('delete_category');
    Route::get('/toggle_category/{id}', [CategoryController::class, 'toggle'])
        ->name('toggle_category');

    Route::get('/list_brand',               [BrandController::class, 'index'])->name('list_brand');
    Route::get('/create_brand',         [BrandController::class, 'create'])->name('create_brand');
    Route::post('/store_brand',         [BrandController::class, 'store'])->name('store_brand');
    Route::get('/edit_brand/{id}',      [BrandController::class, 'edit'])->name('edit_brand');
    Route::put('/update_brand/{id}',    [BrandController::class, 'update'])->name('update_brand');
    Route::get('/delete_brand/{id}',    [BrandController::class, 'destroy'])->name('delete_brand');
    Route::get('/toggle_brand/{id}',    [BrandController::class, 'toggleActive'])->name('toggle_brand');

    Route::post('/inventory/import', [ProductController::class, 'importExcel']);
});
