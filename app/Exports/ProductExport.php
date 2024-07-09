<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Product::select([
            'name',
            'description',
            'price',
            'stock_quantity',
            'image_path',
        ])->get();
    }

    public function headings(): array
    {
        return [
            'name',
            'description',
            'price',
            'stock_quantity',
            'image_path',
        ];
    }
}
