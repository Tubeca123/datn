<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Banner::orderBy("id", "desc")->get();
        return view("admin.pages.Banner.index", compact("items"));
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
        $data = [
            'content' => $request->content,
            'link' => $request->link,
            'image' => $request->image,
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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
}
