<?php
namespace App\Controllers;

use App\Models\DivisiModel;
use App\Models\PosisiModel;
use App\Models\CabangModel;
use App\Models\PengajuanModel;

class PengajuanForm extends BaseController
{
    public function index()
    {
        $divisiModel = new DivisiModel();
        $posisiModel = new PosisiModel();
        $cabangModel = new CabangModel();

        $data['divisi'] = $divisiModel->findAll();
        $data['posisi'] = $posisiModel->findAll();
        $data['cabang'] = $cabangModel->findAll();

        return view('div_menu/form', $data);
    }

    public function store()
    {
        $pengajuanModel = new PengajuanModel();

        $data = $this->request->getPost() + [
            'id_user_divisi'   => session()->get('id_user'),
            'status_hr'        => 'Pending',
            'status_management'=> 'Pending',
            'status_rekrutmen' => 'Pending',
        ];

        $pengajuanModel->insert($data);

        return redirect()->to(base_url('pengajuan'))
                         ->with('success', 'Pengajuan berhasil diajukan!');
    }
}
