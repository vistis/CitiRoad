<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReportImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'image_path',
        'report_id',
    ];

    public $timestamps = false;

    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}
