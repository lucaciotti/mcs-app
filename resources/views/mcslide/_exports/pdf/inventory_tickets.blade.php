@extends('mcslide._exports.pdf._masterPage.masterPdf')

@section('pdf-main')
<style>
    table,
    th,
    td {
        border: 2px solid #090909;
        border-collapse: collapse;
        text-align: center;
        border-style: groove;
        height: 25px;
        vertical-align: middle;
    }
</style>

@php
$firstPage=true;
// $chunkTasks = array_chunk($tasks, 5);
@endphp

{{-- @foreach ($chunkTasks as $tasks)
<p class="page"> --}}
    {{-- @if ($firstPage)
        <div class="row" style="text-align: center">
            <h1>Pianificazione {{ $planName }}</h1>
            <h2>Periodo Produzione: {{ $dtMin }} - {{ $dtMax }}</h2>
            <br>
            <h2 style="text-align: right;"><i><u>Totale Macchine:</u></i> {{ $total_tasks }}</h2>
            <hr>
        </div>
        @php
        $firstPage=false;
        @endphp
    @endif --}}

    {{-- @foreach ($tasks as $task) --}}
    @for ($i = 1; $i <= 6; $i++)
    
        <div class="row element-that-contains-table" style="padding-top: 5pt;">
            <table style="font-size: medium; font-weight:600;">
                <col width='50%'>
                <col width='20%'>
                <col width='30%'>
                <tr style="height:40px">
                    <th rowspan="2"><?php echo DNS1D::getBarcodeSVG('2312001'.str_pad($i, 6, '0', STR_PAD_LEFT).'', 'C39+', 1.5, 60, 'black', true); ?></th>
                    <th>n° <?php echo str_pad($i, 6, '0', STR_PAD_LEFT); ?></th>
                    <th>Inventario: 12/2023</th>
                </tr>
                <tr style="height:40px">
                    <th><?php echo (new DateTime())->format('d/m/Y');?></th>
                    <th><canvas id="canvas1" width="10" height="10" style="border:3px solid #000000;"></canvas> &nbsp;INVENTARIATO</th>
                </tr>
                <tr style="height:60px">
                    <td colspan="3" style="font-size: large; text-align: left;">
                        Referente Conteggio: &nbsp;&nbsp;____________________________
                    </td>
                </tr>
                <tr style="height:120px">
                    <td colspan="2" style="font-size: large; text-align: left;">Cod. Articolo:&nbsp;&nbsp; ___________________________________ <br><br><br>Qta:&nbsp;&nbsp; _____________________ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  UM: ______</td>
                    <td>Mag: 00001 <br><br>Ubicazione: <br>______________</td>
                </tr>
            </table>
            
            <div>
                <br>
                <hr class="dividerPage">
                <br>
            </div>
        </div>
        
    @endfor
    {{-- @endforeach --}}

{{-- </p>
@endforeach --}}

@endsection