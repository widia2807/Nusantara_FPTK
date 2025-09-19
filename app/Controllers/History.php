<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\HistoryModel;

class History extends ResourceController
{
    protected $modelName = HistoryModel::class;
    protected $format    = 'json';

    public function index()
    {
        $data = $this->model->getWithRelations();
        return $this->respond(['data' => $data]);
    }
}

