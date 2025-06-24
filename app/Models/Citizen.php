<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Citizen extends Authenticatable
{
    use HasApiTokens;

    public $incrementing = false;

    protected $table = 'citizens';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'password',
        'province_id',
        'address',
        'date_of_birth',
        'gender',
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
