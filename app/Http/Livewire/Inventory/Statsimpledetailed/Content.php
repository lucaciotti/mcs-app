<?php

namespace App\Http\Livewire\Inventory\Statsimpledetailed;

use App\Http\Livewire\Layouts\DynamicContent;
use App\Models\InventorySession;
use Session;
use Carbon\Carbon;

class Content extends DynamicContent
{
    public $refresh_table;
    public $invsession_id;
    public $invSessions;
    public $years;
    public $stock_year;
    public $order;
    public $orders;
    public $perc_delta;

    public $listeners = [
        'dynamic-content.collapse' => 'collapse',
        'dynamic-content.expande' => 'expande',
        'refreshNewPlannedTask' => 'tableHasToBeRefreshed',
        'refreshDatatable' => 'tableRefreshed',
    ];

    
    public function mount(){
        if (!Session::has('inventory.session.id')) {
            $invSession = InventorySession::where('date_start', '<', Carbon::now())->where('date_end', '>', Carbon::now())->first();
            if(!$invSession){
                $invSession = InventorySession::where('date_start', '<', Carbon::now())->first();
            }
            if($invSession) Session::put('inventory.session.id', $invSession->id);
        }
        $this->invsession_id = Session::get('inventory.session.id');

        $this->years = range(2022, 2030);
        if (!Session::has('products.stock.year')) {
            $this->stock_year = (new DateTime())->format('Y');
            Session::put('products.stock.year', $this->stock_year);
        }
        $this->stock_year = Session::get('products.stock.year');
        
        $this->orders = [
            "product_id" => "Prodotto",
            "qta_inv" => "Qta. Inventariata",
            "qta_giac" => "Qta. Giacenza",
            "delta" => "Delta Qta",
            "cost_delta" => "Delta Costo",
        ];
        if (!Session::has('inventory.stat.order')) {
            $this->order = "product_id";
            Session::put('inventory.stat.order', $this->order);
        }
        $this->order = Session::get('inventory.stat.order');

        if (!Session::has('inventory.stat.perc_delta')) {
            $this->perc_delta = 1;
            Session::put('inventory.stat.perc_delta', $this->perc_delta);
        }
        $this->perc_delta = Session::get('inventory.stat.perc_delta');
    }

    public function render()
    {
        $this->invSessions= InventorySession::all();
        return view('livewire.inventory.statsimpledetailed.content');
    }

    public function updatedInvsessionId(){
        Session::put('inventory.session.id', $this->invsession_id);
        $this->emit('refreshDatatable');
        $this->emit('clearSelected');
    }

    public function updatedStockYear()
    {
        Session::put('products.stock.year', $this->stock_year);
        $this->emit('refreshDatatable');
        $this->emit('clearSelected');
    }

    public function updatedOrder()
    {
        Session::put('inventory.stat.order', $this->order);
        $this->emit('refreshDatatable');
        $this->emit('clearSelected');
    }

    public function updatedPercDelta()
    {
        Session::put('inventory.stat.perc_delta', $this->perc_delta);
        $this->emit('refreshDatatable');
        $this->emit('clearSelected');
    }

    public function tableHasToBeRefreshed()
    {
        $this->refresh_table = true;
    }

    public function tableRefreshed()
    {
        $this->refresh_table = false;
    }
}
