<?php

use App\Http\Controllers\Api\PermissionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('all-permissions',[PermissionController::class,'index']);
Route::post('store-permissions',[PermissionController::class,'store']);
Route::patch('edit-permission',[PermissionController::class,'update']);
Route::delete('delete-permission/{id}',[PermissionController::class,'destroy']);