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
public function update($id = null)
    {
        if (!$id) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                                  ->setJSON(['error'=>'ID kosong']);
        }

        $model   = new CabangModel();
        $current = $model->find($id);
        if (!$current) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                                  ->setJSON(['error'=>'Cabang tidak ditemukan']);
        }

        $payload = $this->request->getJSON(true) ?? [];
        $nama    = array_key_exists('nama_cabang', $payload) ? trim((string)$payload['nama_cabang']) : $current['nama_cabang'];
        $lokasi  = array_key_exists('lokasi', $payload) ? trim((string)$payload['lokasi']) : ($current['lokasi'] ?? '');

        if ($nama === '') {
            return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                                  ->setJSON(['error'=>'Nama cabang wajib diisi']);
        }

        $model->update($id, ['nama_cabang'=>$nama, 'lokasi'=>$lokasi]);
        return $this->response->setJSON(['status'=>'success','message'=>'updated']);
    }

    public function delete($id = null)
    {
        if (!$id) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                                  ->setJSON(['error'=>'ID kosong']);
        }

        $model = new CabangModel();
        if (!$model->find($id)) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                                  ->setJSON(['error'=>'Cabang tidak ditemukan']);
        }

        $model->delete($id);
        return $this->response->setStatusCode(ResponseInterface::HTTP_NO_CONTENT);
    }
}
