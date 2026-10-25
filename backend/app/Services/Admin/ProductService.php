<?php

namespace App\Services\Admin;

use App\Models\Product;
use App\Services\CloudinaryService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductService extends BaseCrudService
{
    protected string $model = Product::class;

    protected array $with = ['category', 'brand', 'images', 'variants'];

    public function __construct(protected CloudinaryService $cloudinary) {}

    public function create(array $data): Product
    {
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

        return DB::transaction(function () use ($data) {
            /** @var Product $product */
            $product = parent::create(collect($data)->except(['images', 'variants'])->toArray());

            $this->syncImages($product, $data['images'] ?? []);
            $this->syncVariants($product, $data['variants'] ?? []);

            return $product->fresh($this->with);
        });
    }

    public function update(string $id, array $data): Product
    {
        return DB::transaction(function () use ($id, $data) {
            /** @var Product $product */
            $product = Product::findOrFail($id);
            $product->update(collect($data)->except(['images', 'variants'])->toArray());

            if (array_key_exists('images', $data)) {
                $this->syncImages($product, $data['images']);
            }

            if (array_key_exists('variants', $data)) {
                $this->syncVariants($product, $data['variants']);
            }

            return $product->fresh($this->with);
        });
    }

    /**
     * Accepts raw uploaded files straight from the "add product" request
     * (`$request->file('images')`) — this uploads each one to Cloudinary
     * itself, so there's no separate "upload first, then attach" step.
     *
     * Also accepts already-uploaded `{url, public_id}` arrays for callers
     * that uploaded elsewhere (e.g. the standalone MediaController flow),
     * so both styles work through the same method.
     *
     * @param  array<int, UploadedFile|array{url: string, public_id?: string, is_primary?: bool}>  $images
     */
    protected function syncImages(Product $product, array $images): void
    {
        if (empty($images)) {
            return;
        }

        // Clean up the assets being replaced so nothing is orphaned on Cloudinary.
        $oldPublicIds = $product->images()->whereNotNull('public_id')->pluck('public_id')->all();

        $product->images()->delete();

        foreach ($images as $index => $image) {
            if ($image instanceof UploadedFile) {
                $uploaded = $this->cloudinary->upload($image, 'products', ['resource_type' => 'image']);

                $product->images()->create([
                    'url' => $uploaded['url'],
                    'public_id' => $uploaded['public_id'],
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);

                continue;
            }

            $product->images()->create([
                'url' => $image['url'],
                'public_id' => $image['public_id'] ?? null,
                'is_primary' => $image['is_primary'] ?? $index === 0,
                'sort_order' => $index,
            ]);
        }

        if (! empty($oldPublicIds)) {
            $this->cloudinary->destroyMany($oldPublicIds);
        }
    }

    /**
     * Delete the product and any Cloudinary assets attached to it.
     */
    public function delete(string $id): bool
    {
        $product = Product::with('images')->findOrFail($id);
        $publicIds = $product->images->pluck('public_id')->filter()->all();

        $deleted = (bool) $product->delete();

        if ($deleted && ! empty($publicIds)) {
            $this->cloudinary->destroyMany($publicIds);
        }

        return $deleted;
    }

    protected function syncVariants(Product $product, array $variants): void
    {
        if (empty($variants)) {
            return;
        }

        $keepIds = [];

        foreach ($variants as $variant) {
            $record = $product->variants()->updateOrCreate(
                ['id' => $variant['id'] ?? null],
                collect($variant)->except('id')->toArray()
            );
            $keepIds[] = $record->id;
        }

        $product->variants()->whereNotIn('id', $keepIds)->delete();
    }

    public function adjustStock(string $id, int $delta): Product
    {
        $product = Product::findOrFail($id);
        $product->update(['stock_quantity' => max(0, $product->stock_quantity + $delta)]);

        return $product;
    }

    public function toggleActive(string $id): Product
    {
        $product = Product::findOrFail($id);
        $product->update(['is_active' => ! $product->is_active]);

        return $product;
    }

    public function lowStock(int $threshold = 5, int $perPage = 15)
    {
        return Product::with($this->with)
            ->where('stock_quantity', '<=', $threshold)
            ->where('is_active', true)
            ->paginate($perPage);
    }

    protected function applyFilters(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['search'] ?? null, fn ($q, $s) => $q->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")->orWhere('sku', 'like', "%{$s}%");
            }))
            ->when($filters['category_id'] ?? null, fn ($q, $c) => $q->where('category_id', $c))
            ->when($filters['brand_id'] ?? null, fn ($q, $b) => $q->where('brand_id', $b))
            ->when(isset($filters['is_active']), fn ($q) => $q->where('is_active', (bool) $filters['is_active']))
            ->when($filters['min_price'] ?? null, fn ($q, $p) => $q->where('price', '>=', $p))
            ->when($filters['max_price'] ?? null, fn ($q, $p) => $q->where('price', '<=', $p))
            ->when($filters['trashed'] ?? null, fn ($q) => $q->onlyTrashed());
    }
}