<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActualFieldModel extends Model
{
    use HasFactory;
    protected $table='actual_field_inventory';
    protected $fillable=['pr_id','po_detail_id','item_id','qty_actual'];

}
