@extends('layouts.app')

@section('title', 'Inventario')

@section('content_header')
<h1>Statistica Prodotti in giacenza - inventariati</h1>
@stop

@section('content-fluid')
@if ($invSession)
<livewire:inventory.statsimpledetailedgiac.content />
@endif
@stop

@push('js')
<script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v0.x.x/dist/livewire-sortable.js"></script>
@endpush

@section('plugins.TempusDominusBs4', true)