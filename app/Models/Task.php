<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $guarded = ["id"];
    protected $table = 'tasks';
    protected $fillable = [
        'project_id',
        'task_number',
        'section',
        'task',
        'bobot',
        'earliest_start',
        'start_date',
        'duration',
        'earliest_finish',
        'finish_date',
        'status',
        'approved_by_user_1',
        'approved_date_user_1',
        'approved_by_user_2',
        'approved_date_user_2',
        'deviasi',
        'date_of_completion',
        'comment',
        'revision',
        'revision_by_user_1',
        'revision_date_user_1',
        'revision_by_user_2',
        'revision_date_user_2',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function boqs()
    {
        return $this->hasMany(BOQ::class, 'task_number', 'task_number');
    }

    public function purchaseRequest(): BelongsTo
    {
        return $this->belongsTo(PurchaseRequest::class, 'project_id', 'project_id');
    }
}
