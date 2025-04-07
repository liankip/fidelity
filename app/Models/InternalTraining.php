<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternalTraining extends Model
{
    use HasFactory;

    protected $table ='internal_training';

    protected $fillable = [
        'id_no',
        'no_doc',
        'aspect_name',
        'risk_effect',
        'program_plan',
        'plan',
        'realization',
        'notes',
        'file_upload',
        'revision',
        'arranged_by',
        'approved_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'arranged_by', 'id');
    }
}
