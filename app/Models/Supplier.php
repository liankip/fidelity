<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'suppliers';
    protected $fillable = [
        'name',
        'pic',
        'term_of_payment',
        'email',
        'phone',
        'address',
        'city',
        'province',
        'post_code',
        'created_by',
        'ktp_image',
        'npwp',
        'norek',
        'bank_name',
        'recommended_by',
        'surveyor_name',
    ];

    public function additionalFiles()
    {
        return $this->hasMany(SupplierAdditionalFile::class, 'supplier_id', 'id');
    }
}
