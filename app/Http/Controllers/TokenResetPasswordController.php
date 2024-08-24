<?php

namespace App\Http\Controllers;

use App\Http\Resources\TokenResetPasswordResource;
use App\Models\TokenResetPassword;
use Illuminate\Http\Request;

class TokenResetPasswordController extends Controller
{
    public function index()
    {
        return TokenResetPasswordResource::collection(TokenResetPassword::all());
    }

    public function show(Request $request)
    {
        $token = TokenResetPassword::where('email', $request->email)->latest()->first();
        if (! $token) {
            return $this->resDataNotFound('Token With Current Email');
        }

        return new TokenResetPasswordResource($token);
    }
}
