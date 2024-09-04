<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
    }

    public function roles()
    {
        return view('admin.roles.index');
    }

    public function permisos()
    {
        return view('admin.permisos.index');
    }
}
