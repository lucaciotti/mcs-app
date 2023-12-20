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
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class InventorySimpleExport implements FromArray, WithMapping, WithHeadings, ShouldAutoSize, WithStyles
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
        $aUbi = [];
        $allRows = InventorySimple::whereIn('id', $this->invIds)->with('product')->get();
        foreach ($allRows as $row) {
            $idProd = $row->product_id;
            $codProd = $row->product->code;
            $descr = $row->product->description;
            $um = $row->product->unit;
            $ubi = $row->ubication;
            if(!in_array($idProd, $aProducts)){
                $totQta = InventorySimple::where('product_id', $idProd)->where('ubication', $ubi)->sum('qty');
                array_push($rows, [$codProd, $descr, $ubi, $um, $totQta]);
                array_push($aProducts, $idProd);
                array_push($aUbi, $ubi);
            } else {
                if(!in_array($ubi, $aUbi)) {
                    $totQta = InventorySimple::where('product_id', $idProd)->where('ubication', $ubi)->sum('qty');
                    array_push($rows, [$codProd, $descr, $ubi, $um, $totQta]);
                    array_push($aUbi, $ubi);                    
                }
            }
        }
        return $rows;
    }

    public function headings(): array
    {
        $head = ['Cod.Prodotto', 'Descr.Prodotto', 'Ubicazione', 'UM', 'Qta'];
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


    public function map($row): array
    {
        $body = [$row[0], $row[1], $row[2] ?? '', $row[3], $row[4]];
        return $body;
    }
}
