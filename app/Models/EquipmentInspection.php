<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentInspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit',
        'work',
        'equipment_list',
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
