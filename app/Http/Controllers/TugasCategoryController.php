<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TugasCategoryController extends Controller
{
    public function index()
    {
        return TugasCategoryResource::collection(TugasCategory::all());
    }
}
