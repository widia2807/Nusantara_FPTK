<?php
namespace App\Controllers;

use App\Models\CabangModel;
use CodeIgniter\HTTP\ResponseInterface;

class Cabang extends BaseController
{
    public function index()
    {
        $model = new CabangModel();
        $data = $model->findAll();
        return $this->response->setJSON(['status'=>'success','data'=>$data]);
    }

    public function create()
    {
        $model = new CabangModel();
        $payload = $this->request->getJSON(true);
        $nama   = trim($payload['nama_cabang'] ?? '');
        $lokasi = trim($payload['lokasi'] ?? '');

        if ($nama === '') {
            return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                                  ->setJSON(['error'=>'Nama cabang wajib diisi']);
        }

        $model->insert(['nama_cabang'=>$nama, 'lokasi'=>$lokasi]);
        return $this->response->setJSON(['status'=>'success','id'=>$model->getInsertID()]);
    }

    // optional: update & delete kalau mau
}
