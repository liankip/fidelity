<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegalDocumentManagement extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $fillable = [
        'nama_dokumen',
        'nomor_dokumen',
        'asal_instansi',
        'file_upload',
        'expired',
        'created_by',
    ];
}
