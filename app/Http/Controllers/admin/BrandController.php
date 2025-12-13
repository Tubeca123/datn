<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Support\Facades\Auth;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::orderBy('id', 'desc')->get();
        return view('admin.pages.Brand.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.pages.Brand.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        Brand::create([
            'name' => $request->name,
            'description' => $request->description,
            'isactive' => 1,
            'create_date' => now(),
            'create_by' => Auth::user()->id,
        ]);

        return redirect()->route('list_brand')->with('success', 'Thêm thương hiệu thành công!');
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.pages.Brand.edit', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $brand = Brand::findOrFail($id);
        $brand->update([
            'name' => $request->name,
            'description' => $request->description,
            'update_date' => now(),
            'update_by' => Auth::user()->id,
        ]);

        return redirect()->route('list_brand')->with('success', 'Cập nhật thương hiệu thành công!');
    }

    public function destroy($id)
    {
        Brand::destroy($id);

        return back()->with('success', 'Xóa thương hiệu thành công!');
    }

    public function toggleActive($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->isactive = $brand->isactive ? 0 : 1;
        $brand->update_date = now();
        $brand->update_by = Auth::user()->id;
        $brand->save();

        return back()->with('success', 'Cập nhật trạng thái thành công!');
    }
}
