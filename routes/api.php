<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DailyGoalsController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\WaterIntakeController;
use App\Models\DailyGoals;
use App\Models\WaterIntake;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix("auths")->controller(AuthController::class)->group(function(){
    Route::get("/details","detail")->middleware("auth:sanctum");

    Route::post("/login","login")->name("login");
    Route::post("/logout","logout")->middleware("auth:sanctum");
    Route::post("/register","register");
});

Route::middleware("auth:sanctum")->group(function(){
    Route::apiResource("daily-goals",DailyGoalsController::class);
    Route::apiResource("water-intakes",WaterIntakeController::class);
    Route::apiResource("reminders",ReminderController::class);
    Route::post("/token",[AuthController::class,"updateToken"]);
    Route::prefix("daily-goals/")->controller(DailyGoalsController::class)->group(function(){
        Route::get("/today","today")->middleware("auth:sanctum");
    });
});



