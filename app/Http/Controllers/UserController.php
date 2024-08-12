<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserWithSecretResource;
use App\Models\AlurBelajar;
use App\Models\Leaderboard;
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
        $userData['is_verified'] = false;

        do {
            $userData['id'] = 'user-'.Str::uuid();
        } while (User::where('id', $userData['id'])->exists());

        $user = User::create($userData);
        $tabungan = Saving::create(['id' => 'saving-'.Str::uuid(), 'user_id' => $user->id, 'saving' => 0]);
        $alurBelajar = AlurBelajar::create(['id' => 'alur-belajar-'.Str::uuid(), 'user_id' => $user->id, 'tahap' => 'A']);
        $leaderboard = Leaderboard::create(['id' => 'leaderboard-'.Str::uuid(), 'user_id' => $user->id, 'point' => 0]);
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
        if ($request->fcm_token) {
            $user->update(['fcm_token' => $request->fcm_token]);
        }
        $token = $user->createToken('fun-education')->plainTextToken;

        return response([
            'data' => $user,
            'token' => $token,
        ], 200);
    }

    public function index(Request $request)
    {
        $shift = $request->query('shift');
        $is_verified = $request->query('is_verified');
        $users = [];
        if ($shift) {
            $users = User::where('shift', $shift)->where('role', 'student')->orderBy('created_at', 'desc')->get();
        } else {
            $users = User::where('role', 'student')->orderBy('created_at', 'desc')->get();
        }

        if ($is_verified) {
            $is_verified = filter_var($is_verified, FILTER_VALIDATE_BOOLEAN);
            $users = $users->where('is_verified', $is_verified);
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

    public function verify(Request $request, $id)
    {
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $user = User::find($id);
        if (! $user) {
            return $this->resUserNotFound();
        }

        if ($request->is_verified == true) {
            $user->update(['is_verified' => true]);
            return response(['message' => 'User Verified', 'data' => $user], 200);

        } elseif ($request->is_verified == false) {
            $user->delete();
            return $this->resDataDeleted('User');
        }
    }

    public function logout()
    {
        $user = auth()->user();
        $user->fcm_token = null;
        $user->save();

        $user->tokens()->delete();

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
