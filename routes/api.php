<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function (): void {
    Route::get('/user', function (Request $request): User {
        return $request->user();
    })->middleware('auth:sanctum')->name('user');
});
