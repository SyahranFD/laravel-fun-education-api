<?php

namespace App\Http\Controllers;

use App\Http\Requests\MinimumApplicationRequest;
use App\Http\Resources\MinimumApplicationResource;
use App\Models\MinimumApplication;
use Illuminate\Support\Str;

class MinimumApplicationController extends Controller
{
    public function store(MinimumApplicationRequest $request)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $minimumApplicationData = $request->all();
        do {
            $minimumApplicationData['id'] = 'saving-application-'.Str::uuid();
        } while (MinimumApplication::where('id', $minimumApplicationData['id'])->exists());

        $minimumApplication = MinimumApplication::create($minimumApplicationData);
        $minimumApplication = new MinimumApplicationResource($minimumApplication);

        return $this->resStoreData($minimumApplication);
    }

    public function index()
    {
        return MinimumApplicationResource::collection(MinimumApplication::all());
    }

    public function showById($id)
    {
        $minimumApplication = MinimumApplication::find($id);
        if (! $minimumApplication) {
            return $this->resDataNotFound('Minimum Application');
        }

        return new MinimumApplicationResource($minimumApplication);
    }

    public function showCurrent()
    {
        $minimumApplication = auth()->user()->minimumApplication;
        if (! $minimumApplication) {
            return $this->resDataNotFound('Minimum Application');
        }

        $saving = auth()->user()->savings;
        if (! $saving) {
            return $this->resDataNotFound('Saving');
        }

        $responseData = [];
        foreach ($minimumApplication as $minimumApplications)
        {
            $responseData[] = [
                'id' => $minimumApplications->id,
                'user_id' => $minimumApplications->user_id,
                'category' => $minimumApplications->category,
                'minimum' => number_format($minimumApplications->minimum, 0, '.', '.'),
                'is_enough' => $saving->saving >= $minimumApplications->minimum
            ];
        }

        return response(['data' => $responseData], 200);
    }

    public function update(MinimumApplicationRequest $request, $id)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $minimumApplication = MinimumApplication::find($id);
        if (! $minimumApplication) {
            return $this->resDataNotFound('Minimum Application');
        }

        $minimumApplication->update($request->all());

        return new MinimumApplicationResource($minimumApplication);
    }

    public function destroy($id)
    {
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $minimumApplication = MinimumApplication::find($id);
        if (! $minimumApplication) {
            return $this->resDataNotFound('Minimum Application');
        }

        $minimumApplication->delete();

        return $this->resDataDeleted('Minimum Application');
    }
}
