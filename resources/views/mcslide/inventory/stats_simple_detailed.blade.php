@extends('layouts.app')

@section('title', 'Inventario')

@section('content_header')
<h1>Statistica Giacenze Inventario</h1>
@stop

@section('content-fluid')
@if ($invSession)
<livewire:inventory.statsimpledetailed.content />
@endif
@stop

@push('js')
<script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v0.x.x/dist/livewire-sortable.js"></script>
@endpush

@section('plugins.TempusDominusBs4', true)