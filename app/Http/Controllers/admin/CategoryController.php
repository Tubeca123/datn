<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();
        
        
        if ($request->has('position') && $request->position != '') {
            $query->where('position', $request->position);
        }
        
        
        if ($request->has('isactive') && $request->isactive != '') {
            $query->where('isactive', $request->isactive);
        }
        
        $categories = $query->orderBy('id', 'DESC')->get();
        
        // Load parent category name cho các category con
        foreach ($categories as $category) {
            if ($category->position == 2 && $category->parent_id) {
                $category->parent_name = Category::find($category->parent_id)->name ?? 'N/A';
            }
        }
        
        return view('admin.pages.Category.index', compact('categories'));
    }

    public function create()
    {
        $parentCategories = Category::where('position', 1)->where('isactive', 1)->orderBy('name')->get();
        return view('admin.pages.Category.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2|max:255',
            'position' => 'required|in:1,2',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->position == 2) {
            $request->validate([
                'parent_id' => 'required|exists:categories,id',
            ]);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $fileName = time() . '_' . uniqid() . '.' . $ext;
            
            $uploadPath = public_path('uploads/categories');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }
            
            $file->move($uploadPath, $fileName);
            $imagePath = 'uploads/categories/' . $fileName;
        }

        Category::create([
            'name'        => $request->name,
            'about'       => $request->about,
            'position'    => $request->position,
            'parent_id'   => $request->position == 2 ? $request->parent_id : null,
            'image'       => $imagePath,
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
        $parentCategories = Category::where('position', 1)
            ->where('isactive', 1)
            ->where('id', '!=', $id) // Không cho phép chọn chính nó nếu là category cha
            ->orderBy('name')
            ->get();
        return view('admin.pages.Category.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|min:2|max:255',
            'position' => 'required|in:1,2',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->position == 2) {
            $request->validate([
                'parent_id' => 'required|exists:categories,id',
            ]);
        }

        $category = Category::findOrFail($id);

        // Xử lý upload image mới
        $imagePath = $category->image; // Giữ nguyên ảnh cũ nếu không upload mới
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($category->image && File::exists(public_path($category->image))) {
                File::delete(public_path($category->image));
            }
            
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $fileName = time() . '_' . uniqid() . '.' . $ext;
            
            $uploadPath = public_path('uploads/categories');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }
            
            $file->move($uploadPath, $fileName);
            $imagePath = 'uploads/categories/' . $fileName;
        }

        $category->update([
            'name'        => $request->name,
            'about'       => $request->about,
            'position'    => $request->position,
            'parent_id'   => $request->position == 2 ? $request->parent_id : null,
            'image'       => $imagePath,
            'status'      => $request->status ?? 1,
            'update_date' => Carbon::now(),
            'update_by'   => Auth::user()->id,
        ]);

        return redirect()->route('list_category')->with('success', 'Cập nhật danh mục thành công!');
    }

    public function toggle($id)
    {
        $cate = Category::findOrFail($id);

        $cate->isactive = $cate->isactive ? 0 : 1;
        $cate->update_date = now();
        $cate->update_by = Auth::user()->id ;
        $cate->save();

        return back()->with('success', 'Cập nhật trạng thái thành công!');
    }
}
