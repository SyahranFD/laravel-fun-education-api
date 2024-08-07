<?php

namespace App\Http\Controllers;

use App\Http\Requests\TugasUserImageRequest;
use App\Http\Resources\TugasUserImageResource;
use App\Models\TugasUserImage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;

class TugasUserImageController extends Controller
{
    public function __construct()
    {
        $this->url = Config::get('url.hosting');
    }

    public function store(TugasUserImageRequest $request)
    {
        $request->validated();

        $tugasUserImageData = $request->all();
        do {
            $tugasUserImageData['id'] = 'tugas-image-'.Str::uuid();
        } while (TugasUserImage::where('id', $tugasUserImageData['id'])->exists());

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public');
            $tugasUserImageData['image'] = $this->url.Storage::url($imagePath);
        }

        $tugasUserImage = TugasUserImage::create($tugasUserImageData);
        $tugasUserImage = new TugasUserImageResource($tugasUserImage);

        return $this->resStoreData($tugasUserImage);
    }

    public function index()
    {
        return TugasUserImageResource::collection(TugasUserImage::orderBy('created_at', 'desc')->get());
    }

    public function show($id)
    {
        $tugasUserImage = TugasUserImage::find($id);
        if (! $tugasUserImage) {
            return $this->resNotFound();
        }

        return new TugasUserImageResource($tugasUserImage);
    }

    public function update(TugasUserImageRequest $request, $id)
    {
        $request->validated();

        $tugasUserImage = TugasUserImage::find($id);
        if (! $tugasUserImage) {
            return $this->resNotFound();
        }

        $tugasUserImageData = $request->all();
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public');
            $galleryData['image'] = $this->url.Storage::url($imagePath);
        }

        $tugasUserImage->update($tugasUserImageData);

        return new TugasUserImageResource($tugasUserImage);
    }

    public function destroy($id)
    {
        $tugasUserImage = TugasUserImage::find($id);
        if (! $tugasUserImage) {
            return $this->resNotFound();
        }

        $tugasUserImage->delete();

        return $this->resDataDeleted('Tugas Image');
    }
}
