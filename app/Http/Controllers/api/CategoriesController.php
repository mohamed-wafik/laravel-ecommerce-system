<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    //
    public function index()
    {
        //
        $categories = Category::all();
        return response()->json([
            'data' =>  $categories,
            'message' => 'Categories retrieved successfully',
            'status' => 200,
        ]);
    }
}
