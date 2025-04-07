<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'boq',
        'po_limit',
        'multiple_po_approval',
        'leave_request_limit',
        'multiple_approval',
        'multiple_k3_approval',
        'multiple_mom_approval',
        'multiple_pr_approval',
        'multiple_item_approval',
        'multiple_wbs_revision_approval',
        'multiple_wbs_approval',
    ];

}
