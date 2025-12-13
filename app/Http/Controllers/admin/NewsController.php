<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\News_category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
class NewsController extends Controller
{
    /**
     * Danh sách bài viết
     */
    public function index(Request $request)
    {
        $query = News::with(['category', 'creator']);

        // Filter theo category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter theo trạng thái
        if ($request->filled('isactive')) {
            $query->where('isactive', $request->isactive);
        }

       

        $news = $query->orderBy('create_date', 'desc')
            ->paginate(10)
            ->appends($request->all());

        $categories = News_category::where('isactive', 1)->get();

        return view('admin.pages.new.index', compact('news', 'categories'));
    }

    /**
     * Form tạo bài viết
     */
    public function create()
    {
        $categories = News_category::where('isactive', 1)->get();
        return view('admin.pages.new.create', compact('categories'));
    }

    /**
     * Lưu bài viết mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'category_id' => 'required|exists:news_categories,id',
            'description' => 'nullable|max:500',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'title.required' => 'Tiêu đề bài viết là bắt buộc',
            'category_id.required' => 'Vui lòng chọn thể loại',
            'content.required' => 'Nội dung bài viết là bắt buộc',
            'image.image' => 'File phải là ảnh',
            'image.max' => 'Ảnh không được vượt quá 2MB'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $fileName = time() . '_' . uniqid() . '.' . $ext;
            
            $uploadPath = public_path('uploads/news');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }
            
            $file->move($uploadPath, $fileName);
            $imagePath = 'uploads/news/' . $fileName;
        }

        News::create([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'content' => $request->content,
            'image' => $imagePath,
            'create_date' => now(),
            'create_by' => Auth::user()->id,
            'isactive' => 1
        ]);

        return redirect()->route('list_news')
            ->with('success', 'Tạo bài viết thành công!');
    }

    /**
     * Xem chi tiết bài viết
     */
    public function show($id)
    {
        $news = News::with(['category', 'creator', 'updater'])->findOrFail($id);
        return view('admin.pages.news.show', compact('news'));
    }

    /**
     * Form sửa bài viết
     */
    public function edit($id)
    {
        $news = News::findOrFail($id);
        $categories = News_category::where('isactive', 1)->get();
        return view('admin.pages.new.edit', compact('news', 'categories'));
    }

    /**
     * Cập nhật bài viết
     */
    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $request->validate([
            'title' => 'required|max:255',
            'category_id' => 'required|exists:news_categories,id',
            'description' => 'nullable|max:500',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $imagePath = $news->image; // Giữ nguyên ảnh cũ nếu không upload mới
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($news->image && File::exists(public_path($news->image))) {
                File::delete(public_path($news->image));
            }
            
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $fileName = time() . '_' . uniqid() . '.' . $ext;
            
            $uploadPath = public_path('uploads/news');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }
            
            $file->move($uploadPath, $fileName);
            $imagePath = 'uploads/news/' . $fileName;
        }

        $news->update([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'content' => $request->content,
            'image' => $imagePath,
            'update_date' => now(),
            'update_by' => Auth::user()->id
        ]);

        return redirect()->route('list_news')
            ->with('success', 'Cập nhật bài viết thành công!');
    }

    /**
     */
    public function destroy($id)
    {
        $news = News::findOrFail($id);
        
        $news->delete();

        return redirect()->route('list_news')
            ->with('success', 'Xóa bài viết thành công!');
    }

    /**
     * Toggle trạng thái
     */
    public function toggle($id)
    {
        $news = News::findOrFail($id);
        
        $news->update([
            'isactive' => $news->isactive == 1 ? 0 : 1,
            'update_date' => now(),
            'update_by' => Auth::user()->id
        ]);

        $status = $news->isactive == 1 ? 'kích hoạt' : 'vô hiệu hóa';
        return redirect()->route('list_news')
            ->with('success', "Đã {$status} bài viết!");
    }
}