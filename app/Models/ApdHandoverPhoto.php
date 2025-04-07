<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApdHandoverPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'apd_handover_id',
        'photo',
    ];

    public function apdHandover()
    {
        return $this->belongsTo(ApdHandover::class);
    }
}
