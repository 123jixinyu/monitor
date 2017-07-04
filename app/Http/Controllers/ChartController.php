<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ChartController extends Controller
{
    public function index(Request $request)
    {
        return view('charts.index');
    }
}
