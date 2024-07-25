<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Customer([
            'user_id' => $row['user_id'],
            'name' => $row['name'],
            'email' => $row['email'],
            'image' => $row['image'],
            'phone' => $row['phone'],
            'status' => $row['status'],
            'address' => $row['address'],
        ]);
    }
}

