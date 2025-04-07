<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BOQAccess extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'b_o_q_id',
        'user_id',
        'action',
        'project_id',
        'status'
    ];


    public function getAccess($projectId)
    {
        return $this->where('user_id', auth()->user()->id)->where('project_id', $projectId)->where('status', 'approved')->first();
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
