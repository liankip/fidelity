<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hiradc extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user_created()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function user_checked()
    {
        return $this->belongsTo(User::class, 'checked_by');
    }
    public function user_approved()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
