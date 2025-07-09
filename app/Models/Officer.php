<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Officer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'officer';

    protected $primaryKey = 'id';
    public $incrementing = false;

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

    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function province()
{
    return $this->belongsTo(Province::class);
}

}
