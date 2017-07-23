<?php

namespace App\Http\Controllers;

use App\Repository\SystemRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TerminalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $url = $request->root() . ':5000';
        return view('terminal.index')->with([
            'url' => $url
        ]);
    }
}
