<?php

namespace App\Http\Controllers;

use App\Http\Requests\CatatanDaruratFileRequest;
use App\Http\Resources\CatatanDaruratFileResource;
use App\Models\CatatanDaruratFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CatatanDaruratFileController extends Controller
{
    public function __construct()
    {
        $this->url = Config::get('url.hosting');
    }

    public function store(CatatanDaruratFileRequest $request)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $catatanDaruratFileData = $request->all();
        do {
            $catatanDaruratFileData['id'] = 'catatan-darurat-file-'.Str::uuid();
        } while (CatatanDaruratFile::where('id', $catatanDaruratFileData['id'])->exists());

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('public');
            $catatanDaruratFileData['file'] = $this->url.Storage::url($filePath);
        }

        $catatanDaruratFile = CatatanDaruratFile::create($catatanDaruratFileData);
        $catatanDaruratFile = new CatatanDaruratFileResource($catatanDaruratFile);

        return $this->resStoreData($catatanDaruratFile);
    }

    public function index()
    {
        return CatatanDaruratFileResource::collection(CatatanDaruratFile::orderBy('created_at', 'desc')->get());
    }

    public function show($id)
    {
        $catatanDaruratFile = CatatanDaruratFile::find($id);
        if (! $catatanDaruratFile) {
            return $this->resDataNotFound('Catatan Darurat File');
        }

        return new CatatanDaruratFileResource($catatanDaruratFile);
    }

    public function destroy($id)
    {
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $catatanDaruratFile = CatatanDaruratFile::find($id);
        if (! $catatanDaruratFile) {
            return $this->resDataNotFound('Catatan Darurat File');
        }

        $catatanDaruratFile->delete();

        return $this->resDataDeleted('Catatan Darurat File');
    }
}
