<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Header extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Add virtual attributes to JSON
    protected $appends = ['MainImageUrl', 'cvUrl'];

    /**
     * Return /storage path for MainImage
     */
    public function getMainImageUrlAttribute()
    {
        return $this->MainImage
            ? '/storage/' . $this->MainImage
            : null;
    }

    /**
     * Return /storage path for CV
     */
    public function getCvUrlAttribute()
    {
        return $this->cv
            ? '/storage/' . $this->cv
            : null;
    }
}
