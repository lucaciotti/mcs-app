<?php

namespace App\Http\Livewire\Warehouses;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Warehouse;

class WarehousesTable extends DataTableComponent
{
    protected $model = Warehouse::class;

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
            Column::make("Data Creazione", "created_at")
                ->sortable(),
        ];
    }
}
