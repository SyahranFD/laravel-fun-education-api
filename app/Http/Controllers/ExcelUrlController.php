<?php

namespace App\Http\Controllers;

use App\Exports\ExportUser;
use App\Http\Requests\ExcelUrlRequest;
use App\Http\Resources\ExcelUrlResource;
use App\Models\ExcelUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Excel;

class ExcelUrlController extends Controller
{
    protected $excel;

    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }

    public function userExport()
    {
        $this->excel->download(new ExportUser(), 'List Siswa ~ '.now()->format('Y-m-d H:i:s').'.xlsx');

        return $this->excel->download(new ExportUser(), 'List Siswa ~ '.now()->format('Y-m-d H:i:s').'.xlsx');
    }
}
