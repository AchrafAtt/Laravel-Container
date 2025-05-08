<?php
use App\Http\Controllers\PasswordResetController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;


Route::get('/auth', function() {
    return response()->json(['message' => 'Authentication route is working.']);     
});

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('jwt.auth')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/user', [AuthController::class, 'getUser']);

});


//resset password

// Password Reset Routes
Route::post('password/email', [PasswordResetController::class, 'sendResetLinkEmail']);
Route::post('password/reset', [PasswordResetController::class, 'reset']);
Route::get('password/reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');




Route::get('/test-mail', function () {
    Mail::raw('Test email at ' . now(), function (Message $message) {
        $message->to('test@gmail.com')
                ->subject('Test Email');
    });
    
    return 'Mail sent!';
});
