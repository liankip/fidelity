<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryOutEditHistoryModel extends Model
{
    use HasFactory;

    protected $table = 'inventory_out_edit_history';

    protected $fillable = [
        'inventory_out_id',
        'prev_out_qty',
        'new_out_qty',
        'prev_user_id',
        'new_user_id',
        'prev_desc',
        'new_desc',
        'edited_by',
    ];
}
