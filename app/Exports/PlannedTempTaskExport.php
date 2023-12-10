<?php

namespace App\Exports;

use App\Models\PlanFilesTempTask;
use App\Models\PlanImportType;
use App\Models\PlanImportTypeAttribute;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PlannedTempTaskExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithColumnFormatting, WithStyles
{
    protected $taskIds;
    protected $importType;
    protected $typeAttribute;

    public function __construct($taskIds, $import_type_id)
    {
        $this->taskIds = $taskIds;
        $this->importType = PlanImportType::where('id', $import_type_id)->first();
        $this->typeAttribute = PlanImportTypeAttribute::where('import_type_id', $import_type_id)->with(['attribute'])->orderBy('cell_num')->get();
        // dd($this);
    }

    public function query()
    {
        return PlanFilesTempTask::query()->whereIn('id', $this->taskIds);
    }

    public function headings(): array
    {
        $head = [];
        foreach ($this->typeAttribute as $column) {
            array_push($head, $column->attribute->label);
        }
        array_push($head, 'Importati');
        array_push($head, 'Errore');
        array_push($head, 'Tipo Errore');
        return $head;
    }

    public function columnFormats(): array
    {
        $format = [];
        $alphabet = range('A', 'Z');
        $index = 0;
        foreach ($this->typeAttribute as $column) {
            if ($column->attribute->col_type == 'date') {
                $format[$alphabet[$index]] = NumberFormat::FORMAT_DATE_DDMMYYYY;
            }
            $index++;
        }
        return $format;
        // return [
        //     'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        //     'C' => NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE,
        // ];
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
        $body = [];
        foreach ($this->typeAttribute as $column) {
            $colname = $column->attribute->col_name;
            if ($column->attribute->col_type == 'date') {
                try{
                    array_push($body, Date::dateTimeToExcel($row->$colname));
                } catch (\Throwable $th) {
                    dd($row->$colname);
                }
            } else {
                array_push($body, $row->$colname);
            }
        }
        array_push($body, ($row->imported ? 'Importato' : '-'));
        array_push($body, ($row->warning ? 'Errore' : '-'));
        array_push($body, $row->error);
        // Date::dateTimeToExcel($invoice->created_at),
        return $body;
    }
}
