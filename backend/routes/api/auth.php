<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AutController;
Route::get('/auth', function() {
    return response()->json(['message' => 'Authentication route is working.']);     
});

Route::post('/login', [AutController::class, 'login'])->name('login');