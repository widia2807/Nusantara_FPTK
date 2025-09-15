<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    public function hr()
    {
        return view('dashboard/hr');
    }

    public function management()
    {
        return view('dashboard/management');
    }

    public function rekrutmen()
    {
        return view('dashboard/rekrutmen');
    }

    public function divisi()
    {
        return view('dashboard/divisi');
    }
}
