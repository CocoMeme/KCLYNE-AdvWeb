<?php

namespace App\Exports;

use App\Models\Service;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ServiceExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Service::select([
            'service_name',
            'description',
            'price',
            'service_image',
        ])->get();
    }

    public function headings(): array
    {
        return [
            'service_name',
            'description',
            'price',
            'service_image',
        ];
    }
}
