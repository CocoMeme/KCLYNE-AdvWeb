<?php

namespace App\Imports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $imagePath = null;
        if (isset($row['employee_image']) && !empty($row['employee_image'])) {
            $imagePath = $row['employee_image'];
        }

        $birthDate = $row['birth_date'];
        if (is_string($birthDate) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $birthDate)) {
            $birthDateFormatted = $birthDate; // Already in 'Y-m-d' format
        } else {
            $birthDateFormatted = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($birthDate)->format('Y-m-d');
        }

        return new Employee([
            "first_name" => $row['first_name'],
            "last_name" => $row['last_name'],
            "birth_date" => $birthDateFormatted,
            "sex" => $row['sex'],
            "phone" => $row['phone'],
            "house_no" => $row['house_no'],
            "street" => $row['street'],
            "baranggay" => $row['baranggay'],
            "city" => $row['city'],
            "province" => $row['province'],
            "position" => $row['position'],
            "payrate_per_hour" => $row['payrate_per_hour'],
            "employee_image" => $imagePath,
        ]);
    }
}
