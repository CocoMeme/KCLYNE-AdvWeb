<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $imagePath = null;
        if (isset($row['image']) && !empty($row['image'])) {
            $imagePath = $row['image'];
        }

        return new Product([
            "name" => $row['name'],
            "description" => $row['description'],
            "price" => $row['price'],
            "stock_quantity" => $row['stock_quantity'],
            "image" => $imagePath,
        ]);
    }
}
