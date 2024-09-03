<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportUser implements FromQuery, WithHeadings, ShouldAutoSize, WithStyles
{
    use Exportable;

    public function query()
    {
        return User::query()
            ->where('role', 'student')
            ->where('is_verified', true)
            ->where('is_graduated', false)
            ->select('full_name', 'shift', 'email', 'gender', 'birth', 'address')
            ->orderBy('shift', 'asc')
            ->orderBy('full_name', 'asc');
    }

    public function headings(): array
    {
        return [
            'Nama Lengkap',
            'Shift Masuk',
            'Email',
            'Jenis Kelamin',
            'Tempat, Tanggal Lahir',
            'Alamat',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => [
                'font' => [
                    'bold' => true,
                ],
                'borders' => [
                    'bottom' => [
                        'borderStyle' => Border::BORDER_THICK,
                        'color' => [
                            'rgb' => '808080'
                        ]
                    ],
                ],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFE4B5'],
                ],
            ],
        ];
    }
}
