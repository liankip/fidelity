<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CSMSModel extends Model
{
    use HasFactory;

    protected $table = 'csms';

    protected $fillable = [
        'document_name',
        'file_upload',
        'updated_by'
    ];
}
