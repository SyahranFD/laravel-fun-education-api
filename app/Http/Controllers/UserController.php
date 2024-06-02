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
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'tempat_tanggal_lahir' => $request->tempat_tanggal_lahir,
            'alamat' => $request->alamat,
            'role' => 'student',
        ];

        $nameParts = explode(' ', $request->username);
        $firstName = $nameParts[0];
        $lastName = $nameParts[1] ?? '';
        $userData['profile_picture'] = 'https://ui-avatars.com/api/?name='.urlencode($firstName.' '.$lastName).'&color=7F9CF5&background=EBF4FF&size=128';

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
        $user = User::where('username', $request->username)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return $this->resInvalidLogin();
        }

        $user = new UserResource($user);
        $token = $user->createToken('fun-education')->plainTextToken;

        return response([
            'data' => $user,
            'token' => $token,
        ], 200);
    }

    public function index()
    {
        return UserResource::collection(User::all());
    }

    public function showById($id)
    {
        $user = User::find($id);
        if (! $user) {
            return $this->resUserNotFound();
        }

        return new UserResource($user);
    }

    public function showCurrent()
    {
        $user = auth()->user();
        if (! $user) {
            return $this->resUserNotFound();
        }

        return new UserResource($user);
    }

    public function updateAdmin(UpdateUserRequest $request, $id)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $user = User::find($id);
        if (! $user) {
            return $this->resUserNotFound();
        }

        $userData = [
            'user_name' => $request->username,
            'password' => Hash::make($request->password),
            'tempat_tanggal_lahir' => $request->tempat_tanggal_lahir,
            'alamat' => $request->alamat,
        ];

        $user->update($userData);

        return new UserResource($user);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response(['message' => 'Logged Out'], 200);
    }

    public function delete($id)
    {
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        User::destroy($id);

        return $this->resDataDeleted('User');
    }
}
