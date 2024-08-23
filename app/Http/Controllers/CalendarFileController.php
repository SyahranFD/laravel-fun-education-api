<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalendarFileRequest;
use App\Http\Resources\CalendarFileResource;
use App\Models\CalendarFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CalendarFileController extends Controller
{
    public function __construct()
    {
        $this->url = Config::get('url.hosting');
    }

    public function store(CalendarFileRequest $request)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $calendarFileData = $request->all();
        do {
            $calendarFileData['id'] = 'calendar-file-'.Str::uuid();
        } while (CalendarFile::where('id', $calendarFileData['id'])->exists());

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('public');
            $calendarFileData['file'] = $this->url.Storage::url($filePath);
        }

        $calendarFile = CalendarFile::create($calendarFileData);
        $calendarFile = new CalendarFileResource($calendarFile);

        return $this->resStoreData($calendarFile);
    }

    public function index()
    {
        return CalendarFileResource::collection(CalendarFile::orderBy('created_at', 'desc')->get());
    }

    public function show($id)
    {
        $calendarFile = CalendarFile::find($id);
        if (! $calendarFile) {
            return $this->resDataNotFound('Calendar File');
        }

        return new CalendarFileResource($calendarFile);
    }

    public function destroy($id)
    {
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $calendarFile = CalendarFile::find($id);
        if (! $calendarFile) {
            return $this->resDataNotFound('Calendar File');
        }

        $calendarFile->delete();

        return $this->resDataDeleted('Calendar File');
    }
}
