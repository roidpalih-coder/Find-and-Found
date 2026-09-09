<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminClaimController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $claims = Claim::with(['item:id,title,type', 'claimant:id,name,email'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(20);

        return response()->json($claims);
    }

    public function update(Request $request, Claim $claim): JsonResponse
    {
        $request->validate([
            'status'         => ['required', 'in:approved,rejected'],
            'response_notes' => ['nullable', 'string'],
        ]);

        $claim->update([
            'status'         => $request->status,
            'response_notes' => $request->response_notes,
        ]);

        return response()->json(['message' => 'Status klaim diperbarui.']);
    }
}
