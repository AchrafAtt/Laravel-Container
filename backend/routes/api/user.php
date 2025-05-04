<?php
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::middleware('jwt.auth')->get('/user',[UserController::class, 'index']);

//store user
Route::middleware('jwt.auth')->post('/user',[UserController::class, 'store']);

//update user
Route::middleware('jwt.auth')->put('/user/{id}',[UserController::class, 'update']);


//delete user
Route::middleware('jwt.auth')->delete('/user/{id}', function ($id) {
    $user = User::findOrFail($id);
    $user->delete();
    return response()->json(['message' => 'User deleted successfully'], 200);
});
