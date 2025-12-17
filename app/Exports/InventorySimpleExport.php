<?php

namespace App\Exports;

use App\Models\InventorySimple;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class InventorySimpleExport implements FromArray, WithMapping, WithHeadings, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    protected $invIds;

    public function __construct($invIds)
    {
        $this->invIds = $invIds;
    }

    public function array(): array
    {
        $rows = [];
        $aProducts = [];
        $aRefs = [];
        $allRows = InventorySimple::whereIn('id', $this->invIds)->with('product')->get();
        foreach ($allRows as $row) {
            $idProd = $row->product_id;
            $codProd = $row->product->code;
            $descr = $row->product->description;
            $um = $row->product->unit;
            $warehouse_id = $row->warehouse_id;
            $mag = $row->warehouse->code;
            $warehouse_type_id = $row->warehouse_type_id;
            $reparto = $row->warehouseType->code;
            $refRow = $codProd . '-' . $mag . '-' . $reparto;
            $cost = $row->product->cost;
            $giac = $row->product->stocks()->where('year', 2025)->first()->stock ?? 0;
            if(!in_array($idProd, $aProducts)){
                $totQta = InventorySimple::whereIn('id', $this->invIds)->where('product_id', $idProd)->where('warehouse_id', $warehouse_id)->where('warehouse_id', $warehouse_type_id)->sum('qty');
                if($totQta>0){
                    array_push($rows, [$codProd, $descr, $mag, $reparto, $um, $totQta, $giac, $cost]);
                    array_push($aProducts, $idProd);
                    array_push($aRefs, $refRow);
                }
            } else {
                if(!in_array($refRow, $aRefs)) {
                    $totQta = InventorySimple::whereIn('id', $this->invIds)->where('product_id', $idProd)->where('warehouse_id', $warehouse_id)->where('warehouse_id', $warehouse_type_id)->sum('qty');
                    if ($totQta > 0) {
                        array_push($rows, [$codProd, $descr, $mag, $reparto, $um, $totQta, $giac, $cost]);
                        array_push($aRefs, $refRow);                    
                    }
                }
            }
        }
        return $rows;
    }

    public function headings(): array
    {
        $head = ['Cod.Prodotto', 'Descr.Prodotto', 'Magazzino', 'Reparto', 'UM', 'Qta Inv', 'Giac. Prec.', 'Costo'];
        return $head;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],

            // // Styling a specific cell by coordinate.
            // 'B2' => ['font' => ['italic' => true]],

            // // Styling an entire column.
            // 'C'  => ['font' => ['size' => 16]],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_NUMBER,
            'G' => NumberFormat::FORMAT_NUMBER,
            'H' => NumberFormat::FORMAT_NUMBER,
        ];
    }


    public function map($row): array
    {
        $body = [strval($row[0]), $row[1], $row[2] ?? '', $row[3], $row[4], $row[5], $row[6], $row[7], $row[8]];
        return $body;
    }
}
