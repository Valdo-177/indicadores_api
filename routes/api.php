<?php

use App\Http\Controllers\Api\userController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', [userController::class, 'index']);

Route::get('/user/{id}', [userController::class, 'show']);

Route::post('/user', [userController::class, 'store']);

// Route::post('/user', function (Request $request) {
//     return response()->json(['student' => $request->name, 'status' => 201], 201);
// });

Route::put('/user/{id}', [userController::class, 'update']);
Route::patch('/user/{id}', [userController::class, 'partialUpdate']);

Route::delete('/user/{id}', [userController::class, 'destroy']);
