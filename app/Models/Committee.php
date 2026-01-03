<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Committee extends Model
{
    protected $fillable = [
        'committee_name',
        'committee_period',
        'committee_images',
    ];

    protected $casts = [
        'committee_images' => 'array',
    ];

    // Accessor to get full image URLs
    public function getImagesAttribute()
    {
        if (!$this->committee_images) {
            return [];
        }

        return array_map(function ($image) {
            return asset('storage/' . $image);
        }, $this->committee_images);
    }
}