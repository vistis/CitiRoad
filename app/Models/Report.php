<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'status',
        'province_id',
        'address',
        'description',
        'citizen_id',
        'created_at',
        'updated_at',
        'updated_by',
        'remark',
    ];

    public $timestamps = false;
    protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
];

    public function images()
    {
        return $this->hasMany(ReportImage::class);
    }

    public function beforeImages()
    {
        return $this->hasMany(ReportImage::class)->where('type', 'Before');
    }

    public function afterImages()
    {
        return $this->hasMany(ReportImage::class)->where('type', 'After');
    }

    public function citizen()
    {
        return $this->belongsTo(Citizen::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function officer()
    {
        return $this->belongsTo(Officer::class, 'updated_by');
    }

    public function reportImages()
    {
    return $this->hasMany(ReportImage::class);
    }
    

}
