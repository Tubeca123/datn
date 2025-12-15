<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use App\Models\News_category;


use App\Models\Category;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $main_category = Category::where('isactive', 1)->get();
        View::share('main_category', $main_category);
        $main_new_category = News_category::where('isactive', 1)->take(10)->get();
        View::share('main_new_category', $main_new_category);
        
        Paginator::useBootstrap();
    }
}
