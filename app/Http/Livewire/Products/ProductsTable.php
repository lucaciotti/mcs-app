<?php

namespace App\Http\Livewire\Products;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class ProductsTable extends DataTableComponent
{
    protected $model = Product::class;
    public $stock_year;
    public $order_by_stock;

    public function builder(): Builder
    {
        $this->stock_year = (int) Session::get('products.stock.year');
        $this->order_by_stock = Session::get('products.stock.order_by_stock');
        if($this->order_by_stock){
            return Product::query()
                ->leftJoin('product_stocks', function ($join) {
                    $join->on('products.id', '=', 'product_stocks.product_id');
                    $join->where('product_stocks.year', '=', intval($this->stock_year));
                    // $join->on('product_stocks.year', '=', intval($this->stock_year));
                })->orderBy('product_stocks.stock', 'desc');
        } else {
            return Product::query();
        }
    }

    // public function mount()
    // {
    //     // $this->stock_year = (int) Session::get('products.stock.year') ?? 2024;
    //     // dd($this->stock_year);
    //     // $this->stock_year = 202;
    // }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setAdditionalSelects(['products.id as id'])
            ->setPerPage(25)
            ->setPerPageAccepted([25, 50, 75, 100])
            ->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
                if ($column->getTitle() == '') {
                    return [
                        'default' => false,
                        // 'class' => 'w-5',
                        'style' => 'width:30%;'
                    ];
                }
                if ($column->getTitle() == 'Codice') {
                    return [
                        'class' => 'text-bold',
                    ];
                }
                if ($column->getTitle() == "Dt.Modifica") {
                    return [
                        'class' => 'text-bold btn',
                        'onclick' => "Livewire.emit('slide-over.open', 'audits.audits-slide-over', {'ormClass': '" . class_basename(get_class($row)) . "', 'ormId': " . $row->id . "});",
                    ];
                }
                return [];
            });
    }

    public function columns(): array
    {
        return [
            Column::make("Codice", "code")
                ->searchable()
                ->sortable(),
            Column::make("Descrizione", "description")
            ->searchable()
                ->sortable(),
            Column::make("UM", "unit")
                ->searchable()
                ->sortable(),
            Column::make("Costo", "cost")
                ->searchable()
                ->sortable(),
            Column::make("Giacenza")
                ->label(
                    fn($row, Column $column) => $this->getStock($row)
                )
                ->searchable(),
            Column::make("Barcode", "barcode")
            ->searchable()
                ->sortable(),
            Column::make("Dt.Modifica", "updated_at")
                ->format(
                    fn ($value, $row, Column $column) => '<span class="fa fa-history pr-1"></span>' . $value->format('d-m-Y')
                )->html()
                ->sortable(),
        ];
    }

    public function getStock($row)
    {
        // dd($row);
        $this->stock_year = (int) Session::get('products.stock.year') ?? 2024;
        return $row->stocks()->where('year', $this->stock_year)->first()->stock ?? 0;
    }
}
