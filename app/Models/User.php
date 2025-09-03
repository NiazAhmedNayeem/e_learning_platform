<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'unique_id',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function student(){
        return $this->hasOne(Student::class, 'user_id', 'id');
    }

    public function expertCategory(){
        return $this->belongsTo(Category::class, 'expertise_category_id', 'id');
    }

    public function getImageShowAttribute()
    {
        
        $default = "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&size=160";

        if (!$this->image || $this->image == "N/A") {
            return $default;
        }

        switch ($this->role) {
            case 'admin':
                return asset('public/upload/admin/' . $this->image);
            case 'teacher':
                return asset('public/upload/teacher/' . $this->image);
            case 'student':
                return asset('public/upload/students/' . $this->image);
            default:
                return $default;
        }
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    
}
