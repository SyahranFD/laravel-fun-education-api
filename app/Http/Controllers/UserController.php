<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $request->validated();

        $userData = [
            'nama_lengkap' => $request->nama_lengkap,
            'password' => Hash::make($request->password),
            'role' => 'student',
        ];

        $nameParts = explode(' ', $request->nama_lengkap);
        $firstName = $nameParts[0];
        $lastName = $nameParts[1] ?? '';
        $userData['profile_picture'] = 'https://ui-avatars.com/api/?name='.urlencode($firstName.' '.$lastName).'&color=7F9CF5&background=EBF4FF';

        do {
            $userData['id'] = 'user-'.Str::uuid();
        } while (User::where('id', $userData['id'])->exists());

        $user = User::create($userData);
        $user = new UserResource($user);
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

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return $this->resInvalidLogin($user, $request->password);
        }

        $user = new UserResource($user);
        $token = $user->createToken('fun-education')->plainTextToken;

        return response([
            'data' => $user,
            'token' => $token,
        ], 200);
    }

    public function showAll()
    {
        return UserResource::collection(User::all());
    }

    public function showById($id)
    {
        $user = new UserResource(User::find($id));
        $this->resUserNotFound($user);

        return $user;
    }

    public function showCurrent()
    {
        return new UserResource(auth()->user());
    }

    public function updateAdmin(UpdateUserRequest $request, $id)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return response(['message' => 'Anda Bukanlah Admin'], 401);
        }

        $user = User::find($id);
        if (! $user) {
            return $this->resUserNotFound($user);
        }

        $userData = [
            'nama_lengkap' => $request->nama_lengkap,
            'password' => Hash::make($request->password),
        ];

        $user->update($userData);
        $user = new UserResource($user);

        return $this->resShowData($user);
    }
}
