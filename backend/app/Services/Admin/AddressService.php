<?php

namespace App\Services\Admin;

use App\Models\Address;
use Illuminate\Database\Eloquent\Builder;

class AddressService extends BaseCrudService
{
    protected string $model = Address::class;

    protected array $with = ['user'];

    protected function applyFilters(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['user_id'] ?? null, fn ($q, $u) => $q->where('user_id', $u))
            ->when($filters['city'] ?? null, fn ($q, $c) => $q->where('city', $c))
            ->when($filters['country'] ?? null, fn ($q, $c) => $q->where('country', $c));
    }
}
