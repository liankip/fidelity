<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskEngineerDrawing extends Model
{
    use HasFactory;
    protected $table = 'task_engineer_drawing';
    protected $fillable = [
        'task_id',
        'file',
        'original_filename',
        'status_uploaded',
        'description',
        'section'
    ];
}
