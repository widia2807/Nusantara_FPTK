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

        $mapped = array_map(function ($row) use ($has, $starts) {
            $action = mb_strtolower((string)($row['action'] ?? ''));
            $hr     = mb_strtolower((string)($row['status_hr'] ?? ''));
            $mng    = mb_strtolower((string)($row['status_management'] ?? ''));
            $rek    = mb_strtolower((string)($row['status_rekrutmen'] ?? ''));

            // 1) Tentukan label utama
            $label = null;
            if ($action !== '') {
                if ($action === 'rejected' || $starts($action, 'reject') || $has($action, 'tolak')) {
                    $label = 'Rejected';
                } elseif ($action === 'approved' || $has($action, 'approve') || $has($action, 'setuju')) {
                    $label = 'Approved';
                } elseif ($has($action, 'sent') || $has($action, 'kirim') || $has($action, 'dikirim')
                          || $has($action, 'send') || $action === 'sent_to_management') {
                    $label = 'Dikirim ke Management';
                } elseif (($has($action, 'rekrutmen') && ($has($action, 'selesai') || $has($action, 'done') || $has($action, 'complete')))
                          || $action === 'rekrutmen_selesai' || $action === 'finish') {
                    $label = 'Rekrutmen Selesai';
                }
            }

            if ($label === null) {
                if ($hr === 'rejected' || $mng === 'rejected') {
                    $label = 'Rejected';
                } elseif (in_array($rek, ['selesai', 'done', 'complete'], true)) {
                    $label = 'Rekrutmen Selesai';
                } elseif ($hr === 'approved' && ($mng === '' || $mng === 'pending')) {
                    $label = 'Dikirim ke Management';
                } elseif ($hr === 'approved' || $mng === 'approved') {
                    $label = 'Approved';
                } else {
                    $label = 'Pending';
                }
            }

            // 2) Warna badge
            $badge = 'secondary';
            if ($label === 'Rejected') $badge = 'danger';
            if ($label === 'Approved' || $label === 'Rekrutmen Selesai') $badge = 'primary';

            // 3) Normalisasi field untuk front-end

            // waktu: pakai waktu history jika ada alias-nya
            if (!empty($row['history_created_at'])) {
                $row['created_at'] = $row['history_created_at'];
            }

            // nama reviewer: pakai reviewer_name (dari tabel user)
            if (!empty($row['reviewer_name'])) {
                $row['full_name'] = $row['reviewer_name'];
            } elseif (empty($row['full_name'])) {
                $row['full_name'] = '-';
            }

            // role reviewer: prioritas role_user_effective (COALESCE(history.role_user, user.role))
            if (!empty($row['role_user_effective'])) {
                $row['role_user'] = $row['role_user_effective'];
            } elseif (empty($row['role_user'])) {
                $row['role_user'] = '-';
            }

            // label & badge untuk dipakai front-end
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
