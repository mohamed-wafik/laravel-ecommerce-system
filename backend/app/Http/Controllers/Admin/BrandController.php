<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBrandRequest;
use App\Http\Requests\Admin\UpdateBrandRequest;
use App\Services\Admin\BrandService;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function __construct(protected BrandService $brands) {}

    public function index(Request $request)
    {
        return $this->paginatedSuccess(
            $this->brands->paginate($request->integer("per_page",20), $request->only(['search', 'is_active']))
        );
    }
    
    public function store(StoreBrandRequest $request)
    {
        return $this->created($this->brands->create($request->validated()), 'Brand created');
    }

    public function update(UpdateBrandRequest $request, string $id)
    {
        return $this->success($this->brands->update($id, $request->validated()), 'Brand updated');
    }

    public function destroy(string $id)
    {
        $this->brands->delete($id);

        return $this->noContentSuccess('Brand deleted');
    }

    public function toggleActive(string $id)
    {
        return $this->success($this->brands->toggleActive($id), 'Brand status toggled');
    }
}