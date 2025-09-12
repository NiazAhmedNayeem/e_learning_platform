<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseVideo extends Model
{
    protected $fillable = ['course_id', 'title', 'video_link', 'is_demo', 'position', 'description', 'status'];
    
    public function course(){
        return $this->belongsTo(Course::class);
    }

}
