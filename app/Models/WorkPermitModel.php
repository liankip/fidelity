<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkPermitModel extends Model
{
    use HasFactory;

    protected $table= 'work_permit';

    protected $fillable=[
        'document_name',
        'file_upload',
        'updated_by'
    ];
}
