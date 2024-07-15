<?php

namespace App\Http\Controllers;

use App\Http\Requests\TugasImageRequest;
use App\Http\Resources\TugasImageResource;
use App\Models\TugasImage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;

class TugasImageController extends Controller
{
    public function __construct()
    {
        $this->url = Config::get('url.hosting');
    }

    public function store(TugasImageRequest $request)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $tugasImageData = $request->all();
        do {
            $tugasImageData['id'] = 'tugas-image-'.Str::uuid();
        } while (TugasImage::where('id', $tugasImageData['id'])->exists());

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/tugas');
            $tugasImageData['image'] = $this->url.Storage::url($imagePath);
        }

        $tugasImage = TugasImage::create($tugasImageData);
        $tugasImage = new TugasImageResource($tugasImage);

        return $this->resStoreData($tugasImage);
    }

    public function index()
    {
        return TugasImageResource::collection(TugasImage::all());
    }

    public function show($id)
    {
        $tugasImage = TugasImage::find($id);
        if (! $tugasImage) {
            return $this->resNotFound();
        }

        return new TugasImageResource($tugasImage);
    }

    public function update(TugasImageRequest $request, $id)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $tugasImage = TugasImage::find($id);
        if (! $tugasImage) {
            return $this->resNotFound();
        }

        $tugasImageData = $request->all();
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/tugas');
            $galleryData['image'] = $this->url.Storage::url($imagePath);
        }

        $tugasImage->update($tugasImageData);

        return new TugasImageResource($tugasImage);
    }

    public function destroy($id)
    {
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $tugasImage = TugasImage::find($id);
        if (! $tugasImage) {
            return $this->resNotFound();
        }

        $tugasImage->delete();

        return $this->resDataDeleted('Tugas Image');
    }
}
