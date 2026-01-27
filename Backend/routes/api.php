<?php

use App\Http\Controllers\AuthApiController;
use App\Http\Controllers\BarangApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/barang/paginate',[BarangApiController::class,'indexPaginate']);
Route::post('/barang/paginate/create',[BarangApiController::class,'createPaginate']);
Route::get('/barang/paginate/search',[BarangApiController::class,'search']);

Route::get('/barang/cache',[BarangApiController::class,'indexCache']);
Route::post('/barang/cache/create',[BarangApiController::class,'createCache']);

Route::post('/barang/job/create',[BarangApiController::class,'createJob']);
Route::post('/barang/job/update',[BarangApiController::class,'update']);
Route::delete('/barang/hapus/{id}',[BarangApiController::class,'destroy']);

Route::get('/me',[AuthApiController::class,'me'])->middleware('throttle:10,1'); //artinya 10x request dlm 1 menit