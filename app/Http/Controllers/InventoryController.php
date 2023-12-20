<?php

namespace App\Http\Controllers;

use App\Exports\InventorySimpleExport;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function confSessions(Request $req)
    {
        return view('mcslide.inventory.sessions');
    }

    public function invTickets(Request $req)
    {
        return view('mcslide.inventory.tickets');
    }

    public function invMeasurements(Request $req)
    {
        return view('mcslide.inventory.measurements');
    }

    public function invStats(Request $req)
    {
        return view('mcslide.inventory.stats');
    }

    public function invMeasurementsSimple(Request $req)
    {
        return view('mcslide.inventory.measurements_simple');
    }

    public function invStatsSimple(Request $req)
    {
        return view('mcslide.inventory.stats_simple');
    }

    public function exportXlsSimple(Request $req)
    {
        // dd();
        $inv_ids = $req->session()->get('invsimple.xlsExport.inv_ids');
        $req->session()->forget('invsimple.xlsExport.inv_ids');
        $filename = 'InvSimple_Export_' . Carbon::now()->format('YmdHis') . '.xlsx';
        return Excel::download(new InventorySimpleExport($inv_ids), $filename);
    }
}
