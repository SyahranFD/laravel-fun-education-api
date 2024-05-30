<?php

namespace App\Http\Controllers;

use App\Http\Requests\SavingApplicationRequest;
use App\Http\Resources\SavingApplicationResource;
use App\Models\SavingApplication;
use Illuminate\Support\Str;

class SavingApplicationController extends Controller
{
    public function store(SavingApplicationRequest $request)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $savingApplicationData = $request->all();
        $savingApplicationData['status'] = 'Pending';
        do {
            $savingApplicationData['id'] = 'saving-application-'.Str::uuid();
        } while (SavingApplication::where('id', $savingApplicationData['id'])->exists());

        $savingApplication = SavingApplication::create($savingApplicationData);
        $savingApplication = new SavingApplicationResource($savingApplication);

        return $this->resStoreData($savingApplication);
    }

    public function index()
    {
        return SavingApplicationResource::collection(SavingApplication::all());
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
        $savingApplication = auth()->user()->savingApplication;
        if (! $savingApplication) {
            return $this->resDataNotFound('Saving Application');
        }

        return SavingApplicationResource::collection($savingApplication);
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
