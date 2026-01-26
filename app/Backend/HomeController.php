<?php

namespace App\Backend;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('backend.home');
    }
}
