<?php

namespace App\Services\Admin;

use App\Models\Review;
use Illuminate\Database\Eloquent\Builder;

class ReviewService extends BaseCrudService
{
    protected string $model = Review::class;

    protected array $with = ['product', 'user'];

    public function create(array $data) : Review {
        return Review::create($data);
    }
    public function approve(string $id): Review
    {
        $review = Review::findOrFail($id);
        $review->update(['is_approved' => true]);

        return $review;
    }

    public function reject(string $id): Review
    {
        $review = Review::findOrFail($id);
        $review->update(['is_approved' => false]);

        return $review;
    }

    protected function applyFilters(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['product_id'] ?? null, fn ($q, $p) => $q->where('product_id', $p))
            ->when(isset($filters['is_approved']), fn ($q) => $q->where('is_approved', (bool) $filters['is_approved']))
            ->when($filters['rating'] ?? null, fn ($q, $r) => $q->where('rating', $r));
    }
}