<?php

namespace App\Services\Api;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;




class PasswordResetService
{
   

    public function sendResetLinkEmail(string $email)
    {
         // Find the user by their professional email
    $user = User::where('email_pro', $email)->first();
    
    \Log::info('User found: ' . ($user ? $user->id : 'Not found'));

    if (!$user) {
        return [
            'success' => false,
            'message' => 'No user found with this email address.'
        ];
    }

        // Create a new token
        $token = Str::random(64);

        // Store the token in the password_reset_tokens table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email_pro' => $email],
            [
                'email_pro' => $email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );

        \Log::info('Password reset requested for: ' . $email);


        // Send the password reset notification
      // Debug log
    \Log::info('Token stored in database');
    $resetUrl = config('app.frontend_url') . '/reset-password?token=' . $token . '&email=' . $email;

     // Send email
     Mail::to($user->email_pro)
     ->send(new ResetPasswordMail($user, $resetUrl));
 
        return [
            'success' => true,
            'message' => 'We have emailed your password reset link.'
        ];
    }
/**
     * Reset the user's password.
     *
     * @param  array  $data
     * @return array
     */
    public function resetPassword(array $data)
    {
        // Validate token
        $tokenData = DB::table('password_reset_tokens')
            ->where('email_pro', $data['email_pro'])
            ->first();
            
            if (!$tokenData || $data['token'] !== $tokenData->token) { 
                return [
                    'success' => false,
                    'message' => 'This password reset token is invalid.'
                ];
            }
        
        // Check if token is expired (1 hour)
        if (Carbon::parse($tokenData->created_at)->addHour()->isPast()) {
            DB::table('password_reset_tokens')
                ->where('email_pro', $data['email_pro'])
                ->delete();
                
            return [
                'success' => false,
                'message' => 'This password reset token has expired.'
            ];
        }
        
        // Update user password
        $user = User::where('email_pro', $data['email_pro'])->first();
        $user->password = Hash::make($data['password']);
        $user->save();
        
        // Delete the token
        DB::table('password_reset_tokens')
            ->where('email_pro', $data['email_pro'])
            ->delete();
            
        return [
            'success' => true,
            'message' => 'Your password has been reset!'
        ];
    }
}