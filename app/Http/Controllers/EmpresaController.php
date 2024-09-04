<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function index()
    {
        return view('admin.empresas.index');
    }

    public function configuracion()
    {
        return view('admin.empresas.configuracion');
    }
}
