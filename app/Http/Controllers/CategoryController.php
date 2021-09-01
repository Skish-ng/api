<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class categoryController extends Controller
{
    //
    function index()
    {
        return response( Category::all(), 201);
    }
    function store()
    {
        // $request->validate([
        //     'name' => 'string|required|unique:categories,name'
        // ]);

        // $Category = Category::create([
        //     'name' => $request->name,
        //     'desc' => "created as others by user".auth()->user()->username
        // ]);
    }
}
