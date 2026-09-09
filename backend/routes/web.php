<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/tester');
});

Route::get('/tester', function () {
    $path = public_path('tester.html');
    if (file_exists($path)) {
        return response()->file($path);
    }
    return response()->json(['message' => 'tester.html not found in public folder.'], 404);
});
