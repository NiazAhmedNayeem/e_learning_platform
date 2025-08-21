<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
            'name',
            'status',
            'image',
        ];


    public function getImageShowAttribute()
    { 
        return $this->image != "N/A" ? asset('public/upload/category/'. $this?->image) : asset('public/upload/default.jpg'); 
    }
}
