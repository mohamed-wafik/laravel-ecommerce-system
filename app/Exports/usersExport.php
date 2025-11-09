<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::withCount('orders')->get()->map(function ($user) {
            return [
                'ID'           => $user->id,
                'Name'         => $user->name,
                'Email'        => $user->email,
                'Role'         => $user->role,
                'Count Orders' => $user->orders->count(),
                'Created At'   => $user->created_at->format('Y-m-d H:i'),
                'Updated At'   => $user->updated_at->format('Y-m-d H:i'),
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'Name', 'Email', 'Role', 'Count Orders', 'Created At', 'Updated At'];
    }
}