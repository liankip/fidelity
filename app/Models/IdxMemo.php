<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdxMemo extends Model
{
    use HasFactory;

    protected $table = 'idx_memo';

    protected $fillable = [
        'idx',
    ];
}
