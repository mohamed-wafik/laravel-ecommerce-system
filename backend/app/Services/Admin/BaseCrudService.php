<?php

namespace App\Services\Admin;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class BaseCrudService
{
    /**
     * @var class-string<Model>
     */
    protected string $model;

    /**
     * Default relations to eager load.
     */
    protected array $with = [];

    protected function query(): Builder
    {
        return $this->model::query()->with($this->with);
    }

public function paginate(
    int $perPage = 15,
    array $filters = [],
    array $where = []
): LengthAwarePaginator {
    $query = $this->query();

    foreach ($where as $condition) {
        $query->where(...$condition);
    }

    $query = $this->applyFilters($query, $filters);

    return $query
        ->latest($this->orderColumn())
        ->paginate($perPage)
        ->withQueryString();
}

    public function find(string $id): Model
    {
        return $this->query()->findOrFail($id);
    }

    public function create(array $data): Model
    {
        return $this->model::create($data);
    }

    public function update(string $id, array $data): Model
    {
        $record = $this->model::findOrFail($id);
        $record->update($data);

        return $record->fresh($this->with);
    }

    public function delete(string $id): bool
    {
        $record = $this->model::findOrFail($id);

        return (bool) $record->delete();
    }

    /**
     * Hook for subclasses to apply search/filter query params.
     */
    protected function applyFilters(Builder $query, array $filters): Builder
    {
        return $query;
    }

    protected function orderColumn(): string
    {
        return 'created_at';
    }
}