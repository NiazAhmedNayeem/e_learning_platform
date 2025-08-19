<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'class',
        'section',
        'dob',
        'gender',
        'age',
        'pre_school',
        'pre_class',
        'pre_section',
        'image',
    ];

    public function getImageShowAttribute(){ return $this->image != "N/A" ? asset('public/upload/students/'. $this?->image) : asset('public/upload/default.jpg'); }

    // public function getImageShowAttribute() {
    // return $this->image != "" 
    //     ? asset('public/upload/students/' . $this->image) 
    //     : asset('public/upload/default.jpg');
    // }
}
