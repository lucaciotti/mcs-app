<?php

namespace App\Http\Livewire\Warehouses;

use App\Models\Warehouse;
use WireElements\Pro\Components\Modal\Modal;

class WarehouseModalEdit extends Modal
{
    public $warehouse;

    public $title;
    public $mode;

    public $code;
    public $description;

    protected $rules = [
        'code' => 'required',
        'description' => 'required',
    ];

    public function mount($id = null)
    {
        if (empty($id)) {
            $this->mode = 'insert';
            $this->title = 'Nuovo Magazzino';
        } else {
            $this->mode = 'edit';
            $this->title = 'Modifica Magazzino [' . $id . ']';
            $this->warehouse = Warehouse::find($id);
            $this->code = $this->warehouse->code;
            $this->description = $this->warehouse->description;
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $validatedData = $this->validate();
        if (empty($this->warehouse)) {
            Warehouse::create($validatedData);
        } else {
            $this->warehouse->update($validatedData);
        }

        // $this->dispatch('refreshDatatable');
        $this->close(
            andEmit: [
                'refreshDatatable'
            ]
        );
    }

    public function render()
    {
        return view('livewire.warehouses.warehouse-modal-edit');
    }


    public static function behavior(): array
    {
        return [
            // Close the modal if the escape key is pressed
            'close-on-escape' => true,
            // Close the modal if someone clicks outside the modal
            'close-on-backdrop-click' => false,
            // Trap the users focus inside the modal (e.g. input autofocus and going back and forth between input fields)
            'trap-focus' => true,
            // Remove all unsaved changes once someone closes the modal
            'remove-state-on-close' => false,
        ];
    }

    public static function attributes(): array
    {
        return [
            // Set the modal size to 2xl, you can choose between:
            // xs, sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl
            'size' => '4xl',
        ];
    }
}
