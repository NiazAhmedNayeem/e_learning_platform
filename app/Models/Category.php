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
        $default = "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&size=160";

        if (empty($this->image) || $this->image === "N/A") {
            return $default;
        }

        return asset('public/upload/category/' . $this->image);
    }
}
