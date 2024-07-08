<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeeExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Employee::select([
            'first_name',
            'last_name',
            'birth_date',
            'sex',
            'phone',
            'house_no',
            'street',
            'baranggay',
            'city',
            'province',
            'position',
            'payrate_per_hour',
            'employee_image',
        ])->get();
    }

    public function headings(): array
    {
        return [
            'first_name',
            'last_name',
            'birth_date',
            'sex',
            'phone',
            'house_no',
            'street',
            'baranggay',
            'city',
            'province',
            'position',
            'payrate_per_hour',
            'employee_image',
        ];
    }
}
