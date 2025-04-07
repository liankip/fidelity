<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkModel extends Model
{
    use HasFactory;

    protected $table = "links";

    public function task()
    {
        return $this->belongsTo(Task::class, 'source', 'id');
    }
}
