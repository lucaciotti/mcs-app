<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function confSessions(Request $req)
    {
        return view('mcslide.inventory.sessions');
    }

    public function print_tickets(Request $req)
    {
        return view('mcslide.inventory_tickets');
    }
}
