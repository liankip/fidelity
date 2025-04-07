<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ibpr extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function ibpr_list()
    {
        return $this->hasMany(IbprList::class,"ibpr_id");

    }

}
