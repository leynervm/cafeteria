<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AgregadonController extends Controller
{
    public function index()
    {
        return view('admin.agregados.index');
    }
}
