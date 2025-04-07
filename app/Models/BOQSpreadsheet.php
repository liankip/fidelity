<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BOQSpreadsheet extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'capex_expense_id',
        'user_id',
        'data',
        'approved_by',
        'date_approved',
        'rejected_by',
        'date_rejected',
        'approved_by_2',
        'date_approved_2',
        'status',
        'is_closed',
        'task_id',
        'task_number',
        'is_task',
        'comment',
        'save',
    ];

    public function getJsonDataAsObjectArray()
    {
        $objects = [];
        $itemIds = collect(json_decode($this->data))->pluck(0);
        $itemNames = Item::whereIn('id', $itemIds)->pluck('name', 'id')->toArray();

        foreach (json_decode($this->data) as $item) {
            $objects[] = (object)[
                'item_name' => $itemNames[$item[0]],
                'item_id' => $item[0],
                'unit' => $item[1],
                'price' => $item[2],
                'quantity' => $item[3],
                'shipping_cost' => $item[4],
                'notes' => $item[5]
            ];
        }

        return $objects;
    }

    public function approved()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function approved2()
    {
        return $this->belongsTo(User::class, 'approved_by_2');
    }

    public function rejected()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function review()
    {
        return $this->hasOne(BOQSpreadsheetReview::class);
    }

    public function getTotalPrice()
    {
        $total = 0;

        foreach ($this->getJsonDataAsObjectArray() as $item) {
            $total += $item->price * $item->quantity;
        }

        return $total;
    }

    public function wbs()
    {
        return $this->belongsTo(Task::class, 'wbs_id');
    }
}
