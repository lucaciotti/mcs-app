<?php

namespace App\Exports;

use App\Models\InventorySession;
use App\Models\InventorySimple;
use App\Models\Product;
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

class StockProductInvExport implements FromArray, WithMapping, WithHeadings, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    protected $invIds;
    protected $invSession_id;
    protected $stock_year;
    protected $order;
    protected $show_only_no_inv;
    protected $show_only_inv;

    public function __construct($invIds)
    {
        $this->invIds = $invIds;
        $invSession_id = InventorySimple::whereIn('id', $this->invIds)->first()->inventory_session_id;
        $invSession = InventorySession::find($invSession_id);
        $this->invSession_id = $invSession_id;
        $this->stock_year = $invSession->year;
        $this->order = 'code';
        $this->show_only_no_inv = true;
        $this->show_only_inv = false;
        ini_set('max_execution_time', 0);
    }

    public function array(): array
    {
        $rows = [];

        $query = Product::query()->select('products.id');
        $query = $query->selectRaw('MAX(products.code) as product_code');
        $query = $query->selectRaw('MAX(products.description) as product_description');
        $query = $query->selectRaw('MAX(products.cost) as product_cost');
        $query = $query->selectRaw('MAX(products.unit) as product_unit');
        $query = $query->selectRaw('IF(SUM(inventory_simples.qty), SUM(inventory_simples.qty), 0) as qta_inv');
        $query = $query->selectRaw('IF(MAX(product_stocks.stock)>0, MAX(product_stocks.stock),0) as qta_giac');
        $query = $query->selectRaw('IF(SUM(inventory_simples.qty), SUM(inventory_simples.qty), 0)-IF(MAX(product_stocks.stock)>0, MAX(product_stocks.stock),0) as delta');
        $query = $query->selectRaw('IF(SUM(inventory_simples.qty), SUM(inventory_simples.qty), 0)*MAX(products.cost) as cost_inv');
        $query = $query->selectRaw('(IF(SUM(inventory_simples.qty), SUM(inventory_simples.qty), 0)-IF(MAX(product_stocks.stock)>0, MAX(product_stocks.stock),0))*MAX(products.cost) as cost_delta');
        $query = $query->leftJoin('product_stocks', function ($join) {
            $join->on('product_stocks.product_id', '=', 'products.id');
            $join->where('product_stocks.year', '=', $this->stock_year);
        });
        $query = $query->leftJoin('inventory_simples', function ($join) {
            $join->on('products.id', '=', 'inventory_simples.product_id');
            $join->where('inventory_session_id', $this->invSession_id);
        });
        $query = $query->having('qta_giac', '>', '0');
        if ($this->show_only_no_inv) {
            $query = $query->having('qta_inv', '==', '0');
        }
        if ($this->show_only_inv) {
            $query = $query->having('qta_inv', '>', '0');
        }
        $query = $query->groupBy('products.id');
        // $query = $query->selectRaw('SUM(qty) as qty_inv');
        if ($this->order == 'code') {
            $query = $query->orderBy($this->order);
        } else {
            $query = $query->orderBy($this->order, 'desc');
        }

        $allRows = $query->get();
        foreach ($allRows as $row) {
            $idProd = $row->id;
            $codProd = $row->product_code;
            $descr = $row->product_description;
            $cost = $row->product_cost;
            $um = $row->product_unit;
            $qta_giac = $row->qta_giac;
            $qta_inv = $row->qta_inv;
            array_push($rows, [$codProd, $descr, $um, $qta_giac, $qta_inv, $cost]);
        }
        return $rows;
    }

    public function headings(): array
    {
        $head = ['Cod.Prodotto', 'Descr.Prodotto', 'UM', 'Giac. Prec.', 'Qta Inv', 'Costo'];
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
            'D' => NumberFormat::FORMAT_NUMBER_00,
            'E' => NumberFormat::FORMAT_NUMBER_00,
            'F' => NumberFormat::FORMAT_CURRENCY_EUR,
        ];
    }


    public function map($row): array
    {
        $body = [$row[0], $row[1], $row[2], $row[3], $row[4], $row[5]];
        return $body;
    }
}
