<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductImportFile;
use App\Models\ProductStock;
use App\Models\User;
use App\Notifications\DefaultMessageNotify;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Log;
use Maatwebsite\Excel\Concerns\RemembersChunkOffset;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithColumnLimit;
use Notification;
use Str;

class ProductsImport implements ToCollection, WithStartRow, SkipsEmptyRows, WithCalculatedFormulas, WithMultipleSheets, SkipsOnError, WithColumnLimit
{
    protected $importedfile;
    protected $rowNum = 1;
    protected $rules = [];

    public function __construct($id)
    {
        $this->importedfile = ProductImportFile::find($id);
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $codProd = $row[0];
            $descr = $row[1];
            $um = $row[2];
            $codMag = $row[3];
            $codUbi = $row[4];
            $year = $row[5];
            $stockQta = $row[6];

            $warehouse = Warehouse::where('code', $codMag)->first();
            if (!$warehouse) {
                $warehouse = Warehouse::create([
                    'code' => $codMag,
                    'description' => 'Mag. ' . $codMag
                ]);
            }
            $ubic = Ubication::where('code', $codUbi)->first();
            if (!$ubic) {
                $ubic=Ubication::create([
                    'code' => $codUbi,
                    'description' => 'Ubi. ' . $codUbi,
                    'warehouse_id' => $warehouse->id
                ]);
            }

            $prod = Product::where('code', $codProd)->first();
            if (!$prod) {
                $prod = Product::create([
                    'code' => $codProd,
                    'description' => $descr,
                    'um' => $um
                ]);
            } else {
                $prod->description = $descr;
                $prod->um = $um;
                $prod->save();
            }

            $stock = ProductStock::where('product_id', $prod->id)->where('ubic_id', $ubic->id)->where('year', $year)->first();
            if(!$stock){
                Stock::create([
                    'product_id' => $prod->id,
                    'ubic_id' => $ubic->id,
                    'year' => $year,
                    'stock' => $stockQta
                ]);
            } else {
                $stock->stock = $stockQta;
                $stock->save();
            }
        }
    }

    public function onError(\Throwable $th)
    {
        report($th);
        #INVIO NOTIFICA
        $notifyUsers = User::whereHas('roles', fn ($query) => $query->where('name', 'admin'))->orWhere('id', $this->importedfile->userCreated()->id)->get();
        foreach ($notifyUsers as $user) {
            Notification::send(
                $user,
                new DefaultMessageNotify(
                    $title = 'File di Import - [' . $this->importedfile->filename . ']!',
                    $body = 'Errore: [' . $th->getMessage() . ']',
                    $link = '#',
                    $level = 'error'
                )
            );
        }
    }

    public function rules(): array
    {
        return $this->rules;
    }

    public function sheets(): array
    {
        return [
            0 => $this
        ];
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function startRow(): int
    {
        return 2;
    }

    public function endColumn(): string
    {
        return 'AZ';
    }
}
