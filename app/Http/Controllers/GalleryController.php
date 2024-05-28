<?php

namespace App\Http\Controllers;

use App\Http\Requests\GalleryRequest;
use App\Http\Resources\GalleryResource;
use App\Models\Gallery;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;

class GalleryController extends Controller
{
    public $url;

    public function __construct()
    {
        $this->url = Config::get('url.localhost');
    }
    public function store(GalleryRequest $request)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $galleryData = $request->all();
        do {
            $galleryData['id'] = 'gallery-'.Str::uuid();
        } while (Gallery::where('id', $galleryData['id'])->exists());

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/gallery');
            $galleryData['image'] = $this->url.Storage::url($imagePath);
        }

        $gallery = Gallery::create($galleryData);
        $gallery = new GalleryResource($gallery);

        return $this->resStoreData($gallery);
    }

    public function index()
    {
        return GalleryResource::collection(Gallery::all());
    }

    public function showById($id)
    {
        $gallery = Gallery::find($id);
        if (! $gallery) {
            return $this->resDataNotFound('Gallery');
        }

        return new GalleryResource($gallery);
    }

    public function update(GalleryRequest $request, $id)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $gallery = Gallery::find($id);
        if (! $gallery) {
            return $this->resDataNotFound('Gallery');
        }

        $galleryData = $request->all();
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/gallery');
            $galleryData['image'] = $this->url.Storage::url($imagePath);
        }

        $gallery->update($galleryData);

        return new GalleryResource($gallery);
    }

    public function delete($id)
    {
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $gallery = Gallery::find($id);
        if (! $gallery) {
            return $this->resDataNotFound('Gallery');
        }

        $gallery->delete();

        return $this->resDataDeleted('Gallery');
    }
}
