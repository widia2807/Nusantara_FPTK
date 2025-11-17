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
        // tambahkan X-Requested-With biar aman untuk fetch/ajax
        $this->response->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
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
        $historyData  = $historyModel->getWithRelations();

        // helper aman utk PHP7/8, pengganti str_contains/starts_with
        $has = function (?string $haystack, string $needle): bool {
            if ($haystack === null || $haystack === '') return false;
            return mb_stripos($haystack, $needle) !== false;
        };
        $starts = function (?string $haystack, string $prefix): bool {
            if ($haystack === null) return false;
            return mb_substr($haystack, 0, mb_strlen($prefix)) === $prefix;
        };

        $mapped = array_map(function ($row) {
    $action = strtolower(trim((string)($row['action'] ?? '')));
    $hr     = strtolower((string)($row['status_hr'] ?? ''));
    $mng    = strtolower((string)($row['status_management'] ?? ''));
    $rek    = strtolower((string)($row['status_rekrutmen'] ?? ''));

    // 1) Label berdasar action (kamus konsisten)
    $labelMap = [
        'hr_send'             => 'Dikirim ke Management',
        'hr_approve'          => 'Approved',
        'hr_reject'           => 'Rejected',
        'management_review'   => 'Diterima untuk Review',
        'management_approve'  => 'Approved',
        'management_reject'   => 'Rejected',
        'rekrutmen_start'     => 'Proses Rekrutmen',
        'rekrutmen_done'      => 'Rekrutmen Selesai',
    ];
    $label = $labelMap[$action] ?? null;

    // 2) Fallback kecil hanya jika action kosong/tidak dikenal
    if ($label === null) {
        if ($mng === 'rejected' || $hr === 'rejected') {
            $label = 'Rejected';
        } elseif (in_array($rek, ['selesai','done','complete'], true)) {
            $label = 'Rekrutmen Selesai';
        } elseif ($mng === 'approved' || $hr === 'approved') {
            // kalau hr approved tapi mg masih pending → kirim ke management
            $label = ($hr === 'approved' && ($mng === '' || $mng === 'pending'))
                   ? 'Dikirim ke Management'
                   : 'Approved';
        } else {
            $label = 'Pending';
        }
    }

    // 3) Warna badge
    $badge = 'secondary';
    if ($label === 'Rejected') $badge = 'danger';
    elseif (in_array($label, ['Approved', 'Rekrutmen Selesai'], true)) $badge = 'primary';
    elseif ($label === 'Dikirim ke Management') $badge = 'warning';

    // 4) Normalisasi tampilan
    if (!empty($row['history_created_at'])) $row['created_at'] = $row['history_created_at'];
    $row['full_name'] = $row['reviewer_name'] ?? ($row['full_name'] ?? '-');
    $row['role_user'] = $row['role_user_effective'] ?? ($row['role_user'] ?? '-');
    $row['label'] = $label;
    $row['badge'] = $badge;
    return $row;
}, $historyData ?? []);

        return $this->response
            ->setStatusCode(ResponseInterface::HTTP_OK)
            ->setJSON([
                'status' => 'success',
                'data'   => $mapped
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
