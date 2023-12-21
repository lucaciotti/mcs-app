<x-layouts.dynamic-content collapsed='{{ $collapsed }}'>
    <x-slot:content>

        <div class="card">
            
            <div class="card-body">
                
                <x-adminlte-input name="codProd" label="Codice Prodotto:" placeholder="Codice Prodotto" error-key="codProd" wire:model.lazy="codProd"/>
                @if ($product)
                    <x-adminlte-input name="descrProd" label="Descrizione Prodotto:" placeholder="Descrizione Prodotto"
                        error-key="descrProd" wire:model.lazy="descrProd" disabled />
                    
                    <hr>
                    <x-adminlte-input name="codUbi" label="Codice Ubicazione:" placeholder="Codice Ubicazione" error-key="codUbi"
                        wire:model.lazy="codUbi" />
                    
                    <hr>
                    <x-adminlte-input name="qty" label="Qta Inv:" placeholder="qty" type="number" error-key="qty" wire:model.lazy="qty" inputmode="numeric"
                        class="text-right" min=1 max=100>
                        <x-slot name="appendSlot">
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

    </x-slot:content>

    <x-slot:extraContent>
        <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
                <button type="button" class="btn btn-default btn-block float-left" wire:click="resetInv">Reset</button>
            </div>
        </div>
        
    </x-slot:extraContent>

</x-layouts.dynamic-content>