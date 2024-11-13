<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return response()->json(auth()->user());
})->middleware('auth:sanctum');


Route::prefix("auths")->controller(AuthController::class)->group(function(){
    Route::post("/login","login")->name("login");
    Route::post("/logout","logout")->middleware("auth:sanctum");
    Route::post("/register","register");
});


