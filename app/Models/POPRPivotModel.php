<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class POPRPivotModel extends Model
{
    use HasFactory;

    protected $table = 'po_pr_pivot';

    protected $fillable = [
        'po_id',
        'pr_id',
    ];
}
