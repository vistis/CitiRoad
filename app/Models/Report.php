<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'reports';
    protected $primaryKey = 'id';
    protected $fillable = [
        'title',
        'status',
        'province_id',
        'address',
        'description',
        'citizen_id',
        'updated_by',
        'remark',
    ];
}
