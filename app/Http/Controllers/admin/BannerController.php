<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class BannerController extends Controller
{
    
    public function index(Request $request)
    {
        $query = Banner::query();

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

        return view('admin.pages.Banner.index', compact('items'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.pages.Banner.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|min:2|max:255',
            'link' => 'required|min:2|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $fileName = time() . '_' . uniqid() . '.' . $ext;

            $uploadPath = public_path('uploads/banner');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            $file->move($uploadPath, $fileName);
            $imagePath = 'uploads/banner/' . $fileName;
        }
        $data = [
            'content' => $request->content,
            'link' => $request->link,
            'image' => $imagePath,
            'isactive' => 1,
            'create_date' => now(),
            'create_by' => Auth::user()->id,
        ];


        $create = new Banner();
        $create->create($data);
        $request->session()->put("messenge", ["style" => "success", "msg" => "Thêm mới banner thành công"]);
        return redirect()->route("list_banner");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $edit = Banner::findOrFail($id);
        return view("admin.pages.Banner.edit", compact("edit"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'content' => 'required|min:2|max:255',
            'link' => 'required|min:2|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $fileName = time() . '_' . uniqid() . '.' . $ext;

            $uploadPath = public_path('uploads/banner');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            $file->move($uploadPath, $fileName);
            $imagePath = 'uploads/banner/' . $fileName;
        }
        $data = [
            'content' => $request->content,
            'link' => $request->link,
            'image' => $imagePath,
            'update_date' => now(),
            'update_by' => Auth::user()->id,
        ];
        $edit = Banner::where('id', $id)->first();
        $edit->update($data);
        $request->session()->put("messenge", ["style" => "success", "msg" => "Cập nhật Banner thành công"]);
        return redirect()->route("list_banner");
    }

    public function destroy($id)
    {
        Banner::destroy($id);

        return back()->with('success', 'Xóa banner thành công!');
    }

    public function toggleActive($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->isactive = $banner->isactive ? 0 : 1;
        $banner->update_date = now();
        $banner->update_by = Auth::user()->id;
        $banner->save();

        return back()->with('success', 'Cập nhật trạng thái thành công!');
    }
}
