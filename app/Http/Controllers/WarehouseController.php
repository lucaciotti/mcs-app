<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index(Request $req)
    {
        return view('mcslide.warehouses');
    }
}
