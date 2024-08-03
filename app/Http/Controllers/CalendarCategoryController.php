<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalendarCategoryCategoryRequest;
use App\Http\Requests\CalendarCategoryRequest;
use App\Http\Resources\CalendarCategoryResource;
use App\Models\CalendarCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CalendarCategoryController extends Controller
{
    public function store(CalendarCategoryRequest $request)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $calendarCategoryData = $request->all();
        do {
            $calendarCategoryData['id'] = 'calendar-category-'.Str::uuid();
        } while (CalendarCategory::where('id', $calendarCategoryData['id'])->exists());

        $calendarCategory = CalendarCategory::create($calendarCategoryData);
        $calendarCategory = new CalendarCategoryResource($calendarCategory);

        return $this->resStoreData($calendarCategory);
    }

    public function index()
    {
        return CalendarCategoryResource::collection(CalendarCategory::all());
    }

    public function show($id)
    {
        $calendarCategory = CalendarCategory::find($id);
        if (! $calendarCategory) {
            return $this->resDataNotFound('CalendarCategory');
        }

        return new CalendarCategoryResource($calendarCategory);
    }

    public function update(CalendarCategoryRequest $request, $id)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $calendarCategory = CalendarCategory::find($id);
        if (! $calendarCategory) {
            return $this->resDataNotFound('CalendarCategory');
        }

        $calendarCategory->update($request->all());
        $calendarCategory = new CalendarCategoryResource($calendarCategory);

        return $this->resStoreData($calendarCategory);
    }

    public function destroy($id)
    {
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $calendarCategory = CalendarCategory::find($id);
        if (! $calendarCategory) {
            return $this->resDataNotFound('CalendarCategory');
        }

        $calendarCategory->delete();

        return $this->resDataDeleted('CalendarCategory berhasil dihapus');
    }
}
