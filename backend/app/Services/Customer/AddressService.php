<?php

namespace App\Services\Customer;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

/**
 * A customer's own address book. Every method is implicitly scoped to the
 * given $user — there's no "list all addresses" here the way Admin's
 * address service has, by design, since a customer should never be able
 * to reach another customer's address through this service's shape alone.
 */
class AddressService
{
    public function list(User $user): Collection
    {
        return $user->addresses()
                    ->orderByDesc('is_default')
                    ->latest()
                    ->get();
    }

    public function create(User $user, array $data): Address
    {
        return DB::transaction(function () use ($user, $data) {
            if (! empty($data['is_default'])) {
                $user->addresses()  
                     ->update(['is_default' => false]);
            }

            return $user->addresses()
                        ->create($data);
        });
    }

    public function update(User $user, string $id, array $data): Address
    {
        return DB::transaction(function () use ($user, $id, $data) {
            $address = $user->addresses()
                            ->findOrFail($id);

            if (! empty($data['is_default'])) {
                $user->addresses()
                     ->where('id', '!=', $id)
                     ->update(['is_default' => false]);
            }

            $address->update($data);

            return $address->fresh();
        });
    }

    public function delete(User $user, string $id): void
    {
        $user->addresses()
             ->findOrFail($id)
             ->delete();
    }
}