<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'Softwarehouse API is running.'
    ]);
});
