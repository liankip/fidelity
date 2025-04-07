<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BOQSpreadsheetReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'b_o_q_spreadsheet_id',
        'reviewed_by',
        'data',
        'comment',
    ];

    public function getJsonDataAsObjectArray()
    {
        $data = json_decode($this->data, true);
        $objects = [];

        foreach ($data as $key => $item) {
            $objects[] = (object)[
                'item_name' => $item['item_name'],
                'item_id' => $item['item_id'],
                'unit' => $item['unit'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'shipping_cost' => $item['shipping_cost'],
                'notes' => $item['notes']
            ];
        }

        return $objects;
    }

    public function boq()
    {
        return $this->belongsTo(BOQSpreadsheet::class, 'b_o_q_spreadsheet_id');
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
