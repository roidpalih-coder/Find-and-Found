<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClaimRequest;
use App\Http\Requests\UpdateClaimStatusRequest;
use App\Models\AppNotification;
use App\Models\Claim;
use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClaimController extends Controller
{
    public function store(StoreClaimRequest $request, Item $item): JsonResponse
    {
        if ($item->status !== 'open') {
            return response()->json(['message' => 'Barang ini tidak tersedia untuk diklaim.'], 422);
        }

        if ($item->user_id === $request->user()->id) {
            return response()->json(['message' => 'Anda tidak bisa mengklaim barang milik sendiri.'], 422);
        }

        $existing = Claim::where('item_id', $item->id)
            ->where('claimant_id', $request->user()->id)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($existing) {
            return response()->json(['message' => 'Anda sudah memiliki klaim aktif untuk barang ini.'], 422);
        }

        $data = [
            'item_id'           => $item->id,
            'claimant_id'       => $request->user()->id,
            'proof_description' => $request->proof_description,
        ];

        if ($request->hasFile('proof_photo')) {
            $data['proof_photo_url'] = $request->file('proof_photo')->store('claims', 'public');
        }

        $claim = Claim::create($data);

        AppNotification::create([
            'user_id' => $item->user_id,
            'type'    => 'claim_submitted',
            'data'    => [
                'claim_id'       => $claim->id,
                'item_id'        => $item->id,
                'item_title'     => $item->title,
                'claimant_name'  => $request->user()->name,
                'message'        => $request->user()->name.' mengajukan klaim untuk barang "'.$item->title.'".',
            ],
        ]);

        return response()->json([
            'message' => 'Klaim berhasil diajukan. Menunggu konfirmasi penemu.',
            'data'    => $claim,
        ], 201);
    }

    public function incoming(Request $request): JsonResponse
    {
        $claims = Claim::with(['item:id,title,primary_photo_url,type', 'claimant:id,name,reputation_points'])
            ->whereHas('item', fn($q) => $q->where('user_id', $request->user()->id))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15);

        return response()->json($claims);
    }

    public function myClaims(Request $request): JsonResponse
    {
        $claims = Claim::with(['item:id,title,primary_photo_url,type,status'])
            ->where('claimant_id', $request->user()->id)
            ->latest()
            ->paginate(15);

        return response()->json($claims);
    }

    public function updateStatus(UpdateClaimStatusRequest $request, Claim $claim): JsonResponse
    {
        $user = $request->user();

        $isItemOwner = $claim->item->user_id === $user->id;
        if (! $isItemOwner && ! $user->isAdmin()) {
            return response()->json(['message' => 'Tidak diizinkan.'], 403);
        }

        if ($claim->status !== 'pending') {
            return response()->json(['message' => 'Klaim ini sudah diproses sebelumnya.'], 422);
        }

        $claim->update([
            'status'         => $request->status,
            'response_notes' => $request->response_notes,
        ]);

        if ($request->status === 'approved') {
            $claim->item->update(['status' => 'claimed']);

            Claim::where('item_id', $claim->item_id)
                ->where('id', '!=', $claim->id)
                ->where('status', 'pending')
                ->update(['status' => 'rejected', 'response_notes' => 'Klaim lain telah disetujui.']);

            $finder = $claim->item->user;
            $contact = [
                'phone_number'     => $finder->phone_number,
                'instagram_handle' => $finder->instagram_handle,
                'whatsapp_url'     => 'https://wa.me/'.$finder->phone_number.'?text=Halo%2C+saya+pemilik+barang+%22'.$claim->item->title.'%22+yang+klaimnya+disetujui.',
                'instagram_url'    => $finder->instagram_handle ? 'https://instagram.com/'.$finder->instagram_handle : null,
            ];

            AppNotification::create([
                'user_id' => $claim->claimant_id,
                'type'    => 'claim_approved',
                'data'    => [
                    'claim_id'   => $claim->id,
                    'item_id'    => $claim->item_id,
                    'item_title' => $claim->item->title,
                    'message'    => 'Klaim Anda untuk "'.$claim->item->title.'" telah disetujui!',
                ],
            ]);

            return response()->json([
                'message' => 'Klaim disetujui.',
                'contact' => $contact,
            ]);
        }

        AppNotification::create([
            'user_id' => $claim->claimant_id,
            'type'    => 'claim_rejected',
            'data'    => [
                'claim_id'   => $claim->id,
                'item_id'    => $claim->item_id,
                'item_title' => $claim->item->title,
                'message'    => 'Klaim Anda untuk "'.$claim->item->title.'" ditolak. '.($request->response_notes ?? ''),
            ],
        ]);

        return response()->json(['message' => 'Klaim ditolak.']);
    }

    public function contact(Request $request, Claim $claim): JsonResponse
    {
        if ($claim->claimant_id !== $request->user()->id && ! $request->user()->isAdmin()) {
            return response()->json(['message' => 'Tidak diizinkan.'], 403);
        }

        if ($claim->status !== 'approved') {
            return response()->json(['message' => 'Kontak hanya tersedia setelah klaim disetujui.'], 403);
        }

        $finder = $claim->item->user;

        return response()->json([
            'data' => [
                'phone_number'     => $finder->phone_number,
                'instagram_handle' => $finder->instagram_handle,
                'whatsapp_url'     => 'https://wa.me/'.$finder->phone_number,
                'instagram_url'    => $finder->instagram_handle ? 'https://instagram.com/'.$finder->instagram_handle : null,
            ],
        ]);
    }
}
