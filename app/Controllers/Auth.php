<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;

class Auth extends ResourceController
{
    use ResponseTrait;

    // POST /api/login
    public function login()
    {
        $request = $this->request->getJSON() ?? (object) $this->request->getPost();

        $username = $request->username ?? '';
        $password = $request->password ?? '';

        $userModel = new UserModel();
        $user = $userModel->where('username', $username)->first();

        if (!$user) {
            return $this->respond(['error' => 'User tidak ditemukan'], 401);
        }

        if (!password_verify($password, $user['password'])) {
            return $this->respond(['error' => 'Password salah'], 401);
        }

        // sukses
        return $this->respond([
            'status' => 'success',
            'user' => [
                'id_user'   => $user['id_user'],
                'username'  => $user['username'],
                'full_name' => $user['full_name'],
                'role'      => $user['role'],
            ]
        ]);
    }

    // GET /api/ping
    public function ping()
    {
        return $this->respond([
            'status'  => 'ok',
            'message' => 'API is running',
            'time'    => date('Y-m-d H:i:s')
        ]);
    }
}
