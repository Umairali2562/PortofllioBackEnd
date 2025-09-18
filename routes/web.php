<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| All your API routes are in routes/api.php.
| This file is intentionally left empty.
|
*/

Route::get('/', function () {
    return response()->json([
        'message' => 'Laravel is running. Use /api/* for endpoints.'
    ]);
});
