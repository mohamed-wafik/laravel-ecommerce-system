<?php

namespace App\Http\Controllers;

use App\Exports\usersExport;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request::query();
        $query = User::query();

        if (isset($filters['role']) && $filters['role'] !== '') {
            $query->where('role', $filters['role']);
        }

        if (isset($filters['search']) && $filters['search'] !== '') {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->with('orders')->paginate(10);

        return view('dashboard.users.index', compact('users'));
    }
    public function show($id) {
        $user = User::with("orders")->findOrFail($id);
        return view("dashboard.users.show",compact("user"));
    }
    public function updateRole(Request $request, $id)
    {
        $request::validate([
            'role' => 'required|in:admin,user,manager',
        ]);

        if(Auth::id() == $id || Auth::user()->role != 'admin') {
            return response()->json(['error' => 'You cannot change your own role.'], 403);
        }
        $user = User::findOrFail($id);
        $user->role = $request::input('role');
        $user->save();

        return response()->json(["role" => $user->role], 200);
    }

    public function destroy($id)
    {
        if(Auth::id() == $id || Auth::user()->role != 'admin') {
            return response()->json(['error' => 'You cannot delete this user.'], 403);
        }
        if(User::findOrFail($id)->orders()->exists()) {
            return response()
                    ->json(['error' => 'Cannot delete user with existing orders.'], 400);
        }
        if(User::findOrFail($id)->role == 'admin') {
            return response()
                    ->json(['error' => 'Cannot delete another admin user.'], 403);
        }
        $user = User::findOrFail($id);
        $user->delete();

        return response()
                ->json(['message' => 'User deleted successfully.'], 200);
    }

    public function exportUsers()
    {
        return Excel::download(new usersExport, 'users.xlsx');
    }
}