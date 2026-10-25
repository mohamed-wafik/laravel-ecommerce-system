<?php

namespace App\Services\Guest;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Read-only storefront browsing — no auth required. Mirrors the shape big
 * e-commerce catalogs expose to anonymous visitors: category tree,
 * filterable/sortable product listing, product detail with variants +
 * approved reviews + related items, brand list, and search-as-you-type.
 * Every query is scoped to active/approved records only — nothing a guest
 * sees here ever includes drafts, disabled products, or unmoderated reviews.
 */
class CatalogService
{
    public function categoryTree(): Collection
    {
        $all = Category::active()->orderBy('sort_order')->get();

        $build = function ($parentId) use (&$build, $all) {
            return $all->where('parent_id', $parentId)
                        ->map(function ($cat) use (&$build) {
                            $cat->setRelation('children', $build($cat->id));

                            return $cat;
                        })->values();
        };

        return $build(null);
    }

    public function parentCategory(): Collection {
        return Category::query()
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function brands(): Collection
    {
        return Brand::active()
                      ->orderBy('name')
                      ->get();
    }

    /**
     * Filters: search, category_id, brand_id, min_price, max_price,
     * sort (price_asc|price_low... see applySort), plus standard pagination.
     */
    public function products(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = Product::query()
            ->active()
            ->inStock()
            ->withCount(['reviews as approved_reviews_count' => fn ($q) => $q->approved()])
            ->withAvg(['reviews as average_rating' => fn ($q) => $q->approved()], 'rating')
            ->with([
                'images' => fn ($q) => $q->orderBy('sort_order')->limit(1), 
                'brand', 
                'category',
                'variants'
            ]);

        $query = $this->applyFilters($query, $filters);
        $query = $this->applySort($query, $filters['sort'] ?? 'newest');

        return $query->paginate($perPage)->withQueryString();
    }

    /*  */

    public function topDealProducts() {
        return Product::withCount(['orderItems', 'reviews'])
            ->withCount(['reviews as approved_reviews_count' => fn ($q) => $q->approved()])
            ->withAvg(['reviews as average_rating' => fn ($q) => $q->approved()], 'rating')
            ->orderBy('order_items_count', 'desc')
            ->with([
                'images' => fn ($q) => $q->orderBy('sort_order')->limit(1), 
                'variants'
            ])
            ->take(10)
            ->get();
    }
    public function product(string $slug): Product
    {
        return Product::active()
            ->with([
                'images', 'variants', 'category', 'brand',
                'reviews' => fn ($q) => $q->approved()->latest('created_at')->limit(10)->with('user:id,name'),
            ])
            ->withCount(['reviews as approved_reviews_count' => fn ($q) => $q->approved()])
            ->withAvg(['reviews as average_rating' => fn ($q) => $q->approved()], 'rating')
            ->where('slug', $slug)
            ->firstOrFail();
    }

    /**
     * "Customers also viewed" style strip — same category, excludes the
     * product itself, active + in-stock only.
     */
    public function relatedProducts(Product $product, int $limit = 8): Collection
    {
        return Product::active()
            ->inStock()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->withCount(['reviews as approved_reviews_count' => fn ($q) => $q->approved()])
            ->withAvg(['reviews as average_rating' => fn ($q) => $q->approved()], 'rating')
            ->with(['images' => fn ($q) => $q->orderBy('sort_order')->limit(1)])
            ->limit($limit)
            ->get();
    }

    public function reviews(string $productId, int $perPage = 10): LengthAwarePaginator
    {
        return Review::approved()
            ->where('product_id', $productId)
            ->with('user:id,name')
            ->latest('created_at')
            ->paginate($perPage);
    }

    /**
     * Lightweight search-as-you-type: product name/SKU + category name
     * matches, capped small since this is meant for a dropdown, not a
     * full results page (that's products() with `search` in $filters).
     */
    public function searchSuggestions(string $query, int $limit = 8): array
    {
        $products = Product::active()
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")->orWhere('sku', 'like', "%{$query}%");
            })
            ->limit($limit)
            ->get(['id', 'name', 'slug', 'price']);

        $categories = Category::active()
            ->where('name', 'like', "%{$query}%")
            ->limit(5)
            ->get(['id', 'name', 'slug']);

        return [
            'products' => $products,
            'categories' => $categories,
        ];
    }

    protected function applyFilters(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['search'] ?? null, fn ($q, $s) => $q->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")->orWhere('description', 'like', "%{$s}%");
            }))
            ->when($filters['category_id'] ?? null, fn ($q, $c) => $q->where('category_id', $c))
            ->when($filters['brand_id'] ?? null, fn ($q, $b) => $q->where('brand_id', $b))
            ->when($filters['min_price'] ?? null, fn ($q, $p) => $q->where('price', '>=', $p))
            ->when($filters['max_price'] ?? null, fn ($q, $p) => $q->where('price', '<=', $p))
            ->when($filters['only_discounted'] ?? false,fn ($q) => $q->whereNotNull('discount_price'))
            ->when($filters['min_rating'] ?? null,fn ($q, $rating) => $q->having('reviews_avg_rating', '>=', $rating));
    }

    protected function applySort(Builder $query, string $sort): Builder
    {
        return match ($sort) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'name_asc' => $query->orderBy('name'),
            'best_selling' => $query->withCount('orderItems')->orderByDesc('order_items_count'),
            default => $query->latest('created_at'), // 'newest'
        };
    }
}