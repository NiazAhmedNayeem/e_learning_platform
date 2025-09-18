<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $fillable = [
        'creator_id', 'title', 'slug', 'description', 'target_role', 'target_course_id', 
        'start_at', 'end_at', 'attachments', 'status', 'image',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'creator_id');
    }

    protected $appends = ['image_show'];
    public function getImageShowAttribute(){

        $default = "https://ui-avatars.com/api/?name=" . urlencode($this->title) . "&size=160";

        if(!$this->image || $this->image == 'N/A'){
            return $default;
        }

        return asset('public/upload/notice/'. $this->image);
    }
}
