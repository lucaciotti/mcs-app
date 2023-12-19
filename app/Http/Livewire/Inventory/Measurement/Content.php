<?php

namespace App\Http\Livewire\Inventory\Measurement;

use App\Http\Livewire\Layouts\DynamicContent;
use App\Models\InventorySessionTicket;
use App\Models\Product;

class Content extends DynamicContent
{
    public $barcode;
    public $codMag;
    public $codProd;
    public $descrProd;
    public $umProd = 'PZ';
    public $codUbi;
    public $qta;
    public $year;
    public $month;
    public $periodo;
    
    public $invSessionTicket;
    public $invSessionWarehouse;
    public $invMeasurements;
    public $invSession;
    public $product;
    public $ubication;
    public $productStock;

    public function rules(): array
    {
        return
            [
                'year' => ['required', 'numeric'],
                // 'month' => ['required', 'numeric', Rule::unique('inventory_sessions', 'month')->where('year', $this->year),],
                // 'description' => 'required',
                // 'date_start' => 'nullable|date',
                // 'date_end' => 'nullable|date|after:date_start',
                // 'active' => 'required',
            ];
    }

    public function render()
    {
        return view('livewire.inventory.measurement.content');
    }

    public function updated($propertyName)
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->validateOnly($propertyName);
    }

    public function updatedBarcode()
    {
        $this->invSessionTicket = InventorySessionTicket::where('ticket', $this->barcode)->first();
        if(!$this->invSessionTicket){
            $this->addError('barcode', 'Il Barcode NON è valido!');
            return;
        }
        $this->invSessionWarehouse = $this->invSessionTicket->inventorySessionWarehouse;
        $this->invSession = $this->invSessionWarehouse->inventorySession;
        $this->invMeasurements = $this->invSessionTicket->inventoryMeasurement;

        $this->codMag = $this->invSessionWarehouse->warehouse->code;
        $this->year = $this->invSession->year;
        $this->month = $this->invSession->month;
        $this->periodo = $this->month .'/'. $this->year;

        if($this->invMeasurements) {
            $this->addError('barcode', 'Cartellino già Inventariato!');
            $this->product = $this->invMeasurements->product;
            $this->codProd = $this->invMeasurements->product->code;
            $this->descrProd = $this->invMeasurements->product->description;
            $this->umProd = $this->invMeasurements->product->unit;
            $this->codUbi = $this->invMeasurements->ubication->code;
            $this->qta = $this->invMeasurements->qty;
        }
    }

    public function updatedCodProd()
    {
        $this->product = Product::where('code', $this->codProd)->first();
        if (!$this->product) {
            $this->addError('codProd', 'Il Prodotto NON è valido!');
            return;
        }
        $this->descrProd = $this->product->description;
        $this->umProd = $this->product->unit;
    }

    public function updatedCodUbi()
    {
        $this->ubication = Ubication::where('code', $this->codUbi)->first();
        if (!$this->ubication) {
            $this->addError('codUbi', 'L\'Ubicazione NON è valida!');
            return;
        }
    }

    public function save(){

    }

    public function resetInv(){
        $this->reset();
    }

}
