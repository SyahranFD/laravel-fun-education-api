<?php

namespace App\Http\Controllers;

use App\Http\Resources\PasswordResetTokenResource;
use App\Models\PasswordResetToken;
use Illuminate\Http\Request;

class PasswordResetTokenController extends Controller
{
    public function index()
    {
        return PasswordResetTokenResource::collection(PasswordResetToken::all());
    }

    public function show(Request $request)
    {
        $token = PasswordResetToken::where('email', $request->email)->latest()->first();
        if (! $token) {
            return $this->resDataNotFound('Token With Current Email');
        }

        return new PasswordResetTokenResource($token);
    }
}
