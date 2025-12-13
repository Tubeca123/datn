<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\News_category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class NewCategoryController extends Controller
{
    public function index(Request $request)
    {


        $query = News_category::query();

        $status = $request->get('status', 'active');
        if ($status === 'active') {
            $query->where('isactive', 1);
        } elseif ($status === 'cancelled') {
            $query->where('isactive', 0);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('create_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('create_date', '<=', $request->date_to);
        }

        $items = $query->orderBy('create_date', 'desc')
            ->paginate(20)
            ->appends($request->all());

        return view('admin.pages.Newscategory.index', compact('items'));
    }



    

    /**
     * AJAX get one category
     */
    public function edit($id)
    {
        $cat = News_category::findOrFail($id);
        return response()->json($cat);
    }

    /**
     * AJAX update
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:news_categories,name,' . $id,
            'description' => 'nullable|max:500',
        ], [
            'name.required' => 'Tên thể loại là bắt buộc',
            'name.unique' => 'Tên thể loại đã tồn tại',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }
        $cat = News_category::findOrFail($id);
        $cat->name = $request->name;
        $cat->description = $request->description;
        $cat->update_date = now();
        $cat->update_by = Auth::id();
        $cat->save();
        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thành công!',
            'category' => [
                'id' => $cat->id,
                'name' => $cat->name,
                'description' => $cat->description
            ]
        ]);
    }

    public function destroy($id)
    {
        News_category::destroy($id);

        return back()->with('success', 'Xóa bài viết thành công!');
    }

    public function toggle($id)
    {

        $news = News_category::findOrFail($id);

        $news->update([
            'isactive' => $news->isactive == 1 ? 0 : 1,
            'update_date' => now(),
            'update_by' => Auth::user()->id
        ]);

        $status = $news->isactive == 1 ? 'kích hoạt' : 'vô hiệu hóa';
        return redirect()->route('list_news_category')
            ->with('success', "Đã {$status} bài viết!");
    }

    /**
     * Lưu thể loại mới
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255|unique:news_categories,name',
                'description' => 'nullable|max:500',
            ], [
                'name.required' => 'Tên thể loại là bắt buộc',
                'name.unique' => 'Tên thể loại đã tồn tại',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }

            $category = News_category::create([
                'name' => $request->name,
                'description' => $request->description,
                'create_date' => now(),
                'create_by' => Auth::id(),
                'isactive' => 1
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tạo thể loại thành công!',
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'description' => $category->description
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Category Store Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
