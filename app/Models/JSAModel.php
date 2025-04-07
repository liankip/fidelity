<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JSAModel extends Model
{
    use HasFactory;

    protected $table = 'jsa';

    protected $fillable = [
        'no_jsa',
        'job_no',
        'job_name',
        'position_no',
        'position_name',
        'section_department',
        'superior_position',
        'jsa_date',
        'file_upload',
        'arranged_by',
        'checked_by',
        'approved_by',
        'revision_num',
        'reviewed',
        'suggestion_notes',
        'job_location',
        'details_data',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'arranged_by', 'id');
    }
}
