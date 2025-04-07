<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApdChecklistInspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit',
        'work',
        'inspection_officer',
        'date',
        'attachment',
        'note'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'inspection_officer');
    }
}
