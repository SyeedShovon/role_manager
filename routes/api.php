<?php

use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Permissions
Route::get('all-permissions',[PermissionController::class,'index']);
Route::post('store-permissions',[PermissionController::class,'store']);
Route::patch('edit-permission',[PermissionController::class,'update']);
Route::delete('delete-permission/{id}',[PermissionController::class,'destroy']);


//Roles
Route::get('all-roles',[RoleController::class,'index']);
Route::post('store-roles',[RoleController::class,'store']);
Route::patch('edit-role',[RoleController::class,'update']);
Route::delete('delete-role/{id}',[RoleController::class,'destroy']);