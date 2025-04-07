<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseRequest extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'purchase_requests';
    protected $fillable = [
        'pr_no',
        'pr_type',
        'project_id',
        'nama_project',
        'warehouse_id',
        'nama_warehouse',
        'requester_phone_number',
        'partof',
        'status',
        'remark',
        'is_task',
        'requester',
        'created_by',
        'approved_by',
        'approved_by_2',
        'updated_by',
        'deleted_by',
        'city',
    ];

    protected $casts = [
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'project_id' => 'integer',
        'warehouse_id' => 'integer'
    ];

    public function purchaseRequestDetails()
    {
        return $this->hasMany(PurchaseRequestDetail::class, 'pr_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, "created_by", "id");
    }

    public function podetail()
    {
        return $this->hasMany(PurchaseRequestDetail::class, "pr_id", "id");
    }

    public function users()
    {
        return $this->belongsToMany(User::class, "purchase_request_user");
    }

    public function prdetail(): HasMany
    {
        return $this->hasMany(PurchaseRequestDetail::class, "pr_id", "id")
            ->where("status", "!=", "Rejected");
    }

    public function po()
    {
        return $this->hasMany(PurchaseOrder::class, "pr_no", "pr_no");
    }

    public function sebagian()
    {
        return $this->whereHas("po");
    }

    public function task()
    {
        return $this->belongsTo(Task::class, 'partof', 'task_number');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    public function approvedBy2()
    {
        return $this->belongsTo(User::class, 'approved_by_2', 'id');
    }
}
