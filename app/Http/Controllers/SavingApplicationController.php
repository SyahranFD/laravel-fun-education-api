<?php

namespace App\Http\Controllers;

use App\Http\Requests\SavingApplicationRequest;
use App\Http\Resources\SavingApplicationResource;
use App\Models\SavingApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SavingApplicationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['category' => 'required|string',]);
        auth()->user();

        $savingApplicationData = $request->all();
        $savingApplicationData['status'] = 'Pending';
        $savingApplicationData['user_id'] = auth()->id();
        do {
            $savingApplicationData['id'] = 'saving-application-'.Str::uuid();
        } while (SavingApplication::where('id', $savingApplicationData['id'])->exists());

        $savingApplication = SavingApplication::create($savingApplicationData);
        $savingApplication = new SavingApplicationResource($savingApplication);

        return $this->resStoreData($savingApplication);
    }

    public function index()
    {
        return SavingApplicationResource::collection(SavingApplication::orderBy('created_at', 'desc')->get());
    }

    public function showById($id)
    {
        $savingApplication = SavingApplication::find($id);
        if (! $savingApplication) {
            return $this->resDataNotFound('Saving Application');
        }

        return new SavingApplicationResource($savingApplication);
    }

    public function showCurrent()
    {
        $user = auth()->user();
        if (! $user) {
            return $this->resUserNotFound();
        }

        $savingApplication = SavingApplication::where('user_id', $user->id)->first();
        if (! $savingApplication) {
            return $this->resDataNotFound('Saving Application');
        }

        return new SavingApplicationResource($savingApplication);
    }

    public function showByUserId($userId)
    {
        $savingApplication = SavingApplication::where('user_id', $userId)->first();
        if (! $savingApplication) {
            return $this->resDataNotFound('Saving Application');
        }

        return new SavingApplicationResource($savingApplication);
    }

    public function update(SavingApplicationRequest $request, $id)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $savingApplication = SavingApplication::find($id);
        if (! $savingApplication) {
            return $this->resDataNotFound('Saving Application');
        }

        $savingApplication->update($request->all());

        return new SavingApplicationResource($savingApplication);
    }

    public function destroy($id)
    {
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $savingApplication = SavingApplication::find($id);
        if (! $savingApplication) {
            return $this->resDataNotFound('Saving Application');
        }

        $savingApplication->delete();

        return $this->resDataDeleted('Saving Application');
    }
}
