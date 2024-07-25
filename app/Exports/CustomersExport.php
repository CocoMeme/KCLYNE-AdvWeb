<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomersExport implements FromCollection
{

    public function collection()
    {
        return Customer::select([
            'user_id',
            'name',
            'email',
            'image',
            'phone',
            'status',
            'address',
        ])->get();
    }

    public function headings(): array
    {
        return [
            'user_id',
            'name',
            'email',
            'image',
            'phone',
            'status',
            'address',
        ];
    }
}

