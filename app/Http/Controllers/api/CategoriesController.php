<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\api\BaseController;
use App\Models\Category;

class CategoriesController extends BaseController
{
    //
    public function index()
    {
        //
        $categories = Category::all();
        return $this->sendResponse($categories, 'Categories retrieved successfully');
    }
}