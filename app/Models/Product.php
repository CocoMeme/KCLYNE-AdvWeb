<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'name', 'description', 'price','stock_quantity', 'image_path', 'created_at', 'updated_at',
    ];

    public function getImagePathsAttribute()
    {
        return explode(',', $this->image_path);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function toSearchableArray()
    {
        $array = $this->toArray();

        // Customize the array to include only the fields you want to index
        return [
            'name' => $this->name,
            'price' => $this->price,
            'description' => $this->description,
            'stock_quantity' => $this->stock_quantity,
            'image_path' => $this->image_path
        ];
    }
}
