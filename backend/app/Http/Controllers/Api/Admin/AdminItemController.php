<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminItemController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $items = Item::with(['user:id,name,email', 'category'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
            ->when($request->search, fn($q) => $q->where('title', 'like', '%'.$request->search.'%'))
            ->latest()
            ->paginate(20);

        return response()->json($items);
    }

    public function update(Request $request, Item $item): JsonResponse
    {
        $request->validate([
            'status'           => ['required', 'in:open,claimed,resolved,cancelled'],
            'moderation_note'  => ['nullable', 'string'],
        ]);

        $item->update(['status' => $request->status]);

        return response()->json(['message' => 'Laporan berhasil dimoderasi.']);
    }

    public function destroy(Item $item): JsonResponse
    {
        $item->delete();
        return response()->json(['message' => 'Laporan berhasil dihapus permanen.']);
    }
}
