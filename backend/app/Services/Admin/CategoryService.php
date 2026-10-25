<?php

namespace App\Services\Admin;

use App\Models\Category;
use App\Services\CloudinaryService;
use App\Services\Concerns\ReplacesCloudinaryImage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class CategoryService extends BaseCrudService
{
    use ReplacesCloudinaryImage;

    protected string $model = Category::class;

    protected array $with = ['parent'];

    public function __construct(protected CloudinaryService $cloudinary) {}

    public function create(array $data): Category
    {
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data = $this->replaceImage($data, 'image', 'image_url', 'image_public_id', 'categories');

        return parent::create($data);
    }

    public function update(string $id, array $data): Category
    {
        $category = Category::findOrFail($id);
        $data = $this->replaceImage($data, 'image', 'image_url', 'image_public_id', 'categories', $category->image_public_id);

        return parent::update($id, $data);
    }

    public function delete(string $id): bool
    {
        $category = Category::findOrFail($id);
        $publicId = $category->image_public_id;

        $deleted = (bool) $category->delete();

        if ($deleted && $publicId) {
            $this->cloudinary->destroy($publicId);
        }

        return $deleted;
    }

    public function tree(): \Illuminate\Support\Collection
    {
        $all = Category::orderBy('sort_order')->get();

        $build = function ($parentId) use (&$build, $all) {
            return $all->where('parent_id', $parentId)->map(function ($cat) use (&$build) {
                $cat->setRelation('children', $build($cat->id));

                return $cat;
            })->values();
        };

        return $build(null);
    }

    public function reorder(array $orderedIds): void
    {
        foreach ($orderedIds as $index => $id) {
            Category::where('id', $id)->update(['sort_order' => $index]);
        }
    }

    public function toggleActive(string $id): Category
    {
        $category = Category::findOrFail($id);
        $category->update(['is_active' => ! $category->is_active]);

        return $category;
    }

    protected function applyFilters(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['search'] ?? null, fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->when($filters['parent_id'] ?? null, fn ($q, $pid) => $q->where('parent_id', $pid))
            ->when(isset($filters['is_active']), fn ($q) => $q->where('is_active', (bool) $filters['is_active']));
    }

    protected function orderColumn(): string
    {
        return 'sort_order';
    }
}
