<?php

namespace App\Http\Controllers;

use App\Http\Requests\SavingRequest;
use App\Http\Resources\SavingResource;
use App\Models\Saving;
use Illuminate\Support\Str;
use Number;

class SavingController extends Controller
{
    public function store(SavingRequest $request)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $savingData = $request->all();
        do {
            $savingData['id'] = 'saving-'.Str::uuid();
        } while (Saving::where('id', $savingData['id'])->exists());

        $saving = Saving::create($savingData);
        $saving = new SavingResource($saving);

        return $this->resStoreData($saving);
    }

    public function index()
    {
        return SavingResource::collection(Saving::orderBy('created_at', 'desc')->get());
    }

    public function showById($id)
    {
        $saving = Saving::find($id);
        if (! $saving) {
            return $this->resDataNotFound('saving');
        }

        return new SavingResource($saving);
    }

    public function showByUserId($userId)
    {
        $saving = Saving::where('user_id', $userId)->first();
        if (! $saving) {
            return $this->resDataNotFound('saving');
        }

        return new SavingResource($saving);
    }

    public function showCurrent()
    {
        $saving = Saving::where('user_id', auth()->user()->id)->first();
        if (! $saving) {
            return $this->resDataNotFound('Saving');
        }

        return new SavingResource($saving);
    }

    public function update(SavingRequest $request, $id)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $saving = Saving::find($id);
        if (! $saving) {
            return $this->resDataNotFound('saving');
        }

        $saving->update($request->all());

        return new SavingResource($saving);
    }

    public function destroy($id)
    {
        $saving = Saving::find($id);
        if (! $saving) {
            return $this->resDataNotFound('saving');
        }

        $saving->delete();

        return $this->resDataDeleted('saving');
    }
}
