<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyExpensesModel extends Model
{
    use HasFactory;

    protected $table = 'daily_expenses';

    protected $fillable = [
       'name',
       'amount',
       'description',
       'documents'
    ];
}
