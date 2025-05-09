<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;

use App\Services\Api\PasswordResetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    protected $passwordResetService;

    public function __construct(PasswordResetService $passwordResetService)
    {
        $this->passwordResetService = $passwordResetService;
    }



    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_pro' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->passwordResetService->sendResetLinkEmail($request->email_pro);

        if ($result['success']) {
            return response()->json($result, 200);
        }

        return response()->json($result, 404);
    }

    /**
     * Handle the password reset request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request){

        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email_pro' => 'required|email',
            'password' => 'required|min:8',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->passwordResetService->resetPassword($request->all());

        if ($result['success']) {
            return response()->json($result, 200);
        }
        return response()->json($result, 404);


    }



}
