<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordChangeOtp extends Model
{
    protected $fillable = [
        'user_id', 'otp_hash', 'new_password_hash', 'expires_at', 'attempts',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
