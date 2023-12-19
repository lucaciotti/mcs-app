<?php

namespace App\Http\Controllers;

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
}
