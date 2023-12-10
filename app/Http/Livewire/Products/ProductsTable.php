<?php

namespace App\Http\Livewire\Products;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Product;

class ProductsTable extends DataTableComponent
{
    protected $model = Product::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Codice", "code")
                ->sortable(),
            Column::make("Descrizione", "description")
                ->sortable(),
            Column::make("UM", "unit")
                ->sortable(),
            Column::make("Data Creazione", "created_at")
                ->sortable(),
        ];
    }
}
