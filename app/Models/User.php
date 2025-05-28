<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes, HasApiTokens;

    const SUPERADMIN_ROLE = 'superadmin';
    const ADMIN_ROLE = 'admin';
    const PATIENT_ROLE = 'patient';
    const PSYCHOLOGIST_ROLE = 'psychologist';

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id', 'id');
    }

    public function psychologist()
    {
        return $this->hasOne(Psychologist::class, 'user_id', 'id');
    }

    public function blog()
    {
        return $this->hasMany(Blog::class, 'author_id', 'id');
    }

    public function screening()
    {
        return $this->hasMany(Screening::class, 'user_id', 'id');
    }

    public function response()
    {
        return $this->hasMany(Response::class, 'user_id', 'id');
    }
}
