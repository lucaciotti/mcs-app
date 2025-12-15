@extends('layouts.app')

@section('title', 'Home')

@section('content_header')
<h1>Ciao,</h1>
    <h6 class="m-0 text-dark">
        Questa è la tua dashbord di <strong>McSlice-Wms</strong>
    </h6>
@stop

@section('content')
<div class="row">

    <div class="col-lg-12 col-12">
        <br><br><br>
{{-- 
        @foreach ($planTiles as $planRow)
            <div class="row">
                @if (count($planRow)==2)
                    @foreach ($planRow as $planTile)
                    <div class="col-lg-6 col-6 ml-auto">
                        <div class="small-box bg-{{ $planTile['color'] }}">
                            <div class="inner">
                                <h3>{{ $planTile['count'] }}</h3>

                                <p>{{ $planTile['title'] }}</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-clipboard-list"></i>
                            </div>
                            <a href="{{ route('planned_tasks', $planTile['id']) }}" class="small-box-footer">Visualizza <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="col-lg-3 col-3 ml-auto">
                    </div>
                    <div class="col-lg-6 col-6 ml-auto">
                        <div class="small-box bg-{{ $planRow[0]['color'] }}">
                            <div class="inner">
                                <h3>{{ $planRow[0]['count'] }}</h3>

                                <p>{{ $planRow[0]['title'] }}</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-clipboard-list"></i>
                            </div>
                            <a href="{{ route('planned_tasks', $planRow[0]['id']) }}" class="small-box-footer">Visualizza <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-3 ml-auto">
                    </div>
                @endif
            </div>
        @endforeach --}}
        
        {{-- <hr> --}}
       @if(Auth::user()->hasPermission('tasks-update'))
    <div class="row ">
        <div class="col-lg-6 col-12 ml-auto">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>Misurazioni</h3>
                    <p>&nbsp;</p>
                </div>
                <div class="icon">
                    <i class="fa fa-barcode"></i>
                </div>
                <a href="{{ route('inventory_measurements_simple') }}" class="small-box-footer">Visualizza <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-6 col-12 ml-auto">
            <!-- small box -->
            <div class="small-box" style="background-color: rgb(222, 190, 132)">
                <div class="inner">
                    <h3>Gestione Inventario</h3>
                    <p>&nbsp;</p>
                </div>
                <div class="icon">
                    <i class="fa fa-tasks"></i>
                </div>
                <a href="{{ route('inventory_stats_simple') }}" class="small-box-footer">Visualizza <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    
    </div>
    @else
    
    @if(Auth::user()->hasPermission('tasks-read'))
    <div class="row ">
        <div class="col-lg-12 col-12 ml-auto">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>Misurazioni</h3>
                    <p>&nbsp;</p>
                </div>
                <div class="icon">
                    <i class="fa fa-barcode"></i>
                </div>
                <a href="{{ route('inventory_measurements_simple') }}" class="small-box-footer">Visualizza <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    
    </div>
    @endif
    
    @endif
        
        <div class="row ">
            {{-- <div class="col-lg-6 col-6 ml-auto">
                <div class="small-box" style="background-color: rgb(222, 190, 132)">
                    <div class="inner">
                        <h3>Stampa Tagliandini</h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-barcode"></i>
                    </div>
                    <a href="{{ route('inventory_tickets') }}" class="small-box-footer">Visualizza <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div> --}}
        </div>

        <div id="reader">
        
        </div>
        
        <!-- Include the HTML5-QRCode Library -->
        <!-- Adjust the path based on where you placed the HTML5-QRCode file -->
        {{-- <script src="{{ asset('assets/html5-qrcode/html5-qrcode.min.js') }}"></script> --}}
        {{-- <script type="text/javascript" src="https://unpkg.com/html5-qrcode"></script> --}}
        
        <script>
            // Initialize the HTML5 QR Code Scanner
                    let html5QRCodeScanner = new Html5QrcodeScanner(
                        // Target element with the ID "reader" and configure settings
                        "reader", {
                            fps: 10, // Frames per second for scanning
                            qrbox: {
                                width: 200, // Width of the scanning box
                                height: 200, // Height of the scanning box
                            },
                        }
                    );
                
                    // Function executed when the scanner successfully reads a QR Code
                    function onScanSuccess(decodedText, decodedResult) {
                        // Redirect to the scanned QR Code link
                        // window.location.href = decodedText;
                        console.log(decodedText);
                        // Clear the scanner area after the action is performed
                        html5QRCodeScanner.clear();
                    }
                
                    // Render the QR Code scanner
                    html5QRCodeScanner.render(onScanSuccess);
        </script>
</div>
</div>
@stop