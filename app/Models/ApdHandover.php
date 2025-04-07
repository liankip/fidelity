<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApdHandover extends Model
{
    use HasFactory;

    protected $fillable = [
        'apd_request_id',
        'receiver_id',
        'handover_by',
        'date',
        'attachment',
        'description',
    ];

    public function apdRequest()
    {
        return $this->belongsTo(ApdRequest::class);
    }

    public function apdHandoverPhoto()
    {
        return $this->hasMany(ApdHandoverPhoto::class);
    }
}
