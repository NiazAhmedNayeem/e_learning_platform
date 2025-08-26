<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'short_description',
        'long_description',
        'price',
        'discount_price',
        'prerequisite',
        'status',
    ];

    //$course->final_price
    public function getFinalPriceAttribute(){
        return $this->discount_price ?? $this->price;
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function teacher(){
        return $this->belongsTo(User::class, 'teacher_id');
    }


    public function getImageShowAttribute(){
        $default = "https://ui-avatars.com/api/?name=" . urlencode($this->title) . "&size=160";

        if (empty($this->image) || $this->image === "N/A"){
            return $default;
        }
        return asset('public/upload/courses/' . $this->image);
    }

}
