<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AdminStatsController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => [
                'total_items'          => Item::count(),
                'total_lost'           => Item::where('type', 'lost')->count(),
                'total_found'          => Item::where('type', 'found')->count(),
                'total_open'           => Item::where('status', 'open')->count(),
                'total_resolved'       => Item::where('status', 'resolved')->count(),
                'total_claims'         => Claim::count(),
                'total_claims_pending' => Claim::where('status', 'pending')->count(),
                'total_users'          => User::where('role', 'user')->count(),
            ],
        ]);
    }
}
