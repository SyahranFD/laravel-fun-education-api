<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Config;


class UserController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $request->validated();
        $url = Config::get('url.localhost');

        $userData = [
            'nama_lengkap' => $request->nama_lengkap,
            'password' => Hash::make($request->password),
        ];

        if ($request->hasFile('profile_picture')) {
            $imagePath = $request->file('profile_picture')->store('public/profile-picture');
            $userData['profile_picture'] = $url.Storage::url($imagePath);
        } else {
            $nameParts = explode(' ', $request->nama_lengkap);
            $firstName = $nameParts[0];
            $lastName = $nameParts[1] ?? '';
            $userData['profile_picture'] = 'https://ui-avatars.com/api/?name=' . urlencode($firstName . ' ' . $lastName) . '&color=7F9CF5&background=EBF4FF';
        }

        do {
            $userData['id'] = 'user-' . Str::uuid();
        } while (User::where('id', $userData['id'])->exists());

        $user = User::create($userData);
        $token = $user->createToken('fun-education')->plainTextToken;

        return response([
            'data' => $user,
            'token' => $token,
        ], 201);
    }


    public function login(LoginRequest $request)
    {
        $request->validated();

        $user = User::where('nama_lengkap', $request->nama_lengkap)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => 'Nama Lengkap or Password Is Invalid',
            ], 409);
        }

        $token = $user->createToken('fun-education')->plainTextToken;

        return response([
            'data' => $user,
            'token' => $token,
        ], 200);
    }
}
