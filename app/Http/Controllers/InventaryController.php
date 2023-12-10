<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InventaryController extends Controller
{
    public function print_tickets(Request $req)
    {
        return view('mcslide.inventory_tickets');
    }
}
