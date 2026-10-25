<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Services\Admin\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(protected CategoryService $categories) {}

    public function index(Request $request)
    {
        return $this->paginatedSuccess(
            $this->categories->paginate($request->integer("per_page",20), $request->only(['search', 'parent_id', 'is_active']))
        );
    }

    public function tree()
    {
        return $this->success($this->categories->tree());
    }


    public function store(StoreCategoryRequest $request)
    {
        return $this->created($this->categories->create($request->validated()), 'Category created');
    }

    public function update(UpdateCategoryRequest $request, string $id)
    {
        return $this->success($this->categories->update($id, $request->validated()), 'Category updated');
    }

    public function destroy(string $id)
    {
        $this->categories->delete($id);

        return $this->noContentSuccess('Category deleted');
    }

    public function reorder(Request $request)
    {
        $data = $request->validate(['ordered_ids' => ['required', 'array']]);
        $this->categories->reorder($data['ordered_ids']);

        return $this->success(null, 'Categories reordered');
    }

    public function toggleActive(string $id)
    {
        return $this->success($this->categories->toggleActive($id), 'Category status toggled');
    }
}