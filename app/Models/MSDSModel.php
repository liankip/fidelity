<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MSDSModel extends Model
{
    use HasFactory;

    protected $table='msds';

    protected $fillable=[
        'document_name',
        'file_upload',
        'updated_by'
    ];
}
