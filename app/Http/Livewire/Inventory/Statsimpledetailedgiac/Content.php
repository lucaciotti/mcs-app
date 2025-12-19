<?php

namespace App\Http\Livewire\Inventory\Statsimpledetailedgiac;

use App\Http\Livewire\Layouts\DynamicContent;
use App\Models\InventorySession;
use Session;
use Carbon\Carbon;
use DateTime;

class Content extends DynamicContent
{
    public $refresh_table;
    public $invsession_id;
    public $invSessions;
    public $years;
    public $stock_year;
    public $order;
    public $orders;
    public $show_only_no_inv;
    public $show_only_inv;

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
            "code" => "Prodotto",
            "qta_inv" => "Qta. Inventariata",
            "qta_giac" => "Qta. Giacenza",
            "delta" => "Delta Qta",
            "cost_delta" => "Delta Costo",
        ];
        if (!Session::has('inventory.statgiac.order')) {
            $this->order = "code";
            Session::put('inventory.statgiac.order', $this->order);
        }
        $this->order = Session::get('inventory.statgiac.order');

        $this->show_only_no_inv = Session::get('inventory.statgiac.show_only_no_inv');
        $this->show_only_inv = Session::get('inventory.statgiac.show_only_inv');
    }

    public function render()
    {
        $this->invSessions= InventorySession::all();
        return view('livewire.inventory.statsimpledetailedgiac.content');
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

    public function updatedShowOnlyNoInv()
    {
        if ($this->show_only_inv == true) $this->show_only_inv = !$this->show_only_no_inv;
        Session::put('inventory.statgiac.show_only_no_inv', $this->show_only_no_inv);
        Session::put('inventory.statgiac.show_only_inv', $this->show_only_inv);
        $this->emit('refreshDatatable');
        $this->emit('clearSelected');
    }

    public function updatedShowOnlyInv()
    {
        if ($this->show_only_no_inv == true) $this->show_only_no_inv = !$this->show_only_inv;
        Session::put('inventory.statgiac.show_only_no_inv', $this->show_only_no_inv);
        Session::put('inventory.statgiac.show_only_inv', $this->show_only_inv);
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
