<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\api\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends BaseController
{
    public function index(Request $request)
    {
        $filters = $request->query();

        $query = Product::with('category')
            ->withCount('reviews')
            ->withAvg('reviews', 'rating');

        if (!empty($filters['category'])) {
            $query->where('category_id', $filters['category']);
        }

        if (!empty($filters['only_discounted'])) {
            $query->where('discount', '>', 0);
        }

        if (!empty($filters['min_rating'])) {
            $query->having('reviews_avg_rating', '>=', (float)$filters['min_rating']);
        }

        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }
        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['search'] . '%')
                ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }

        $products = $query->paginate(10)->appends($filters);

        return $this->sendResponse($products, 'Products retrieved successfully');

    }


    public function getTopDeal() {
        $topProducts = Product::withCount(['orders', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->orderBy('orders_count', 'desc')
            ->take(10)
            ->get();

        return $this->sendResponse($topProducts, 'Top deal products retrieved successfully');
    }
    public function show($id)
    {
        $product = Product::with([
            'category',
            'reviews' => function ($query) {
                $query->where('rating', '>=', 4)
                    ->limit(4);
            },
            'reviews.user',
        ])
        ->withCount('reviews')
        ->withAvg('reviews', 'rating')
        ->findOrFail($id);

        return $this->sendResponse(ProductResource::make($product), 'Product details retrieved successfully');
    }
}