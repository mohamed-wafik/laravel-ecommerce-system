<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    private CloudinaryService $cloudinaryService;

    public function __construct(CloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
    }
    
    public function index(Request $request)
    {
        $filters = $request->query();

        $query = Product::query();

        $pageSize = isset($filters["pageSize"]) ? (int)$filters["pageSize"] : 10;
        $orderBy = $filters["orderBy"] ?? "desc";
        $search = $filters["search"] ?? null;

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where("title", "like", "%" . $search . "%")
                ->orWhere("description", "like", "%" . $search . "%");
            });
        }

        $products = $query->orderBy("created_at", $orderBy)->paginate($pageSize);
        $categories = Category::all();

        return view("dashboard.product.index", compact("products", "categories","filters"));
    }


    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $result = $this->cloudinaryService->uploadFile($request->file('image'));
            $data['image'] = $result['secure_url'] ?? ($result['url'] ?? null);
            $data['image_public_id'] = $result['public_id'] ?? null;
        }

        Product::create($data);

        return redirect()->back()
            ->with('success', 'Product created successfully!');
    }

    public function show($id)
    {
        $product = Product::with(['category'])
            ->withCount(['orders',])
            ->findOrFail($id);
            
        return view('dashboard.product.show', compact('product' ));
    }

    public function update(StoreProductRequest $request, $id)  
    {
        $product = Product::find($id);

        if (!$product) {
            return redirect()
                ->back()
                ->with('error', 'Product not found!');
        }

        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($product->image_public_id) {
                $check = $this->cloudinaryService->deleteFile($product->image_public_id);
                if (!$check) {
                    return redirect()->back()
                        ->with('error', 'Failed to delete existing image from Cloudinary!');
                }
            }

            $result = $this->cloudinaryService->uploadFile($request->file('image'));
            $data['image'] = $result['secure_url'] ?? ($result['url'] ?? null);
            $data['image_public_id'] = $result['public_id'] ?? null;
        } 

        $product->update($data);

        return redirect()->back()
            ->with('success', 'Product updated successfully!');
    }

    public function destroy($id)  
    {
        $product = Product::find($id);

        if (!$product) {
            return redirect()->back()
                ->with('error', 'Product not found!');
        }

        if ($product->image_public_id) {
            $check = $this->cloudinaryService->deleteFile($product->image_public_id);
            if (!$check) {
                return redirect()->back()
                    ->with('error', 'Failed to delete image from Cloudinary!');
            }
        }

        $product->delete();

        return redirect()->back()
            ->with('success', 'Product deleted successfully!');
    }
}