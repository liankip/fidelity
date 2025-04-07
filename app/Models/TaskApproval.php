<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskApproval extends Model
{
    use HasFactory;

    protected $table = 'task_approval';
    protected $fillable = [
        'project_id',
        'file_path',
        'status',
        'approved_by_user_1',
        'approved_date_user_1',
        'approved_by_user_2',
        'approved_date_user_2',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
