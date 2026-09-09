<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemStatusRequest;
use App\Models\AppNotification;
use App\Models\Item;
use App\Models\ItemPhoto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Item::with(['user:id,name,reputation_points', 'category'])
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->district, fn($q) => $q->where('district', 'like', '%'.$request->district.'%'))
            ->when($request->search, fn($q) => $q->where(function($q2) use ($request) {
                $q2->where('title', 'like', '%'.$request->search.'%')
                   ->orWhere('description', 'like', '%'.$request->search.'%')
                   ->orWhere('location_name', 'like', '%'.$request->search.'%');
            }))
            ->when($request->date_from, fn($q) => $q->whereDate('incident_date', '>=', $request->date_from))
            ->when($request->date_to, fn($q) => $q->whereDate('incident_date', '<=', $request->date_to))
            ->when($request->priority, fn($q) => $q->whereHas('category', fn($q2) => $q2->where('is_priority_document', true)))
            ->orderByRaw("CASE WHEN EXISTS(SELECT 1 FROM categories WHERE categories.id = items.category_id AND categories.is_priority_document = 1) THEN 0 ELSE 1 END")
            ->latest();

        return response()->json($query->paginate(15));
    }

    public function show(Item $item): JsonResponse
    {
        $item->load(['user:id,name,reputation_points,domicile_city', 'category', 'photos']);
        $data = $item->toArray();
        unset($data['secret_details']);

        return response()->json(['data' => $data]);
    }

    public function store(StoreItemRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        if ($request->hasFile('photo')) {
            $data['primary_photo_url'] = $request->file('photo')->store('items', 'public');
        }

        $item = Item::create($data);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                ItemPhoto::create([
                    'item_id'    => $item->id,
                    'photo_url'  => $photo->store('items', 'public'),
                    'created_at' => now(),
                ]);
            }
        }

        $this->triggerSmartMatch($item);

        return response()->json([
            'message' => 'Laporan berhasil dipublikasikan.',
            'data'    => $item->load('category'),
        ], 201);
    }

    public function update(StoreItemRequest $request, Item $item): JsonResponse
    {
        if ($request->user()->id !== $item->user_id) {
            return response()->json(['message' => 'Tidak diizinkan.'], 403);
        }

        if ($item->status !== 'open') {
            return response()->json(['message' => 'Laporan yang sudah diklaim tidak bisa diedit.'], 422);
        }

        $data = $request->validated();

        if ($request->hasFile('photo')) {
            if ($item->primary_photo_url) Storage::disk('public')->delete($item->primary_photo_url);
            $data['primary_photo_url'] = $request->file('photo')->store('items', 'public');
        }

        $item->update($data);

        return response()->json(['message' => 'Laporan berhasil diperbarui.', 'data' => $item->fresh()]);
    }

    public function updateStatus(UpdateItemStatusRequest $request, Item $item): JsonResponse
    {
        $user = $request->user();

        if ($user->id !== $item->user_id && ! $user->isAdmin()) {
            return response()->json(['message' => 'Tidak diizinkan.'], 403);
        }

        $item->update(['status' => $request->status]);

        return response()->json(['message' => 'Status laporan diperbarui menjadi '.$request->status.'.']);
    }

    public function destroy(Request $request, Item $item): JsonResponse
    {
        $user = $request->user();

        if ($user->id !== $item->user_id && ! $user->isAdmin()) {
            return response()->json(['message' => 'Tidak diizinkan.'], 403);
        }

        if (! in_array($item->status, ['open', 'cancelled'])) {
            return response()->json(['message' => 'Laporan aktif tidak bisa dihapus.'], 422);
        }

        if ($item->primary_photo_url) Storage::disk('public')->delete($item->primary_photo_url);
        foreach ($item->photos as $photo) {
            Storage::disk('public')->delete($photo->photo_url);
        }

        $item->delete();

        return response()->json(['message' => 'Laporan berhasil dihapus.']);
    }

    public function myItems(Request $request): JsonResponse
    {
        $query = Item::with(['category'])
            ->where('user_id', $request->user()->id)
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->latest();

        return response()->json($query->paginate(15));
    }

    public function reward(Request $request, Item $item): JsonResponse
    {
        $request->validate([
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'badge'   => ['nullable', 'string', 'in:honest_finder,quick_responder,community_hero'],
            'message' => ['nullable', 'string', 'max:500'],
        ]);

        if ($request->user()->id !== $item->user_id) {
            return response()->json(['message' => 'Tidak diizinkan.'], 403);
        }

        if ($item->status !== 'resolved') {
            return response()->json(['message' => 'Barang belum berstatus selesai.'], 422);
        }

        $points = $request->badge ? 50 : 10;
        $item->user->increment('reputation_points', $points);

        return response()->json([
            'message' => 'Apresiasi berhasil diberikan. Penemu mendapat +'.$points.' poin reputasi.',
        ]);
    }

    private function triggerSmartMatch(Item $item): void
    {
        if ($item->type === 'found') {
            $matches = Item::where('type', 'lost')
                ->where('status', 'open')
                ->where('category_id', $item->category_id)
                ->where('user_id', '!=', $item->user_id)
                ->get();

            foreach ($matches as $lostItem) {
                AppNotification::create([
                    'user_id' => $lostItem->user_id,
                    'type'    => 'smart_match',
                    'data'    => [
                        'item_id'       => $item->id,
                        'item_title'    => $item->title,
                        'your_item_id'  => $lostItem->id,
                        'message'       => 'Ada barang ditemukan yang mungkin cocok dengan laporan kehilangan Anda: "'.$item->title.'".',
                    ],
                ]);
            }
        }
    }
}
