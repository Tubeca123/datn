<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\News;

class HomeController extends Controller
{
    public function index()
    {
        $list_category = Category::where('position', 1)->get();
        foreach ($list_category as $category) {
            $products = $category->products()->where('isactive', 1)->with('images')->take(3)->get();
            $count = $category->products()->where('isactive', 1)->count();
            $images = [];
            foreach ($products as $p) {
                $images[] = $p->images[0]->src ?? 'default.jpg';
            }
            $category->products_image = $images;
            $category->count = $count;
        }
        $list_product_sold_count = Product::where('isactive', 1)->orderBy('sold_count', 'desc')->take(10)->with('images')->get();
        $list_product_new = Product::where('isactive', 1)->orderBy('create_date', 'desc')->take(10)->with('images')->get();
        // dd($list_product_new[1]->images[0]->src);
        $news = News::where('isactive', 1)->orderBy('create_date', 'desc')->with('news_categories')->take(10)->get();
        return view('users.pages.index', compact('list_category', 'list_product_sold_count', 'list_product_new', 'news'));
    }
}
