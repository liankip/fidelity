<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;


class ItemApprovedExport implements FromCollection, WithHeadings, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    private $items;

    public function __construct($items)
    {
        $this->items = $items;
    }

    public function collection()
    {
        $data = new Collection();

        foreach ($this->items as $item) {
            $data->push([
                'category' => $item->category->name,
                'item_code' => $item->item_code,
                'name' => $item->name,
                'type' => $item->type,
                'unit' => $item->unit,
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Kategori Barang',
            'Kode Barang',
            'Nama Barang',
            'Jenis Barang',
            'Satuan',
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
