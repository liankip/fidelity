<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectGroup extends Model
{
    protected $fillable = [
        'name',
    ];
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function totalProjectBudget()
    {
        $total = 0;
        $projectsIds = $this->projects->pluck('id');
        $total += Project::whereIn('id', $projectsIds)->sum('value');

        return $total;

    }
}
