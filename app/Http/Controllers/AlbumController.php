<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlbumRequest;
use App\Http\Resources\AlbumResource;
use App\Models\Album;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AlbumController extends Controller
{
    public $url;
    public function __construct()
    {
        $this->url = Config::get('url.hosting');
    }
    public function store(AlbumRequest $request)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $albumData = $request->all();
        do {
            $albumData['id'] = 'album-'.Str::uuid();
        } while (Album::where('id', $albumData['id'])->exists());

        if ($request->hasFile('cover')) {
            $imagePath = $request->file('cover')->store('public');
            $albumData['cover'] = $this->url.Storage::url($imagePath);
        }

        $album = Album::create($albumData);
        $album = new AlbumResource($album);

        return $this->resStoreData($album);
    }

    public function index()
    {
        return AlbumResource::collection(Album::orderBy('created_at', 'desc')->get());
    }

    public function showById($id)
    {
        $album = Album::with('gallery')->find($id);
        if (! $album) {
            return $this->resDataNotFound('Album');
        }

        return new AlbumResource($album);
    }

    public function update(AlbumRequest $request, $id)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $album = Album::with('gallery')->find($id);
        if (! $album) {
            return $this->resDataNotFound('Album');
        }

        if ($request->hasFile('cover')) {
            $imagePath = $request->file('cover')->store('public');
            $albumData['cover'] = $this->url.Storage::url($imagePath);
        }

        $albumData = $request->all();
        $album->update($albumData);

        return new AlbumResource($album);
    }

    public function delete($id)
    {
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $album = Album::find($id);
        if (! $album) {
            return $this->resDataNotFound('Album');
        }

        $album->delete();

        return $this->resDataDeleted('Album');
    }
}
