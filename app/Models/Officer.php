<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Officer extends Authenticatable
{
    public $incrementing = false;

    protected $table = 'officers';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'password',
        'role',
        'province_id',
        'profile_picture_path',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
