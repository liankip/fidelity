<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SafetyTalkModel extends Model
{
    use HasFactory;

    protected $table = 'safety_talk';
    protected $fillable = [
        'activity_date',
        'location',
        'job_status',
        'executor',
        'file_upload',
        'updated_by'
    ];
}
