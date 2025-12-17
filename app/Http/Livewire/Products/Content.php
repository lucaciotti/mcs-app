<?php

namespace App\Http\Livewire\Products;

use App\Http\Livewire\Layouts\DynamicContent;
use DateTime;
use Illuminate\Support\Facades\Session;

class Content extends DynamicContent
{
    public $refresh_table;
    public $stock_year;
    public $order_by_stock;
    public $years;

    public $listeners = [
        'dynamic-content.collapse' => 'collapse',
        'dynamic-content.expande' => 'expande',
        'refreshNewPlannedTask' => 'tableHasToBeRefreshed',
        'refreshDatatable' => 'tableRefreshed',
    ];

    public function mount()
    {
        $this->years = range(2022, 2030);
        if (!Session::has('products.stock.year')) {
            $this->stock_year = (new DateTime())->format('Y');
            Session::put('products.stock.year', $this->stock_year);
        }
        $this->stock_year = Session::get('products.stock.year');
        $this->order_by_stock = Session::get('products.stock.order_by_stock');
    }

    public function render()
    {
        return view('livewire.products.content');
    }

    public function updatedStockYear()
    {
        Session::put('products.stock.year', $this->stock_year);
        $this->emit('refreshDatatable');
        $this->emit('clearSelected');
    }

    public function updatedOrderByStock()
    {
        Session::put('products.stock.order_by_stock', $this->order_by_stock);
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
