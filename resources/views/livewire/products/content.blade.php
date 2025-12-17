<x-layouts.dynamic-content collapsed='{{ $collapsed }}'>
    <x-slot:content>

        <div class="card">
            <div class="card-body">
                <div style="">
                    <x-adminlte-select name="stock_year" label="Anno Giacenza" error-key="stock_year" wire:model="stock_year" style="text-align: center;" class="text-bold">
                        @foreach ($years as $year)
                        <option value='{{ $year }}'>{{$year }}</option>
                        @endforeach
                    </x-adminlte-select>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="order_by_stock" label="Ordine per giacenza" error-key="order_by_stock" wire:model="order_by_stock">
                        <label class="form-check-label" for="order_by_stock">Ordina per Giacenza</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Lista prodotti</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" title="Font Size Smaller" id="card-text-size">
                        <i class="fas fa-text-height fa-xs"></i>
                    </button>
                    <button type="button" class="btn btn-tool" title="Toggle fullscreen" id="card-fullscreen">
                        <i class="fas fa-expand-alt"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>

            <div class="card-body">
                @if ($refresh_table)
                <div class="text-danger text-bold text-center pt-2 pb-2"
                    style="background-color: lightgrey; font-size:medium;">
                    Attenzione: Nuove Pianificazioni Importate!
                    <br>
                    <button class="btn btn-sm btn-outline-danger" onclick="Livewire.emit('refreshDatatable');">Aggiorna la tabella</button>
                </div>
                @endif
                <div style="">
                    <livewire:products.products-table />
                </div>
            </div>
        </div>
    </x-slot:content>

    <x-slot:extraContent>
        <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
                {{-- @permission('xlsimport-create') --}}
                <button class="btn btn-outline-warning btn-block"
                    onclick="Livewire.emit('modal.open', 'products-import-file.imports-modal-edit');">
                    <span class="fa fa-file-import"></span> Importa Prodotti/Stock
                </button>
                {{-- @endpermission --}}
            </div>
        </div>
    </x-slot:extraContent>

</x-layouts.dynamic-content>