<x-layouts.dynamic-content collapsed='{{ $collapsed }}'>
    <x-slot:content>

        <div class="card">
            <div class="card-body">
                <div style="">
                    <x-adminlte-select name="invsession_id" label="Sessioni Inventario" error-key="invsession_id"
                        wire:model="invsession_id" style="text-align: center;" class="text-bold">
                        @foreach ($invSessions as $invSession)
                        <option value='{{ $invSession->id }}'>{{$invSession->description }} [<strong>{{
                                $invSession->month }} / {{ $invSession->year }}</strong>]</option>
                        @endforeach
                    </x-adminlte-select>
                    <x-adminlte-select name="stock_year" label="Anno Giacenza" error-key="stock_year" wire:model="stock_year"
                        style="text-align: center;" class="text-bold">
                        @foreach ($years as $year)
                        <option value='{{ $year }}'>{{$year }}</option>
                        @endforeach
                    </x-adminlte-select>
                </div>
            </div>
        </div>

        <div class="card">
            {{-- <div class="card-header">
                <h3 class="card-title">Ordinamento</h3>
            </div> --}}
            <div class="card-body">
                <div class="row">
                    <x-adminlte-select name="order" label="Ordinamento" error-key="order" wire:model="order"
                        style="text-align: center;" class="text-bold" fgroup-class="col-lg-6">
                        @foreach ($orders as $key => $value)
                        <option value='{{ $key }}'><strong>{{ $value }}</strong></option>
                        @endforeach
                    </x-adminlte-select>
                    <div class="form-group col-lg-6">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="show_only_no_inv" label="Mostra solo codici NON inventariati"
                            error-key="show_only_no_inv" wire:model="show_only_no_inv">
                            <label class="form-check-label" for="show_only_no_inv">Mostra solo codici NON inventariati</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="show_only_inv" label="Mostra solo codici inventariati"
                                error-key="show_only_inv" wire:model="show_only_inv">
                            <label class="form-check-label" for="show_only_inv">Mostra solo codici inventariati</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Prodotti in giacenza - inventariati</h3>
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
                {{-- @if ($refresh_table)
                <div class="text-danger text-bold text-center pt-2 pb-2"
                    style="background-color: lightgrey; font-size:medium;">
                    Attenzione: Nuove Pianificazioni Importate!
                    <br>
                    <button class="btn btn-sm btn-outline-danger"
                        onclick="Livewire.dispatch('refreshDatatable');">Aggiorna
                        la tabella</button>
                </div>
                @endif --}}
                <div style="">
                    <livewire:inventory.statsimpledetailedgiac.stat-simple-detailed-table />
                </div>
            </div>
        </div>
    </x-slot:content>

    <x-slot:extraContent>
        {{-- <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
                <button class="btn btn-outline-success btn-block"
                    onclick="Livewire.emit('modal.open', 'pdf-reports.generate-reports')">
                    <span class="fa fa-print"></span> Genera PDF Tagliandini
                </button>
            </div>
        </div> --}}
    </x-slot:extraContent>

</x-layouts.dynamic-content>