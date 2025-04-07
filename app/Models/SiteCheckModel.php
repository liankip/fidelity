<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteCheckModel extends Model
{
    use HasFactory;

    protected $table = 'site_check_upload';

    protected $fillable = ['project_id', 'pr_id', 'item_id', 'name', 'description', 'file_upload'];
}
