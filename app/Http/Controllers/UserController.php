<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserWithSecretResource;
use App\Models\AlurBelajar;
use App\Models\Leaderboard;
use App\Models\Saving;
use App\Models\TokenResetPassword;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Laravel\Firebase\Facades\Firebase;

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
        $userData['password'] = Hash::make($request->password);

        do {
            $userData['id'] = 'user-'.Str::uuid();
        } while (User::where('id', $userData['id'])->exists());

        $user = User::create($userData);
        Saving::create(['id' => 'saving-'.Str::uuid(), 'user_id' => $user->id, 'saving' => 0]);
        AlurBelajar::create(['id' => 'alur-belajar-'.Str::uuid(), 'user_id' => $user->id, 'tahap' => 'A']);
        Leaderboard::create(['id' => 'leaderboard-'.Str::uuid(), 'user_id' => $user->id, 'point' => 0]);
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
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
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
        $is_graduated = $request->query('is_graduated');
        $search = $request->query('search');
        $gender = $request->query('gender');

        $query = User::query();

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('full_name', 'like', '%' . $search . '%')
                    ->orWhere('nickname', 'like', '%' . $search . '%')
                    ->orWhere('birth', 'like', '%' . $search . '%')
                    ->orWhere('address', 'like', '%' . $search . '%');
            });
        }

        if ($gender) {
            $query->where('gender', $gender);
        }

        if ($shift) {
            $query->where('shift', $shift);
        }

        if ($is_verified) {
            $is_verified = filter_var($is_verified, FILTER_VALIDATE_BOOLEAN);
            $query->where('is_verified', $is_verified);
        } else {
            $query->where('is_verified', true);
        }

        if ($is_graduated) {
            $is_graduated = filter_var($is_graduated, FILTER_VALIDATE_BOOLEAN);
            $query->where('is_graduated', $is_graduated);
        } else {
            $query->where('is_graduated', false);
        }

        $users = $query->where('role', 'student')
            ->where('is_verified_email', true)
            ->orderBy('created_at', 'desc')
            ->get();

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

    public function checkEmail(Request $request)
    {
        $email = $request->email;
        $isExist = $request->is_exist;

        if ($isExist) {
            $isExist = filter_var($isExist, FILTER_VALIDATE_BOOLEAN);
            $user = User::where('email', $email)->first();
            if ($isExist) {
                if ($user) {
                    return response(['message' => 'Email Exist'], 200);
                } else {
                    return response(['message' => 'Email Not Exist'], 404);
                }
            } else {
                if ($user) {
                    return response(['message' => 'Email Exist'], 404);
                } else {
                    return response(['message' => 'Email Not Exist'], 200);
                }
            }
        } else {
            $user = User::where('email', $email)->first();
            if ($user) {
                return response(['message' => 'Email Exist'], 200);
            } else {
                return response(['message' => 'Email Not Exist'], 404);
            }
        }
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

    public function resetPassword(ResetPasswordRequest $request)
    {
        $request->validated();
        $user = User::where('email', $request->email)->first();
        if (! $user) {
            return $this->resUserNotFound();
        }

        $tokenResetPassword = TokenResetPassword::where('email', $request->email)->latest()->first();
        if ($tokenResetPassword->token != $request->token_reset_password) {
            return response(['message' => 'Token Reset Password Invalid'], 400);
        }

        TokenResetPassword::where('email', $request->email)->delete();
        $newPassword = Hash::make($request->password);
        $user->update(['password' => $newPassword]);

        return response([
            'message' => 'Reset Password Success',
        ], 201);
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

        $notification = 'User tidak memiliki fcm_token atau fcm_token tidak valid';
        $title = '';
        $body = '';

        if ($request->is_verified == true) {
            $title = 'Pembuatan Akun Anda Disetujui';
            $body = 'Selamat, akun Anda telah disetujui!';
        } elseif ($request->is_verified == false) {
            $title = 'Pembuatan Akun Anda Ditolak';
            $body = 'Maaf, akun Anda tidak disetujui.';
        }

        if ($user->fcm_token) {
            $notification = $this->notification($user, $title, $body);
        }

        if ($request->is_verified == true) {
            $user->update(['is_verified' => true]);
            return response(['message' => 'User Verified', 'data' => $user, 'notification' => $notification], 200);

        } elseif ($request->is_verified == false) {
            $user->delete();
            return response(['message' => 'User Deleted', 'notification' => $notification], 200);
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

    public function notification($user, $title, $body)
    {
        try {
            $FcmToken = $user->fcm_token;
            $message = CloudMessage::fromArray([
                'token' => $FcmToken,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
            ]);

            Firebase::messaging()->send($message);
            return $message;
        } catch (\Kreait\Firebase\Exception\Messaging\NotFound $e) {
            // If a NotFound exception is thrown, do nothing and continue
        }
    }
}
