<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class EmailController extends Controller
{
    public function emailSend(Request $request){
        if($request->user()->hasVerifiedEmail()){
            $response['status'] = false;
            $response['message'] = 'Email Sudah Diverifikasi';
            return response()->json($response);
        }
        $request->user()->sendEmailVerificationNotification();
        $response['status'] = True;
        $response['message'] = 'Verifikasi Email Dikirim';
        return response()->json($response);
    }

    public function verify(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return [
                'message' => 'Email already verified'
            ];
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        $response['message'] = 'Email Diverifikasi';
        return response()->json($response);
    }
}
