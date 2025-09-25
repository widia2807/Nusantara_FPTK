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
    public function mng_history()
{
    return view('mng_history');
}

public function div_history()
{
    return view('div_history');
}

public function hr_history()
{
    return view('hr_history');
}

public function rek_history()
{
    return view('rek_history');
}


}
