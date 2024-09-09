<?php

namespace App\Http\Controllers;

use App\Http\Requests\SchoolInformationDescRequest;
use App\Http\Resources\SchoolInformationDescResource;
use App\Models\SchoolInformationDesc;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SchoolInformationDescController extends Controller
{
    public function store(SchoolInformationDescRequest $request)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $schoolInformationDescData = $request->all();
        do {
            $schoolInformationDescData['id'] = 'school-information-desc-'.Str::uuid();
        } while (SchoolInformationDesc::where('id', $schoolInformationDescData['id'])->exists());

        $schoolInformationDesc = SchoolInformationDesc::create($schoolInformationDescData);
        $schoolInformationDesc = new SchoolInformationDescResource($schoolInformationDesc);

        return $this->resStoreData($schoolInformationDesc);
    }

    public function index()
    {
        return SchoolInformationDescResource::collection(SchoolInformationDesc::all());
    }

    public function update(SchoolInformationDescRequest $request, $id)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $schoolInformationDesc = SchoolInformationDesc::find($id);
        if (! $schoolInformationDesc) {
            return $this->resDataNotFound('School Information Desc');
        }

        $schoolInformationDesc->update($request->all());
        $schoolInformationDesc = new SchoolInformationDescResource($schoolInformationDesc);

        return $this->resStoreData($schoolInformationDesc);
    }

    public function destroy($id)
    {
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $schoolInformationDesc = SchoolInformationDesc::find($id);
        if (! $schoolInformationDesc) {
            return $this->resDataNotFound('School Information Desc');
        }

        $schoolInformationDesc->delete();

        return $this->resDataDeleted('School Information Desc');
    }
}
