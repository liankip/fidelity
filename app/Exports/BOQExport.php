<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class BOQExport implements FromCollection, WithEvents, WithHeadings
{
    private $boqs;

    public function __construct($boqs)
    {
        $this->boqs = $boqs;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {

        $data = new Collection();

        foreach ($this->boqs as $key=>$boq) {
            $itemPrice = DB::table('supplier_item_prices')
                ->where('item_id', $boq->item_id)
                ->where('unit_id', $boq->unit_id)
                ->orderBy('price', 'asc')
                ->first();
            $historyPrice =  DB::table('purchase_order_details')
                ->join('purchase_orders', 'purchase_orders.id', '=', 'purchase_order_details.purchase_order_id')
                ->where('purchase_orders.status', '=', 'Approved')
                ->where('purchase_order_details.item_id', '=', $boq->item_id)
                ->orderBy('purchase_order_details.price', 'asc')
                ->select('purchase_order_details.*')
                ->first();

            $expectedPrice = 0;
            if ((int) $boq->price_estimation === 0) {
                if ($itemPrice !== null) {
                    $expectedPrice = $itemPrice->price;
                } else {
                    $expectedPrice = 0;
                }
            } else {
                $expectedPrice = $boq->price_estimation;
            }

            $note = "";
            $status = "";

            if ($boq->origin) {
                $note .= "Kota Asal: " . $boq->origin;
            }

            if ($boq->destination) {
                $note .= " Kota Tujuan: " . $boq->destination;
            }


            if ($boq->rejected_by != null && $boq->rejected_by != 0) {
                $status = "Rejected";
            } else {
                if ($boq->approved) {
                    $status = "Approved by " . $boq->approved->name;
                } else {
                    $status = "Waiting for approval";
                }
            }

            $formattedPrice = 'Rp. ' . number_format($expectedPrice, 0, ',', '.');
            $shippingCost = 'Rp. ' . number_format($boq->shipping_cost, 0, ',', '.');
            $totalEstimation = 'Rp. ' . number_format($expectedPrice * $boq->qty + $boq->shipping_cost, 0, ',', '.');
            $historyPrice = $historyPrice ? 'Rp. ' . number_format($historyPrice->price, 0, ',', '.') : '-';

            $data->push([
                'no' => $key+1,
                'name' => $boq->item->name,
                'quantity' => $boq->qty,
                'unit' => $boq->unit->name,
                'expected_price' => $formattedPrice,
                'history_price' => $historyPrice,
                'shipping_cost' => $shippingCost,
                'total_estimation' => $totalEstimation,
                'note' => $note . " " . $boq->note,
                'status' => $status,
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'No',
            'Item Name',
            'Quantity',
            'Unit',
            'Expected Price',
            'History Price',
            'Shipping Cost Estimation',
            'Total Estimation',
            'Note',
            'Status',
        ];
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(5);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(18);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(40);
            },
        ];
    }
}
