<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('products')->paginate(10);
        return view('dashboard.category.index', compact('categories'));
    }

    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();

        if($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $data['image'] = $path;
        }

        Category::create($data);

        return redirect()->back()
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
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }
            $path = $request->file('image')->store('images', 'public');
            $validated['image'] = $path;
        }

        $category->update($validated);

        return redirect()->back()
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

        $category->delete();

        return redirect()->back()
            ->with('success', 'Category deleted successfully!');
    }
}
