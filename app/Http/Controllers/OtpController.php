<?php

namespace App\Http\Controllers;

use App\Http\Requests\OtpCheckRequest;
use App\Http\Requests\OtpRequest;
use App\Http\Resources\OtpResource;
use App\Mail\VerifyEmail;
use App\Models\Otp;
use App\Models\TokenResetPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OtpController extends Controller
{
    public function store(OtpRequest $request)
    {
        $otpData['otp'] = rand(1000, 9999);
        $otpData['email'] = $request->email;
        $otpData['expired_at'] = now()->addMinutes(6);

        do {
            $otpData['id'] = 'otp-'.Str::uuid();
        } while (Otp::where('id', $otpData['id'])->exists());

        Otp::where('email', $request->email)->delete();
        $otp = Otp::create($otpData);
        $otp = new OtpResource($otp);

        Mail::to($request->email)->send(new VerifyEmail($otpData['otp']));

        return response([
            'data' => $otp,
        ]);
    }

    public function check(OtpCheckRequest $request)
    {
        $otp = Otp::where('email', $request->email)->latest()->first();
        if (! $otp) {
            return $this->resDataNotFound('OTP With Current Email');
        }

        $user = User::where('email', $request->email)->first();
        if ($otp->otp == $request->otp) {
            if ($otp->expired_at < now()) {
                $otp->delete();
                return response(['message' => 'OTP is expired'], 400);
            }

            if ($request->reset_password == "true") {
                TokenResetPassword::where('email', $request->email)->delete();
                $tokenResetPassword = TokenResetPassword::create(['email' => $request->email, 'token' => Str::random(60)]);
            }

            $otp->delete();
            $user->update(['is_verified_email' => true]);
            return response(['message' => 'OTP is valid', 'token_reset_password' => $tokenResetPassword->token ?? null,]);
        }

        return response(['message' => 'OTP is invalid']);
    }

    public function show()
    {
        return OtpResource::collection(Otp::all());
    }

    public function showCurrent()
    {
        $otp = Otp::where('email', auth()->user()->email)->latest()->first();
        if (! $otp) {
            return $this->resDataNotFound('OTP');
        }

        return new OtpResource($otp);
    }

    public function showByEmail(Request $request)
    {
        $otp = Otp::where('email', $request->email)->latest()->first();
        if (! $otp) {
            return $this->resDataNotFound('OTP');
        }

        return new OtpResource($otp);
    }
}
