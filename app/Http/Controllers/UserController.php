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
        $user = User::where('nama_lengkap', $request->nama_lengkap)->first();

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
            'nama_lengkap' => $request->nama_lengkap,
            'password' => Hash::make($request->password),
        ];

        $user->update($userData);
        $user = new UserResource($user);

        return $this->resShowData($user);
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

        return response(['message' => 'User Deleted'], 200);
    }
}
