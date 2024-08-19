<?php

namespace App\Http\Controllers;

use App\Http\Requests\OtpRequest;
use App\Http\Resources\OtpResource;
use App\Mail\VerifyEmail;
use App\Models\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OtpController extends Controller
{
    public function store(OtpRequest $request)
    {
        $otpData['otp'] = rand(1000, 9999);
        $otpData['email'] = $request->email;

        do {
            $otpData['id'] = 'otp-'.Str::uuid();
        } while (Otp::where('id', $otpData['id'])->exists());

        Otp::where('email', $request->email)->delete();
        $otp = Otp::create($otpData);

        Mail::to($request->email)->send(new VerifyEmail($otp));

        return $this->resStoreData($otp);
    }

    public function check(Request $request)
    {
        $otp = Otp::where('email', auth()->user()->email)->latest()->first();
        if ($otp->otp == $request->otp) {
            $otp->delete();
            return response(['message' => 'OTP is valid']);
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
}
