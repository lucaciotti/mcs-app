<?php

namespace App\Http\Livewire\Inventory\Measuresimple;

use Livewire\Component;
use WireElements\Pro\Components\Modal\Modal;

class CamBarcodeReader extends Modal
{
    public $title;
    public $mode;


    public function mount()
    {
        $this->title = 'Barcode Reader';
    }
    
    public function render()
    {
        return view('livewire.inventory.measuresimple.cam-barcode-reader');
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
