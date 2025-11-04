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
    return view('history/mng_history');
}

public function div_history()
{
   return view('history/div_history');

}

public function hr_history()
{
    // arahkan ke view yang sudah ada di /views/users/hr_history.php
    return view('history/hr_history');
}

public function rek_history()
{
    return view('history/rek_history');
}


}
