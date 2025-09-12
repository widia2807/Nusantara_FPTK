<?php

namespace App\Controllers;

use App\Models\HistoryModel;
use CodeIgniter\RESTful\ResourceController;

class History extends ResourceController
{
    protected $modelName = HistoryModel::class;
    protected $format    = 'json';

    public function index($pengajuan_id = null)
    {
        return $this->respond(
            $this->model->where('pengajuan_id', $pengajuan_id)->findAll()
        );
    }
}
