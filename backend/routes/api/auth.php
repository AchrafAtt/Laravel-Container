<?php
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;


// Group all auth routes under /auth prefix
Route::prefix('auth')->group(function () {
    // Public routes
    Route::get('/', function() {
        return response()->json(['message' => 'Authentication route is working.']);
    })->name('auth.check');
    
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1') // Rate limit: 5 attempts per minute
        ->name('auth.login');
    
    // Password reset routes
    Route::middleware('throttle:3,1')->group(function () { // Rate limit: 3 attempts per minute
        Route::post('/forgot-password', [ForgotPasswordController::class, 'forgotPassword'])
            ->name('auth.forgot-password');
        Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])
            ->name('auth.reset-password');
    });
    
    // Protected routes
    Route::middleware('jwt.auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])
            ->name('auth.logout');
        Route::get('/user', [AuthController::class, 'getUser'])
            ->name('auth.user');
    });
});