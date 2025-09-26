<?php

namespace App\Controllers;

use App\Models\HistoryModel;
use CodeIgniter\HTTP\ResponseInterface;

class History extends BaseController
{
    private function corsHeaders()
    {
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, OPTIONS');
        $this->response->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        $this->response->setHeader('Access-Control-Max-Age', '86400');
    }

    public function index()
    {
        $this->corsHeaders();

        if (strtolower($this->request->getMethod()) !== 'get') {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_METHOD_NOT_ALLOWED)
                ->setJSON(['error' => 'Method not allowed']);
        }

        try {
            $historyModel = new HistoryModel();
            
            // Gunakan method yang memang ada di model Anda
            $historyData = $historyModel->getWithRelations();

            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_OK)
                ->setJSON([
                    'status' => 'success',
                    'data' => $historyData
                ]);
                
        } catch (\Throwable $e) {
            log_message('error', 'History fetch failed: {msg}', ['msg' => $e->getMessage()]);
            
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR)
                ->setJSON([
                    'error' => 'Gagal mengambil data history',
                    'debug' => $e->getMessage()
                ]);
        }
    }

    public function options()
    {
        $this->corsHeaders();
        return $this->response->setStatusCode(ResponseInterface::HTTP_OK);
    }
}