<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistService extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'vehicle_no',
        'vehicle_name',
        'service_type',
        'monthly_service',
        'file_upload',
        'arranged_by',
        'approved_by'
    ];
}
