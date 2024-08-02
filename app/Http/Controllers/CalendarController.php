<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalendarRequest;
use App\Http\Resources\CalendarResource;
use App\Models\Calendar;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CalendarController extends Controller
{
    public function store(CalendarRequest $request)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $calendarData = $request->all();
        do {
            $calendarData['id'] = 'calendar-'.Str::uuid();
        } while (Calendar::where('id', $calendarData['id'])->exists());

        $calendar = Calendar::create($calendarData);
        $calendar = new CalendarResource($calendar);

        return $this->resStoreData($calendar);
    }

    public function index()
    {
        return CalendarResource::collection(Calendar::all()->orderBy('date', 'asc'));
    }

    public function show($id)
    {
        $calendar = Calendar::find($id);
        if (! $calendar) {
            return $this->resDataNotFound('Calendar');
        }

        return new CalendarResource($calendar);
    }

    public function update(CalendarRequest $request, $id)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $calendar = Calendar::find($id);
        if (! $calendar) {
            return $this->resDataNotFound('Calendar');
        }

        $calendar->update($request->all());
        $calendar = new CalendarResource($calendar);

        return $this->resStoreData($calendar);
    }

    public function destroy($id)
    {
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $calendar = Calendar::find($id);
        if (! $calendar) {
            return $this->resDataNotFound('Calendar');
        }

        $calendar->delete();

        return $this->resDataDeleted('Calendar berhasil dihapus');
    }
}
