<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectDocument extends Model
{
    protected $table = 'project_documents';

    protected $fillable = [
        'project_id',
        'path',
        'uploaded_by',
        'file_name',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'uploaded_by', 'id');
    }
}
