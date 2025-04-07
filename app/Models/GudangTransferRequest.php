<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangTransferRequest extends Model
{
    use HasFactory;
    protected $guarded = ["id"];
    public function warehousefrom()
    {
        return $this->belongsTo(Warehouse::class,"from","id");
    }
    public function warehouseto()
    {
        return $this->belongsTo(Warehouse::class,"to","id");
    }
    public function user()
    {
        return $this->belongsTo(User::class,"created_by","id");
    }
}
