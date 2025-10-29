<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
// use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //
public function index(Request $request)
{
    $filters = $request->query();

    $query = Product::with("category"); // ✅ eager-load category first

    if (isset($filters['category'])) {
        $query->where('category_id', $filters['category']);
    }

    if (isset($filters['min_price'])) {
        $query->where('price', '>=', $filters['min_price']);
    }

    if (isset($filters['max_price'])) {
        $query->where('price', '<=', $filters['max_price']);
    }

    if (isset($filters['search'])) {
        $query->where(function ($q) use ($filters) {
            $q->where('name', 'like', '%' . $filters['search'] . '%')
              ->orWhere('description', 'like', '%' . $filters['search'] . '%');
        });
    }

    $products = $query->paginate(10);

    // You can wrap each product in a resource easily using `ProductResource::collection`
    return response()->json([
        'data' => $products,
        'message' => 'Products retrieved successfully',
        'status' => 200,
    ]);
}
    public function show($id)
    {
        $product = Product::with("category")->findOrFail($id);
        if (!$product) {
            return response()->json([
                'data' => null,
                'message' => 'Product not found',
                'status' => 404,
            ], 404);
        }
        return response()->json([
            'data' =>  $product,
            'message' => 'Product retrieved successfully',
            'status' => 200,
        ]);
    }
}
