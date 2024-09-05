<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlurBelajarRequest;
use App\Http\Resources\AlurBelajarResource;
use App\Models\AlurBelajar;
use App\Models\User;
use Illuminate\Support\Str;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Laravel\Firebase\Facades\Firebase;

class AlurBelajarController extends Controller
{
    public function store(AlurBelajarRequest $request)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $alurBelajarData = $request->all();
        do {
            $alurBelajarData['id'] = 'alur-belajar-'.Str::uuid();
        } while (AlurBelajar::where('id', $alurBelajarData['id'])->exists());

        $alurBelajar = AlurBelajar::create($alurBelajarData);
        $alurBelajar = new AlurBelajarResource($alurBelajar);

        return $this->resStoreData($alurBelajar);
    }

    public function index()
    {
        return AlurBelajarResource::collection(AlurBelajar::all());
    }

    public function showById($id)
    {
        $alurBelajar = AlurBelajar::find($id);
        if (! $alurBelajar) {
            return $this->resDataNotFound('Alur Belajar');
        }

        return new AlurBelajarResource($alurBelajar);
    }

    public function showCurrent()
    {
        $alurBelajar = auth()->user()->alurBelajar;
        if (! $alurBelajar) {
            return $this->resDataNotFound('Alur Belajar');
        }

        return new AlurBelajarResource($alurBelajar);
    }

    public function showByUserId($userId)
    {
        $alurBelajar = AlurBelajar::where('user_id', $userId)->first();
        if (! $alurBelajar) {
            return $this->resDataNotFound('Alur Belajar');
        }

        return new AlurBelajarResource($alurBelajar);
    }

    public function update(AlurBelajarRequest $request, $id)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $alurBelajar = AlurBelajar::find($id);
        if (! $alurBelajar) {
            return $this->resDataNotFound('Alur Belajar');
        }

        $alurBelajarData = $request->all();
        $alurBelajar->update($alurBelajarData);
        $alurBelajar = new AlurBelajarResource($alurBelajar);

        $user = User::find($request->get('user_id'));
        $notification = 'User tidak memiliki fcm_token atau fcm_token tidak valid';
        if ($user->fcm_token) {
            $notification = $this->notification($user, 'Alur Belajar Telah Meningkat', 'Alur belajar anak anda telah meningkat, silahkan cek aplikasi untuk melihatnya.');
        }

        return $this->resStoreData($alurBelajar, $notification);
    }

    public function delete($id)
    {
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $alurBelajar = AlurBelajar::find($id);
        if (! $alurBelajar) {
            return $this->resDataNotFound('Alur Belajar');
        }

        $alurBelajar->delete();

        return $this->resDataDeleted('Alur Belajar');
    }

    public function notification($user, $title, $body)
    {
        try {
            $message = CloudMessage::fromArray([
                'token' => $user->fcm_token,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
            ])->withData([
                'route' => '/laporan-page',
            ]);

            Firebase::messaging()->send($message);
            return $message;
        } catch (\Kreait\Firebase\Exception\Messaging\NotFound $e) {
            // If a NotFound exception is thrown, do nothing and continue
        }
    }
}
