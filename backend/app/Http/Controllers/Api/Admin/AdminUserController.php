<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $users = User::withCount(['items', 'claims'])
            ->when($request->role, fn($q) => $q->where('role', $request->role))
            ->when($request->search, fn($q) => $q->where(function($q2) use ($request) {
                $q2->where('name', 'like', '%'.$request->search.'%')
                   ->orWhere('email', 'like', '%'.$request->search.'%');
            }))
            ->latest()
            ->paginate(20);

        return response()->json($users);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $request->validate([
            'role' => ['sometimes', 'in:user,admin'],
        ]);

        $user->update($request->only(['role']));

        return response()->json(['message' => 'User berhasil diperbarui.', 'data' => $user]);
    }

    public function destroy(User $user): JsonResponse
    {
        if ($user->role === 'admin') {
            return response()->json(['message' => 'Admin tidak bisa dihapus.'], 422);
        }

        $user->delete();
        return response()->json(['message' => 'User berhasil dihapus.']);
    }
}
