<?php

namespace App\Exports;

use App\Models\Item;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class BOQTableExport implements FromCollection, WithEvents, WithHeadings
{
    private $boqs;

    public function __construct($boqs)
    {
        $this->boqs = $boqs;
    }

    public function collection()
    {
        $data = new Collection();

        foreach ($this->boqs as $boq) {
            $data->push([
                'item_name' => Item::find($boq[0])?->name,
                'unit' => $boq[1],
                'price' => $boq[2],
                'qty' => $boq[3],
                'shipping_cost' => $boq[4],
                'note' => $boq[5],
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Item Name',
            'Unit',
            'Price',
            'Quantity',
            'Shipping Cost',
            'Notes'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(20);
            },
        ];
    }
}
