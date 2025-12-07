<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('id', 'DESC')->get();
        return view('admin.pages.Category.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.pages.Category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2|max:255',
        ]);

        Category::create([
            'name'        => $request->name,
            'about'       => $request->about,
            'position'    => $request->position,
            'status'      => $request->status ?? 1,
            'create_date' => Carbon::now(),
            'create_by'   => Auth::user()->id,
            'isactive'    => 1,
        ]);

        return redirect()->route('list_category')->with('success', 'Thêm danh mục thành công!');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.pages.Category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|min:2|max:255',
        ]);

        $category = Category::findOrFail($id);

        $category->update([
            'name'        => $request->name,
            'about'       => $request->about,
            'position'    => $request->position,
            'status'      => $request->status,
            'update_date' => Carbon::now(),
            'update_by'   => Auth::user()->id ,
        ]);

        return redirect()->route('list_category')->with('success', 'Cập nhật danh mục thành công!');
    }

    public function toggle($id)
    {
        $cate = Category::findOrFail($id);

        // Đảo trạng thái 1 <-> 0
        $cate->isactive = $cate->isactive ? 0 : 1;
        $cate->update_date = now();
        $cate->update_by = Auth::user()->id ;
        $cate->save();

        return back()->with('success', 'Cập nhật trạng thái thành công!');
    }
}
