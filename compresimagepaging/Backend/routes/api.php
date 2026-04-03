<?php

use App\Http\Controllers\BarangApiController;
use App\Http\Controllers\VideoApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/barang',BarangApiController::class);
Route::apiResource('/video',VideoApiController::class);

Route::get('/test', function () {
    return [
        'php_binary' => PHP_BINARY,
        'gd' => extension_loaded('gd')
    ];
});