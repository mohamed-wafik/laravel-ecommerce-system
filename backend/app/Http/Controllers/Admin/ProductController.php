<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Services\Admin\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(protected ProductService $products) {}

    public function index(Request $request) 
    {
        return $this->paginatedSuccess(
            $this->products->paginate($request->integer("per_page",15), $request->only([
                'search', 'category_id', 'brand_id', 'is_active', 'min_price', 'max_price', 'trashed',
            ]))
        );
    }

    public function show(string $id)
    {
        return $this->success($this->products->find($id));
    }

    /**
     * One request creates the product row, uploads every image straight to
     * Cloudinary, and creates all variant rows — all in a single DB
     * transaction (see ProductService::create()). No separate "upload
     * media first" step and no separate "add variants" call.
     */
    public function store(StoreProductRequest $request)
    {
        return $this->created($this->products->create($request->validated()), 'Product created');
    }

    /**
     * Same one-request shape as store(): send new image files to replace
     * the gallery, and/or a fresh variants array to replace the variant
     * set — both optional, both handled in one call.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        return $this->success($this->products->update($id, $request->validated()), 'Product updated');
    }

    public function destroy(string $id)
    {
        $this->products->delete($id);

        return $this->noContentSuccess('Product deleted');
    }

    public function toggleActive(string $id)
    {
        return $this->success($this->products->toggleActive($id), 'Product status toggled');
    }

    public function adjustStock(Request $request, string $id)
    {
        $data = $request->validate(['delta' => ['required', 'integer']]);

        return $this->success($this->products->adjustStock($id, $data['delta']), 'Stock adjusted');
    }

    public function lowStock(Request $request)
    {
        return $this->paginatedSuccess($this->products->lowStock((int) $request->get('threshold', 5)));
    }
}