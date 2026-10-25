<?php

namespace App\Services\Admin;

use App\Models\Brand;
use App\Services\CloudinaryService;
use App\Services\Concerns\ReplacesCloudinaryImage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class BrandService extends BaseCrudService
{
    use ReplacesCloudinaryImage;

    protected string $model = Brand::class;

    public function __construct(protected CloudinaryService $cloudinary) {}

    public function create(array $data): Brand
    {
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data = $this->replaceImage($data, 'logo', 'logo_url', 'logo_public_id', 'brands');

        return parent::create($data);
    }

    public function update(string $id, array $data): Brand
    {
        $brand = Brand::findOrFail($id);
        $data = $this->replaceImage($data, 'logo', 'logo_url', 'logo_public_id', 'brands', $brand->logo_public_id);

        return parent::update($id, $data);
    }

    public function delete(string $id): bool
    {
        $brand = Brand::findOrFail($id);
        $publicId = $brand->logo_public_id;

        $deleted = (bool) $brand->delete();

        if ($deleted && $publicId) {
            $this->cloudinary->destroy($publicId);
        }

        return $deleted;
    }

    /**
     * Uploads `logo` (a real file from the create/update brand request) to
     * Cloudinary and swaps it into `logo_url` / `logo_public_id`, deleting
     * the previous logo if one existed — implemented once in
     * ReplacesCloudinaryImage::replaceImage(), shared with Category/User/Profile.
     */
    public function toggleActive(string $id): Brand
    {
        $brand = Brand::findOrFail($id);
        $brand->update(['is_active' => ! $brand->is_active]);

        return $brand;
    }

    protected function applyFilters(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['search'] ?? null, fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->when(isset($filters['is_active']), fn ($q) => $q->where('is_active', (bool) $filters['is_active']));
    }

    protected function orderColumn(): string
    {
        return 'name';
    }
}
