<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Services\Admin\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(protected UserService $users) {}

    public function index(Request $request)
    {
        $id = $request->user()->id;

        $users = $this->users->paginate(
            $request->integer('per_page', 15),
            $request->only([
                'search',
                'role',
                'is_active',
                'trashed',
            ]),
            [
                ['id', '!=', $id],
            ]
        );

        return $this->paginatedSuccess($users);
    }


    public function show(string $id)
    {
        return $this->success($this->users->find($id));
    }

    public function store(StoreUserRequest $request)
    {
        return $this->created($this->users->create($request->validated()), 'User created');
    }

    public function update(UpdateUserRequest $request, string $id)
    {
        return $this->success($this->users->update($id, $request->validated()), 'User updated');
    }

    public function destroy(Request $request,string $id)
    {
        $user = $request->user();

        if ($user->role === 'admin' && $user->id === $id) {
            return $this->error('You cannot delete yourself.');
        }
        
        $this->users->delete($id);

        return $this->noContentSuccess('User deleted');
    }

    public function toggleActive(string $id)
    {
        return $this->success($this->users->toggleActive($id), 'User status toggled');
    }

    public function orders(string $id)
    {
        return $this->paginatedSuccess($this->users->orderHistory($id));
    }
}