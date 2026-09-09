<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = AppNotification::where('user_id', $request->user()->id)
            ->when($request->read === 'true', fn($q) => $q->whereNotNull('read_at'))
            ->when($request->read === 'false', fn($q) => $q->whereNull('read_at'))
            ->latest();

        $notifications = $query->paginate(20);
        $unreadCount = AppNotification::where('user_id', $request->user()->id)->whereNull('read_at')->count();

        return response()->json([
            'data'         => $notifications->items(),
            'meta'         => [
                'current_page' => $notifications->currentPage(),
                'last_page'    => $notifications->lastPage(),
                'total'        => $notifications->total(),
            ],
            'unread_count' => $unreadCount,
        ]);
    }

    public function markRead(Request $request, AppNotification $notification): JsonResponse
    {
        if ($notification->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Tidak diizinkan.'], 403);
        }

        $notification->update(['read_at' => now()]);

        return response()->json(['message' => 'Notifikasi ditandai telah dibaca.']);
    }

    public function markAllRead(Request $request): JsonResponse
    {
        AppNotification::where('user_id', $request->user()->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['message' => 'Semua notifikasi ditandai telah dibaca.']);
    }
}
