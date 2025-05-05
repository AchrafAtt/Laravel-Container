<?php
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// Route::middleware('jwt.auth',)->get('/user',[UserController::class, 'index']);


Route::middleware('jwt.auth')->group(function () {
    // Now apply the role middleware
    Route::middleware('role:admin')->get('/user', [UserController::class, 'index']);

    //store user
    Route::post('/user',[UserController::class, 'store']);
    
    //update user
    Route::put('/user/{id}',[UserController::class, 'update']);

    //delete user
    Route::delete('/user/{id}', function ($id) {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully'], 200);
    });
    
    // Non-admin routes that still need JWT auth
    Route::get('/profile', [UserController::class, 'profile']);
});
