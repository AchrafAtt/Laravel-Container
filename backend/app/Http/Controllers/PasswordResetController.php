<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;


/**
 * Handle password reset flow
 * 

 */
class PasswordResetController extends Controller
{
    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email_pro' => 'required|email',
        ]);

        // Find the user by their professional email
        $user = User::where('email_pro', $request->email_pro)->first();

        \Log::info('User found: ' . ($user ? $user->id : 'Not found'));


        if (!$user) {
            throw ValidationException::withMessages([
                'email_pro' => ['No user found with this email address.'],
            ]);
        }

        // Create a new token
        $token = Str::random(64);

        // Store the token in the password_reset_tokens table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email_pro' => $request->email_pro],
            [
                'email_pro' => $request->email_pro,
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );

        \Log::info('Password reset requested for: ' . $request->email_pro);


        // Send the password reset notification
      // Debug log
    \Log::info('Token stored in database');

    try {
        // Send the password reset notification
        $user->notify(new ResetPasswordNotification($token));
        \Log::info('Notification sent to user');
    } catch (\Exception $e) {
        \Log::error('Failed to send notification: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
        
        // Still return success to avoid exposing information
        return response()->json([
            'message' => 'Password reset link sent to your email',
        ]);
    }

        return response()->json([
            'message' => 'Password reset link sent to your email',
        ]);
    }

    /**
     * Show the password reset form (if needed for web version)
     * 
     * @param string $token
     * @return \Illuminate\View\View
     */
    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Reset the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email_pro' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        // Check if token exists and is valid
        $tokenData = DB::table('password_reset_tokens')
            ->where('email_pro', $request->email_pro)
            ->where('token', $request->token)
            ->first();

        if (!$tokenData) {
            throw ValidationException::withMessages([
                'email_pro' => ['Invalid token or email.'],
            ]);
        }

        // Check if token is expired (tokens valid for 60 minutes)
        $createdAt = Carbon::parse($tokenData->created_at);
        if (Carbon::now()->diffInMinutes($createdAt) > 60) {
            DB::table('password_reset_tokens')->where('email_pro', $request->email_pro)->delete();
            
            throw ValidationException::withMessages([
                'email_pro' => ['Token has expired. Please request a new password reset link.'],
            ]);
        }

        // Update the user's password
        $user = User::where('email_pro', $request->email_pro)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the token
        DB::table('password_reset_tokens')->where('email_pro', $request->email_pro)->delete();

        return response()->json([
            'message' => 'Password has been successfully reset',
        ]);
    }
}