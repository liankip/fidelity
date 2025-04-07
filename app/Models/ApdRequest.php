<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApdRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'attachment',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function apdHandover()
    {
        return $this->hasOne(ApdHandover::class);
    }

    public function apdHandoverPhoto()
    {
        return $this->hasManyThrough(ApdHandoverPhoto::class, ApdHandover::class);
    }

    public function apdHandoverCount()
    {
        return $this->hasOne(ApdHandover::class)->count();
    }
}
