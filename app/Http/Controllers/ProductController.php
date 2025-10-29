<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;


class ProductController extends Controller
{
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
            $path = $request->file('image')->store('images', 'public');
            $data['image'] = $path;
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

        // Get recent orders with this product
        $recentOrders = $product->orders()
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.product.show', compact('product', 'recentOrders' ));
    }

    // public function duplicate($id)
    // {
    //     $product = Product::findOrFail($id);
        
    //     $newProduct = $product->replicate();
    //     $newProduct->title = $product->title . ' (Copy)';
    //     $newProduct->sku = $product->sku . '-COPY';
    //     $newProduct->status = 'draft';
    //     $newProduct->save();

    //     return redirect()->route('products.edit', $newProduct->id)
    //         ->with('success', 'Product duplicated successfully');
    // }

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
            
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            $path = $request->file('image')->store('images', 'public');
            $data['image'] = $path;
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

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->back()
            ->with('success', 'Product deleted successfully!');
    }
}
