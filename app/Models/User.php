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
        $default = asset('public/upload/default.jpg');

        if (!$this->image || $this->image == "N/A") {
            return $default;
        }

        switch ($this->role) {
            case 'admin':
                return asset('public/upload/admin/' . $this->image);
            case 'teacher':
                return asset('public/upload/teacher/' . $this->image);
            default:
                return $default;
        }
    }
}
