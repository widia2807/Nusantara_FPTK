<?php
namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class Users extends BaseController
{
    private function corsHeaders()
    {
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, OPTIONS');
        $this->response->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        $this->response->setHeader('Access-Control-Max-Age', '86400');
    }

    public function createForm()
    {
        return view('users/create'); 
    }
public function hr_history()
{
    return view('users/hr_history'); // ini akan load app/Views/users/hr_history.php
}

    public function create()
    {
        $this->corsHeaders();

        if (strtolower($this->request->getMethod()) !== 'post') {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_METHOD_NOT_ALLOWED)
                ->setJSON(['error' => 'Method not allowed']);
        }

        // --- Authorization (HARUS HR) ---
        $role = session('role'); // ✅ ganti dari user.role ke role langsung

        if ($role !== 'HR') {
            log_message('notice', 'Create user blocked: required HR, got {role}', ['role' => $role ?: '(none)']);
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_FORBIDDEN)
                ->setJSON(['error' => 'Hanya HR yang boleh membuat akun']);
        }

        // --- Ambil payload ---
        $data = $this->request->getJSON(true);
        if (!is_array($data) || empty($data)) {
            $data = $this->request->getPost();
        }

        $payload = [
            'username'  => trim($data['username']  ?? ''),
            'full_name' => trim($data['full_name'] ?? ''),
            'password'  => (string)($data['password'] ?? ''),
            'role'      => (string)($data['role'] ?? ''),
            'is_active' => isset($data['is_active']) ? (int)$data['is_active'] : 1,
        ];

        // --- Validasi ---
        $validRoles = ['HR','Management','Rekrutmen','Divisi']; // ✅ tambahin
        $rules = [
            'username'  => 'required|min_length[3]|max_length[100]|is_unique[user.username]',
            'full_name' => 'required|min_length[3]|max_length[150]',
            'password'  => 'required|min_length[6]|max_length[255]',
            'role'      => 'required|in_list[' . implode(',', $validRoles) . ']',
            'is_active' => 'in_list[0,1]',
        ];

        if (!$this->validate($rules)) {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_UNPROCESSABLE_ENTITY)
                ->setJSON([
                    'error' => 'Validasi gagal',
                    'details' => $this->validator->getErrors()
                ]);
        }

        // --- Insert data ---
        $users = new UserModel(); // ✅ perbaikan
        $insertData = [
            'username'  => $payload['username'],
            'full_name' => $payload['full_name'],
            'password'  => password_hash($payload['password'], PASSWORD_DEFAULT),
            'role'      => $payload['role'],
            'is_active' => $payload['is_active'],
        ];

        try {
            $users->insert($insertData);
            $id = (int)$users->getInsertID();

            log_message('info', 'User created by HR: username={u}, id={id}', [
                'u' => $payload['username'], 'id' => $id
            ]);

            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_CREATED)
                ->setJSON([
                    'status' => 'success',
                    'user' => [
                        'id_user'   => $id,
                        'username'  => $insertData['username'],
                        'full_name' => $insertData['full_name'],
                        'role'      => $insertData['role'],
                        'is_active' => (int)$insertData['is_active'],
                    ],
                ]);
        } catch (\Throwable $e) {
            log_message('error', 'Create user failed: {msg}', ['msg' => $e->getMessage()]);
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR)
                ->setJSON(['error' => 'Gagal membuat user']);
        }
    }
}
