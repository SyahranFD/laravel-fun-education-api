<?php

namespace App\Http\Controllers;

use App\Http\Requests\SchoolInformationRequest;
use App\Http\Resources\SchoolInformationResource;
use App\Models\SchoolInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SchoolInformationController extends Controller
{
    public function store(SchoolInformationRequest $request)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $schoolInformationData = $request->all();
        do {
            $schoolInformationData['id'] = 'school-information-'.Str::uuid();
        } while (SchoolInformation::where('id', $schoolInformationData['id'])->exists());

        $schoolInformation = SchoolInformation::create($schoolInformationData);
        $schoolInformation = new SchoolInformationResource($schoolInformation);

        return $this->resStoreData($schoolInformation);
    }

    public function index()
    {
        return SchoolInformationResource::collection(SchoolInformation::orderBy('created_at', 'desc')->get());
    }

    public function update(SchoolInformationRequest $request, $id)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $schoolInformation = SchoolInformation::find($id);
        if (! $schoolInformation) {
            return $this->resDataNotFound('School Information');
        }

        $schoolInformation->update($request->all());
        $schoolInformation = new SchoolInformationResource($schoolInformation);

        return $this->resStoreData($schoolInformation);
    }

    public function destroy($id)
    {
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $schoolInformation = SchoolInformation::find($id);
        if (! $schoolInformation) {
            return $this->resDataNotFound('School Information');
        }

        $schoolInformation->delete();

        return $this->resDataDeleted('School Information');
    }
}
