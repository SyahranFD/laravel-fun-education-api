<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportUser implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::where('role', 'student')
            ->where('is_verified', true)
            ->where('is_graduated', false)
            ->select('full_name', 'email', 'birth', 'address', 'shift', 'gender')
            ->get();
    }
}
