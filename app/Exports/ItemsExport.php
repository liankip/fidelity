<?php

namespace App\Exports;

use App\Models\Item;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class ItemsExport implements FromCollection, WithHeadings, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $items = Item::available()->get();
        $collections = new Collection();

        foreach ($items as $item) {
            $collections->push([
                'category' => $item->category ? $item->category->name : 'NA',
                'item_code' => $item->item_code,
                'name' => $item->name,
                'type' => $item->type,
                'unit' => $item->unit,
            ]);
        }

        return $collections;
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
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
