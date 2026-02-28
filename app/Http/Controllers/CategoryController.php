<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private CloudinaryService $cloudinaryService;
    
    public function __construct(CloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
    }
    public function index()
    {
        $categories = Category::with('products')->paginate(10);
        return view('dashboard.category.index', compact('categories'));
    }
    public function create()
    {
        return view('dashboard.category.create');
    }
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();

        if($request->hasFile('image')) {
            $result = $this->cloudinaryService->uploadFile($request->file('image'));
            $data['image'] = $result['secure_url'] ?? ($result['url'] ?? null);
            $data['image_public_id'] = $result['public_id'] ?? null;
        }

        Category::create($data);

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully!');
    }

    public function show($id)
    {
        $category = Category::with('products')->find($id);

        if (!$category) {
            return redirect()
                ->back()
                ->with('error', 'Category not found!');
        }

        return view('dashboard.category.show', compact('category'));
    }

    public function edit($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return redirect()
                ->back()
                ->with('error', 'Category not found!');
        }

        return view('dashboard.category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return redirect()->back()
                ->with('error', 'Category not found!');
        }

        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($category->image_public_id) {

                $check =$this->cloudinaryService->deleteFile($category->image_public_id);

                if (!$check) {
                    return redirect()->back()
                        ->with('error', 'Failed to delete existing image from Cloudinary!');
                }
            }

            $result = $this->cloudinaryService->uploadFile($request->file('image'));
            $validated['image'] = $result['secure_url'] ?? ($result['secure_url'] ?? null);
            $validated['image_public_id'] = $result['public_id'] ?? null;
        }

        $category->update($validated);

        return redirect()
                ->route('categories.index')
                ->with('success', 'Category updated successfully!');
    }


    public function destroy($id)
    {
        $category = Category::with('products')->find($id);

        if (!$category) {
            return redirect()->back()
                ->with('error', 'Category not found!');
        }

        if ($category->products()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete category with products!');
        }

        if ($category->image_public_id) {
            $check = $this->cloudinaryService->deleteFile($category->image_public_id);

            if (!$check) {
                return redirect()->back()
                    ->with('error', 'Failed to delete image from Cloudinary!');
            }
        }
        
        $category->delete();

        return redirect()->back()
            ->with('success', 'Category deleted successfully!');
    }
}