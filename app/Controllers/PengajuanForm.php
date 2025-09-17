<?php
namespace App\Controllers;

use App\Models\PengajuanModel;

class PengajuanForm extends BaseController
{
    public function index()
    {
        return view('pengajuan'); // tampilin form HTML
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
