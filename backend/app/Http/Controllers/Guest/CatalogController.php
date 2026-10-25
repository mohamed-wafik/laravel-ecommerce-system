<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Services\Guest\CatalogService;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function __construct(protected CatalogService $catalog) {}

    public function categories()
    {
        return $this->success($this->catalog->categoryTree());
    }

    public function parentCategory()  {
        return $this->success($this->catalog->parentCategory());
    }
    public function brands()
    {
        return $this->success($this->catalog->brands());
    }

    public function products(Request $request)
    {
        $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'category_id' => ['nullable', 'uuid'],
            'brand_id' => ['nullable', 'uuid'],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'min:0'],
            'only_discounted' => ['nullable', 'boolean'],
            'min_rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'sort' => [
                'nullable',
                'in:newest,price_asc,price_desc,name_asc,best_selling',
            ],
        ]);

        return $this->paginatedSuccess(
            $this->catalog->products($request->only([
                'search',
                'category_id',
                'brand_id',
                'min_price',
                'max_price',
                'only_discounted',
                'min_rating',
                'sort',
            ]))
        );
    }

    public function topDeals() {
        return $this->success($this->catalog->topDealProducts());
    }

    public function product(string $slug)
    {
        $product = $this->catalog->product($slug);

        return $this->success([
            'product' => $product,
            'related' => $this->catalog->relatedProducts($product),
        ]);
    }

    public function reviews(Request $request, string $productId)
    {
        return $this->paginatedSuccess($this->catalog->reviews($productId));
    }

    public function search(Request $request)
    {
        $data = $request->validate(['q' => ['required', 'string', 'min:2', 'max:100']]);

        return $this->success($this->catalog->searchSuggestions($data['q']));
    }
}