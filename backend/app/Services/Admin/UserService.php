<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Services\CloudinaryService;
use App\Services\Concerns\HashesPassword;
use App\Services\Concerns\ReplacesCloudinaryImage;
use Illuminate\Database\Eloquent\Builder;

class UserService extends BaseCrudService
{
    use HashesPassword, ReplacesCloudinaryImage;

    protected string $model = User::class;

    public function __construct(protected CloudinaryService $cloudinary) {}

    public function create(array $data): User
    {
        $data = $this->hashPassword($data, required: true);
        $data = $this->replaceImage($data, 'avatar', 'avatar_url', 'avatar_public_id', 'avatars');

        return parent::create($data);
    }

    public function update(string $id, array $data): User
    {
        $data = $this->hashPassword($data);

        $user = User::findOrFail($id);
        $data = $this->replaceImage($data, 'avatar', 'avatar_url', 'avatar_public_id', 'avatars', $user->avatar_public_id);

        return parent::update($id, $data);
    }

    public function delete(string $id): bool
    {
        $user = User::findOrFail($id);
        $publicId = $user->avatar_public_id;

        $deleted = (bool) $user->delete();

        if ($deleted && $publicId) {
            $this->cloudinary->destroy($publicId);
        }

        return $deleted;
    }

    public function toggleActive(string $id): User
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => ! $user->is_active]);

        return $user;
    }

    public function promoteToAdmin(string $id): User
    {
        $user = User::findOrFail($id);
        $user->update(['role' => 'admin']);

        return $user;
    }

    public function orderHistory(string $id, int $perPage = 15)
    {
        return User::findOrFail($id)->orders()->latest()->paginate($perPage);
    }

    protected function applyFilters(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['search'] ?? null, fn ($q, $s) => $q->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('email', 'like', "%{$s}%")
                    ->orWhere('phone', 'like', "%{$s}%");
            }))
            ->when($filters['role'] ?? null, fn ($q, $role) => $q->where('role', $role))
            ->when(isset($filters['is_active']), fn ($q) => $q->where('is_active', (bool) $filters['is_active']))
            ->when($filters['trashed'] ?? null, fn ($q) => $q->onlyTrashed());
    }
}
