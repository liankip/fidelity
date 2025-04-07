<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Project;

class Overtime extends Model
{
    use HasFactory;
    protected $table = 'overtime';

    protected $fillable = [
        'overtime_id',
        'user_id',
        'project_id',
        'overtime_date',
        'start_time',
        'finish_time',
        'overtime_report',
        'est_cost',
        'realization',
        'assigned_by',
        'status',
        'updated_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
