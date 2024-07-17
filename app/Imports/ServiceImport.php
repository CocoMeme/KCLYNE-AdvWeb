<?php

namespace App\Imports;

use App\Models\Service;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ServiceImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $imagePath = $this->handleImageImport($row['service_image']);
        
        return new Service([
            "service_name" => $row['service_name'],
            "description" => $row['description'],
            "price" => $row['price'],
            "service_image" => $row['service_image'],
        ]);
    }

    /**
    * Handle the image import process.
    *
    * @param string $imageBase64
    * @return string|null
    */
    private function handleImageImport($imageBase64)
    {
        if ($imageBase64) {
            // Decode the base64 image
            $image = base64_decode($imageBase64);
            // Generate a unique file name
            $imageName = uniqid() . '.png';
            // Save the image to the storage
            Storage::put('public/images/' . $imageName, $image);
            // Return the path to be saved in the database
            return 'images/' . $imageName;
        }
        
        return null;
    }
}

