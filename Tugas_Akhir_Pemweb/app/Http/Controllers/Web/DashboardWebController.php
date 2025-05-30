<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardWebController extends Controller
{
    public function index()
    {
        return view('dashboard'); // pastikan kamu punya file resources/views/dashboard.blade.php
    }
}
