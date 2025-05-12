<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClinicController extends Controller
{
    public function dashboard()
    {
        return view('clinic.dashboard');
    }
} 