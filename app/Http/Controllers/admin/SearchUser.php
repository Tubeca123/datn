<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SearchUser extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('admin.pages.search.index', compact('users'));
    }
}
