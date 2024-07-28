<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Service extends Model
{
    use HasFactory, Searchable;

    protected $table = 'services';

    protected $fillable = [
        'service_name',
        'description',
        'price',
        'service_image',
    ];

    public function reviews()
    {
        return $this->hasMany(ServiceReview::class);
    }
    public function toSearchableArray()
    {
        $array = $this->toArray();

        // Customize the array to be indexed by Algolia
        return [
            'service_name' => $this->service_name,
            'description' => $this->description,
            'price' => $this->price,
            'service_image' => $this->service_image
        ];
    }
}
