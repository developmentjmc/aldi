<?php

namespace App\Backend;

class SiteController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        $title = 'Dashboard';
        return view('backend.dashboard.index', get_defined_vars());
    }
}
