<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ClaimController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\Admin\AdminClaimController;
use App\Http\Controllers\Api\Admin\AdminCategoryController;
use App\Http\Controllers\Api\Admin\AdminItemController;
use App\Http\Controllers\Api\Admin\AdminStatsController;
use App\Http\Controllers\Api\Admin\AdminUserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // ── PUBLIC ────────────────────────────────────────────────────────────────
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login',    [AuthController::class, 'login']);
    });

    Route::get('items',          [ItemController::class, 'index']);
    Route::get('items/{item}',   [ItemController::class, 'show']);
    Route::get('categories',     [CategoryController::class, 'index']);

    // ── DEV / TESTER HELPER ──────────────────────────────────────────────────
    if (app()->environment('local')) {
        Route::get('dev/db-inspect', function () {
            $tables = ['users', 'categories', 'items', 'item_photos', 'claims', 'notifications', 'personal_access_tokens'];
            $result = [];
            foreach ($tables as $table) {
                if (! \Illuminate\Support\Facades\Schema::hasTable($table)) continue;
                $columns = \Illuminate\Support\Facades\DB::select("SHOW FULL COLUMNS FROM `{$table}`");
                $count = \Illuminate\Support\Facades\DB::table($table)->count();
                $samples = \Illuminate\Support\Facades\DB::table($table)->limit(25)->get();
                $result[$table] = [
                    'count' => $count,
                    'columns' => $columns,
                    'samples' => $samples,
                ];
            }
            return response()->json(['data' => $result]);
        });
    }

    // ── AUTHENTICATED ─────────────────────────────────────────────────────────
    Route::middleware('auth:sanctum')->group(function () {

        Route::post('auth/logout', [AuthController::class, 'logout']);

        Route::get('profile',  [ProfileController::class, 'show']);
        Route::put('profile',  [ProfileController::class, 'update']);

        Route::post('items',                   [ItemController::class, 'store']);
        Route::put('items/{item}',             [ItemController::class, 'update']);
        Route::put('items/{item}/status',      [ItemController::class, 'updateStatus']);
        Route::delete('items/{item}',          [ItemController::class, 'destroy']);
        Route::get('items/user/my',            [ItemController::class, 'myItems']);
        Route::post('items/{item}/claims',     [ClaimController::class, 'store']);
        Route::post('items/{item}/reward',     [ItemController::class, 'reward']);

        Route::get('claims/incoming',          [ClaimController::class, 'incoming']);
        Route::get('claims/my',                [ClaimController::class, 'myClaims']);
        Route::patch('claims/{claim}/status',  [ClaimController::class, 'updateStatus']);
        Route::get('claims/{claim}/contact',   [ClaimController::class, 'contact']);

        Route::get('notifications',            [NotificationController::class, 'index']);
        Route::patch('notifications/read-all', [NotificationController::class, 'markAllRead']);
        Route::patch('notifications/{notification}/read', [NotificationController::class, 'markRead']);

        // ── ADMIN ─────────────────────────────────────────────────────────────
        Route::middleware('admin')->prefix('admin')->group(function () {
            Route::get('stats',  [AdminStatsController::class, 'index']);

            Route::get('items',          [AdminItemController::class, 'index']);
            Route::patch('items/{item}', [AdminItemController::class, 'update']);
            Route::delete('items/{item}',[AdminItemController::class, 'destroy']);

            Route::get('claims',           [AdminClaimController::class, 'index']);
            Route::patch('claims/{claim}', [AdminClaimController::class, 'update']);

            Route::get('categories',              [AdminCategoryController::class, 'index']);
            Route::post('categories',             [AdminCategoryController::class, 'store']);
            Route::put('categories/{category}',   [AdminCategoryController::class, 'update']);
            Route::delete('categories/{category}',[AdminCategoryController::class, 'destroy']);

            Route::get('users',          [AdminUserController::class, 'index']);
            Route::patch('users/{user}', [AdminUserController::class, 'update']);
            Route::delete('users/{user}',[AdminUserController::class, 'destroy']);
        });
    });
});
