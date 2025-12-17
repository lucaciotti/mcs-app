<?php

namespace App\Http\Livewire\Inventory\Statsimpledetailed;

use App\Models\InventorySimple;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\PlannedTask;
use App\Models\PlanTypeAttribute;
use DateInterval;
use DatePeriod;
use DateTime;
use DatetimeHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Laratrust;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\TextFilter;
use Session;

use function PHPUnit\Framework\isInstanceOf;

class StatSimpleDetailedTable extends DataTableComponent
{
    protected $model = InventorySimple::class;

    public $invSession_id;
    public $stock_year;
    public $order;
    public $perc_delta;

    protected function getListeners()
    {
        return [
            'clearSelected' => 'clearSelected',
        ];
    }

    public function mount()
    {
        // $this->setFilter('date_prod_from', date('Y-m-d', strtotime('-' . date('w') . ' days')));

    }
    
    public function builder(): Builder
    {
        $this->invSession_id = Session::get('inventory.session.id');
        $this->stock_year = intval(Session::get('products.stock.year'));
        $this->order = Session::get('inventory.stat.order');
        $this->perc_delta = Session::get('inventory.stat.perc_delta') ?? 1;
        // return InventorySimple::query()
        //     ->where('inventory_session_id', $this->invSession_id);

        
        $query =  InventorySimple::query()->selectRaw('MAX(id) as id')->select('inventory_simples.product_id');
        $query = $query->selectRaw('SUM(qty) as qta_inv');
        $query = $query->selectRaw('IF(MAX(product_stocks.stock)>0, MAX(product_stocks.stock),0) as qta_giac');
        $query = $query->selectRaw('SUM(qty)-IF(MAX(product_stocks.stock)>0, MAX(product_stocks.stock),0) as delta');
        $query = $query->selectRaw('SUM(qty)*products.cost as cost_inv');
        $query = $query->selectRaw('(SUM(qty)-IF(MAX(product_stocks.stock)>0, MAX(product_stocks.stock),0))*products.cost as cost_delta');
        $query = $query->leftJoin('product_stocks', function ($join) {
            $join->on('product_stocks.product_id', '=', 'inventory_simples.product_id');
            $join->where('product_stocks.year', '=', $this->stock_year);
        });
        $query = $query->leftJoin('products', function ($join) {
            $join->on('products.id', '=', 'inventory_simples.product_id');
        });
        // $query = $query->selectRaw('SUM(qty) as qty_inv');
        $query = $query->where('inventory_session_id', $this->invSession_id)->groupBy('product_id');
        if($this->order=='product_id'){
            $query = $query->orderBy($this->order);
        } else {
            $query = $query->orderBy($this->order, 'desc');
        }
        // $query = $query->orderBy('product_id');
        // dd($query->get());
        return $query;
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            // ->setDebugEnabled()
            ->setPerPageAccepted([25, 50, 75, 100])
            ->setPerPage(25)
            ->setSecondaryHeaderEnabled()
            ->setOfflineIndicatorEnabled()
            ->setFilterLayoutSlideDown()
            ->setHideBulkActionsWhenEmptyEnabled()
            // ->setTrAttributes(function ($row, $index) {
            //     if ($row->delta == 'delta') {
            //         return [
            //             'class' => 'text-bold',
            //             'style' => 'background-color: lightgrey;',
            //         ];
            //     }

            //     return [];
            // })
            ->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
                if ($column->getTitle() == '') {
                    return [
                        'default' => false,
                        // 'class' => 'w-5',
                        'style' => 'width:30%;'
                    ];
                }
                if ($column->getTitle() == 'Prodotto') {
                    return [
                        'class' => 'text-bold',
                    ];
                }
                if ($column->getTitle() == 'Qta Delta') {
                    if(abs($row['delta']) > ($row['qta_giac']*($this->perc_delta/100))){
                        return [
                            'class' => 'bg-warning',
                        ];
                    }
                }
                return [];
            });
    }


    public function columns(): array
    {
        return [
            Column::make("Prodotto", "product.code")
                ->searchable(),
            Column::make("Descr. Prodotto", "product.description")
                ->searchable(),
            Column::make("Costo Prodotto", "product.cost")
                ->searchable(),
            Column::make("U.M.", "product.unit")
                ->searchable(),
            Column::make("Qta Inv.")
                ->label(
                    fn ($row, Column $column) =>  $row['qta_inv']
                )->html()
                ->searchable(),
            Column::make("Qta Giac.")
                ->label(
                    fn ($row, Column $column) =>  $row['qta_giac'] ?? 0
                )->html()
                ->searchable(),
            Column::make("Qta Delta")
                ->label(
                    fn ($row, Column $column) =>  $row['delta'] ?? 0
                )->html()
                ->searchable(),
            Column::make("Costo Qta Inv.")
                ->label(
                    fn ($row, Column $column) =>  money($row['cost_inv'] ?? 0)
                )->html()
                ->searchable(),
            Column::make("Costo Qta Delta")
                ->label(
                    fn ($row, Column $column) =>  money($row['cost_delta'] ?? 0)
                )->html()
                ->searchable(),
        ];
        // $columns = [];
        // array_push(
        //     $columns,
        //     Column::make("Prodotto", 'product.code')
        //         ->label(
        //             fn ($row, Column $column) =>  '<strong>'.$row['modello'].'</strong>'
        //         )->html()
        //         ->sortable(),
        // );
        // foreach ($this->aDateOfWeeks as $date) {
        //     if (!is_object($date)) {
        //         $date = new DateTime($date['date']);
        //     }
        //     array_push(
        //         $columns,
        //         Column::make(
        //             Carbon::instance($date->modify('next Friday'))->currentOrNextBusinessDay()->format('d/m/Y').' [w_'. ($date->format('W')-1) .']')
        //             ->label(
        //             function ($row) use($date) {
        //                 return $row['w_' . $date->format('W')];
        //             })
        //             ->sortable()
        //     );
        // }
        // return $columns;
    }

    // public function filters(): array
    // {
    //     return [
    //         DateFilter::make('Data Prod. [>=]', 'date_prod_from')
    //         ->config([
    //             'half-space' => true,
    //         ])
    //             ->filter(function (Builder $builder, string $value) {
    //                 $builder->where('ibp_data_inizio_prod', '>=', $value);
    //             }),

    //         DateFilter::make('Data Prod. [<=]', 'date_prod_to')
    //         ->config([
    //             'half-space' => true,
    //         ])
    //             ->filter(function (Builder $builder, string $value) {
    //                 $builder->where('ibp_data_inizio_prod', '<=', $value);
    //             }),

    //         DateFilter::make('Data Consegna [>=]', 'date_from')
    //         ->config([
    //             'half-space' => true,
    //         ])
    //             ->filter(function (Builder $builder, string $value) {
    //                 $builder->where('ibp_data_consegna', '>=', $value);
    //             }),

    //         DateFilter::make('Data Consegna [<=]', 'date_to')
    //         ->config([
    //             'half-space' => true,
    //         ])
    //             ->filter(function (Builder $builder, string $value) {
    //                 $builder->where('ibp_data_consegna', '<=', $value);
    //             }),

    //         SelectFilter::make('Seleziona Mese', $this->month)
    //         // ->config([
    //         //     'position' => 'bottom',
    //         // ])
    //             ->options([
    //                 'january' => 'Gennaio',
    //                 'february' => 'Febbraio',
    //                 'march' => 'Marzo',
    //                 'april' => 'Aprile',
    //                 'may' => 'Maggio',
    //                 'june' => 'Giugno',
    //                 'july' => 'Luglio',
    //                 'august' => 'Agosto',
    //                 'semptemper' => 'Settembre',
    //                 'october' => 'Ottobre',
    //                 'november' => 'Novembre',
    //                 'december' => 'Dicembre',
    //             ])
    //             ->filter(function (Builder $builder, string $value) {
    //                 $this->month = $value;
    //             }),

    //         DateFilter::make('Data Completato [>=]', 'date_complete_from')
    //         ->config([
    //             'position' => 'bottom',
    //             'half-space' => true,
    //         ])
    //             ->filter(function (Builder $builder, string $value) {
    //                 $builder->where('completed_date', '>=', $value);
    //             }),

    //         DateFilter::make('Data Completato [<=]', 'date_complete_to')
    //         ->config([
    //             'position' => 'bottom',
    //             'half-space' => true,
    //         ])
    //             ->filter(function (Builder $builder, string $value) {
    //                 $builder->where('completed_date', '<=', $value);
    //             }),


    //     ];
    // }
}
