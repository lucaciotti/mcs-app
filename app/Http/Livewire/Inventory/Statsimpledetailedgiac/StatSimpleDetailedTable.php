<?php

namespace App\Http\Livewire\Inventory\Statsimpledetailedgiac;

use App\Models\Product;
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
    protected $model = Product::class;

    public $invSession_id;
    public $stock_year;
    public $order;
    public $show_only_no_inv;
    public $show_only_inv;

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
        $this->order = Session::get('inventory.statgiac.order');
        $this->show_only_no_inv = Session::get('inventory.statgiac.show_only_no_inv');
        $this->show_only_inv = Session::get('inventory.statgiac.show_only_inv');
        // return InventorySimple::query()
        //     ->where('inventory_session_id', $this->invSession_id);

        
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
        if($this->show_only_no_inv){
            $query = $query->having('qta_inv', '==', '0');
        }
        if($this->show_only_inv){
            $query = $query->having('qta_inv', '>', '0');
        }
        $query = $query->groupBy('code');
        // $query = $query->selectRaw('SUM(qty) as qty_inv');
        if($this->order== 'code'){
            $query = $query->orderBy($this->order);
        } else {
            $query = $query->orderBy($this->order, 'desc');
        }
        // $query = $query->orderBy('code');
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
                if ($column->getTitle() == 'Qta Inv.') {
                    if(abs($row['qta_inv']) == 0){
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
            // Column::make("Prodotto", "product.code")
            //     ->searchable(),
            // Column::make("Descr. Prodotto", "product.description")
            //     ->searchable(),
            // Column::make("Costo Prodotto", "product.cost")
            //     ->searchable(),
            // Column::make("U.M.", "product.unit")
            //     ->searchable(),
            Column::make("Prodotto")
                ->label(
                    fn ($row, Column $column) =>  $row['product_code']
                )->html()
                ->searchable(),
            Column::make("Descr. Prodotto")
                ->label(
                    fn ($row, Column $column) =>  $row['product_description']
                )->html()
                ->searchable(),
            Column::make("Costo Prodotto")
                ->label(
                    fn ($row, Column $column) =>  $row['product_cost']
                )->html()
                ->searchable(),
            Column::make("U.M.")
                ->label(
                    fn ($row, Column $column) =>  $row['product_unit']
                )->html()
                ->searchable(),
            Column::make("Qta Giac.")
                ->label(
                    fn ($row, Column $column) =>  $row['qta_giac'] ?? 0
                )->html()
                ->searchable(),
            Column::make("Qta Inv.")
                ->label(
                    fn ($row, Column $column) =>  $row['qta_inv']
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
