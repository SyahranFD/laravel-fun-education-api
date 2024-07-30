<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserWithSecretResource;
use App\Models\AlurBelajar;
use App\Models\Saving;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $request->validated();

        $userData = $request->all();
        $userData['role'] = 'student';

        $nameParts = explode(' ', $request->nickname);
        $firstName = $nameParts[0];
        $lastName = $nameParts[1] ?? '';
        $userData['profile_picture'] = 'https://ui-avatars.com/api/?name='.urlencode($firstName.' '.$lastName).'&color=7F9CF5&background=EBF4FF&size=128';

        do {
            $userData['id'] = 'user-'.Str::uuid();
        } while (User::where('id', $userData['id'])->exists());

        $user = User::create($userData);
        $tabungan = Saving::create(['id' => 'saving-'.Str::uuid(), 'user_id' => $user->id, 'saving' => 0]);
        $alurBelajar = AlurBelajar::create(['id' => 'alur-belajar-'.Str::uuid(), 'user_id' => $user->id, 'tahap' => 'A']);
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
        $user = User::where('nickname', $request->nickname)
            ->where('password', $request->password)
            ->first();

        if (! $user) {
            return $this->resInvalidLogin();
        }

        $user = new UserResource($user);
        $token = $user->createToken('fun-education')->plainTextToken;

        return response([
            'data' => $user,
            'token' => $token,
        ], 200);
    }

    public function index(Request $request)
    {
        $shift = $request->query('shift');
        $users = [];
        if ($shift) {
            $users = User::where('shift', $shift)->where('role', 'student')->get();
        } else {
            $users = User::where('role', 'student')->get();
        }

        return UserResource::collection($users);
    }

    public function showById($id)
    {
        $user = User::find($id);
        if (! $user) {
            return $this->resUserNotFound();
        }

        return new UserResource($user);
    }

    public function showByIdSecret($id)
    {
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $user = User::find($id);
        if (! $user) {
            return $this->resUserNotFound();
        }

        return new UserWithSecretResource($user);
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

        $userData = $request->all();
        $userData['password'] = Hash::make($request->password);

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
