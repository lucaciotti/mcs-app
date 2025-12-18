<x-layouts.dynamic-content collapsed='{{ $collapsed }}'>
    <x-slot:content>

        @if (!$warehouse_id || !$warehouse_type_id)
            <div class="card">
                <div class="card-body">
                    <x-adminlte-select name="warehouse_id" label="Magazzino" error-key="warehouse_id" wire:model="warehouse_id"
                        style="text-align: center;" class="text-bold">
                        <option value=''><i>Selezione Magazzino</i></option>
                        @foreach ($warehouses as $wh)
                        <option value='{{ $wh->id }}'><strong>{{ $wh->code }}</strong> - {{
                            $wh->description }}</option>
                        @endforeach
                    </x-adminlte-select>
                    <x-adminlte-select name="warehouse_type_id" label="Reparto" error-key="warehouse_type_id"
                        wire:model="warehouse_type_id" style="text-align: center;" class="text-bold">
                        <option value=''><i>Seleziona Reparto</i></option>
                        @foreach ($warehouse_types as $wh_t)
                        <option value='{{ $wh_t->id }}'><strong>{{ $wh_t->code }}</strong> - {{
                            $wh_t->description }}</option>
                        @endforeach
                    </x-adminlte-select>
                </div>
            </div>
        @else
        
            <div class="card">
                
                <div class="card-body">
                    <h6 style="text-align:right;">Magazzino: <strong>{{ $warehouses->where('id', $warehouse_id)->first()->code }}</strong></h6>
                    <h6 style="text-align:right;"> Reparto: <strong>{{ $warehouse_types->where('id', $warehouse_type_id)->first()->code }}</strong></h6>
                    
                    @if (!$product)
                        @if(!$isToogleSearch)
                            <x-adminlte-input name="codProd" label="Codice Prodotto:" placeholder="Codice Prodotto" error-key="codProd" wire:model.lazy="codProd">
                                <x-slot name="appendSlot">
                                    <button class="btn btn-sm btn-outline-primary" type="button" onclick="Livewire.emit('modal.open', 'inventory.measuresimple.cam-barcode-reader')" data-toggle="tooltip"
                                        data-placement="bottom" title="Camera Scan">
                                        <i class="fas fa-fw fa-camera"></i>
                                    </button>
                                </x-slot>
                            </x-adminlte-input>
                            <button class="btn btn-outline-primary btn-lg btn-block" type="button" wire:click="toogleSearch" data-toggle="tooltip"
                                data-placement="bottom" title="Ricerca Libera">
                                <i class="fas fa-fw fa-search"></i>Ricerca Libera
                            </button>
                        @else
                            <x-adminlte-input name="search" label="Ricerca Libera:" placeholder="Ricerca Libera" error-key="search" wire:model="search">
                                <x-slot name="appendSlot">
                                    <button class="btn btn-sm btn-outline-primary" type="button" wire:click="toogleSearch" data-toggle="tooltip"
                                        data-placement="bottom" title="Codice Prodotto">
                                        <i class="fas fa-fw fa-barcode"></i>
                                    </button>
                                </x-slot>
                            </x-adminlte-input>
                            @if(!empty($listProds))
                            <div id='dropdownList' class="navbar-search-results myDropdownDiv">
                                <div class="list-group myDropdownList">
                                    <a href="#" class="list-group-item list-group-item-action" wire:loading wire:target="search">
                                        <div class="d-flex align-items-center text-secondary">
                                            <strong>Caricamento...</strong>
                                            <div class="spinner-border ml-auto" role="status" aria-hidden="true"></div>
                                        </div>
                                    </a>
                                    @if(!empty($listProds))
                                    @foreach($listProds as $i => $product)
                            
                                    <a class="list-group-item list-group-item-action" wire:click="selectedProd('{{ $product['code'] }}')">
                                        <div class="d-flex w-100 justify-content-between">
                                            <p class="mb-1"><b>{{ $product['code'] }}</b><br><small>{{ $product['description'] }}</small></p>
                                        </div>
                                    </a>
                            
                                    @endforeach
                                    @else
                                    <a class="list-group-item">
                                        <div class="search-title">Nessun risultato...
                                        </div>
                                        <div class="search-path"></div>
                                    </a>
                                    @endif
                                </div>
                            </div>
                            @endif
                        @endif
                    @else
                        <x-adminlte-input name="codProd" label="Codice Prodotto:" placeholder="Codice Prodotto" error-key="codProd" wire:model.lazy="codProd" disabled/>
                        <x-adminlte-input name="descrProd" label="Descrizione Prodotto:" placeholder="Descrizione Prodotto"
                            error-key="descrProd" wire:model.lazy="descrProd" disabled />
                        
                        <hr>
                        <x-adminlte-input name="codUbi" label="Codice Ubicazione:" placeholder="Codice Ubicazione" error-key="codUbi"
                            wire:model.lazy="codUbi" />
                        
                        <hr>
                        <x-adminlte-input name="qty" label="Qta Inv:" placeholder="qty" type="number" error-key="qty" wire:model.lazy="qty" inputmode="numeric"
                            class="text-left" min=1 >
                            <x-slot name="prependSlot">
                                <div class="input-group-text bg-dark">
                                    {{ $umProd }}
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                    @endif
                    
                </div>

            </div>

            <div class="card">        
                <div class="card-body">
                        <button type="submit" class="btn btn-primary btn-block" wire:click="save">Salva</button>
                    
                </div>
            </div>
        @endif

    </x-slot:content>

    <x-slot:extraContent>
        <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
                <button type="button" class="btn btn-warning btn-block float-left" wire:click="resetInv">Reset</button>
                <br>
                <br>
                <hr>
                <button type="button" class="btn btn-danger btn-block float-left" wire:click="resetAllInv">Cambia Magazzino/Reparto</button>
            </div>
        </div>        
    </x-slot:extraContent>

</x-layouts.dynamic-content>

@push('css')
<style>
    .myDropdownList {
        max-height: 400px;
        margin-bottom: 5px;
        /* overflow-y: scroll; */
        -webkit-overflow-scrolling: touch;
    }

    .myDropdownDiv {
        position: absolute;
        width: 96%;
        overflow-y: auto;
        /* padding-top: 5px; */
        margin-top: -12px;
        z-index: 10;
        /* background: white; */
        /* border-bottom-left-radius: 10px; */
        /* border-bottom-right-radius: 10px; */
        /* max-height: 200px; */
        /* border: 1px solid gray; */
        /*This is relative to the navbar now*/
        /* left: 0;
            right: 0;
            top: 40px; */
    }

    @media screen and (max-width: 500px) {
        .myDropdownDiv {
            width: auto;
            margin-left: -15px;
        }
    }

    .myDropdownDiv a:link,
    a:visited,
    a:hover,
    a:active {
        color: #000;
    }

    .myDropdownDiv a:hover,
    a:active {
        background-color: lightblue;
        cursor: pointer;
    }
</style>
@endpush