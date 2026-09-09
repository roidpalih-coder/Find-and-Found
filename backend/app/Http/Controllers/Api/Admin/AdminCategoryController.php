<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminCategoryController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['data' => Category::withCount('items')->orderBy('name')->get()]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name'                 => ['required', 'string', 'max:50'],
            'slug'                 => ['required', 'string', 'max:60', 'unique:categories,slug'],
            'icon'                 => ['nullable', 'string', 'max:50'],
            'is_priority_document' => ['boolean'],
        ]);

        $category = Category::create($request->all());

        return response()->json(['message' => 'Kategori berhasil dibuat.', 'data' => $category], 201);
    }

    public function update(Request $request, Category $category): JsonResponse
    {
        $request->validate([
            'name'                 => ['sometimes', 'string', 'max:50'],
            'slug'                 => ['sometimes', 'string', 'max:60', 'unique:categories,slug,'.$category->id],
            'icon'                 => ['nullable', 'string', 'max:50'],
            'is_priority_document' => ['boolean'],
        ]);

        $category->update($request->all());

        return response()->json(['message' => 'Kategori berhasil diperbarui.', 'data' => $category]);
    }

    public function destroy(Category $category): JsonResponse
    {
        if ($category->items()->exists()) {
            return response()->json(['message' => 'Kategori tidak bisa dihapus karena masih digunakan.'], 422);
        }

        $category->delete();
        return response()->json(['message' => 'Kategori berhasil dihapus.']);
    }
}
