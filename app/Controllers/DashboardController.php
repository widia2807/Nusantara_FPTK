<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    public function index()
    {
        $role = session()->get('role'); // ambil role dari session

        switch ($role) {
            case 'HR':
                return view('dashboard/hr');
            case 'Management':
                return view('dashboard/management');
            case 'Rekrutmen':
                return view('dashboard/rekrutmen');
            case 'Divisi':
                return view('dashboard/divisi');
            default:
                return redirect()->to('/login'); // kalau belum login
        }
    }
}
