<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\ProductExcelImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Product;
use App\Models\ImageProduct;
use Illuminate\Support\Facades\File;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductUnit;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with([
            'images' => fn($q) => $q->orderBy('position'),
            'inventory',
            'brand',
            'category'
        ])->get();

        return view('admin.pages.Product.index', compact('products'));
    }

    public function edit($id)
    {
        $product = Product::with(['images', 'units'])->findOrFail($id);

        $categories = Category::orderBy('name')->get();
        $brands     = Brand::orderBy('name')->get();

        // Lấy giá theo từng unit
        $price_box = $product->units->where('unit_id', 1)->first()->price_sale ?? 0;
        $price_pack = $product->units->where('unit_id', 2)->first()->price_sale ?? 0;
        $price_pill = $product->units->where('unit_id', 3)->first()->price_sale ?? 0;

        return view('admin.pages.Product.edit', compact(
            'product',
            'categories',
            'brands',
            'price_box',
            'price_pack',
            'price_pill'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:4096',
            'price_box' => 'nullable|numeric|min:0',
            'price_pack' => 'nullable|numeric|min:0',
            'price_pill' => 'nullable|numeric|min:0',
        ]);

        $product = Product::findOrFail($id);

        // Update basic fields
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'brand_id' => $request->brand_id,
            'category_id' => $request->category_id,
            'manufacturer' => $request->manufacturer,
            'country' => $request->country,
            'update_date' => now(),
        ]);

        // Update or create product_unit prices (unit ids: hộp=1, vỉ=2, viên=3)
        // Nếu bạn dùng dynamic unit ids, truyền từ form thay vì cố định 1/2/3
        $boxUnitId = 1;
        $packUnitId = 2;
        $pillUnitId = 3;

        if ($request->filled('price_box')) {
            ProductUnit::updateOrCreate(
                ['product_id' => $product->id, 'unit_id' => $boxUnitId],
                ['price_sale' => $request->price_box]
            );
        }

        if ($request->filled('price_pack')) {
            ProductUnit::updateOrCreate(
                ['product_id' => $product->id, 'unit_id' => $packUnitId],
                ['price_sale' => $request->price_pack]
            );
        }

        if ($request->filled('price_pill')) {
            ProductUnit::updateOrCreate(
                ['product_id' => $product->id, 'unit_id' => $pillUnitId],
                ['price_sale' => $request->price_pill]
            );
        }

        // Cách A: nếu upload ảnh mới -> xóa ảnh cũ (file + db) -> thêm ảnh mới
        if ($request->hasFile('images')) {

            // xóa file + record
            foreach ($product->images as $old) {
                $path = public_path($old->src);
                if (File::exists($path)) File::delete($path);
                $old->delete();
            }

            // upload mới
            $pos = 1;
            foreach ($request->file('images') as $file) {
                $ext = $file->getClientOriginalExtension();
                $fileName = time() . '_' . uniqid() . '.' . $ext;
                $file->move(public_path('uploads/products'), $fileName);

                ImageProduct::create([
                    'product_id' => $product->id,
                    'src' => 'uploads/products/' . $fileName,
                    'position' => $pos++,
                    'create_date' => now(),
                    'isactive' => 1,
                ]);
            }
        }

        return redirect()->route('product.index')->with('success', 'Cập nhật sản phẩm thành công.');
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new ProductExcelImport, $request->file('file'));

        return back()->with('success', 'Nhập thuốc thành công!');
    }
}
