<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;

class Auth extends ResourceController
{
    use ResponseTrait;

    // ================================
    // POST /api/login
    // ================================
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

        // cek password hash
        if (!isset($user['password']) || !password_verify($password, $user['password'])) {
            return $this->respond(['error' => 'Password salah'], 401);
        }

        // 🔴 cek password default
        if (password_verify("123456", $user['password'])) {
            return $this->respond([
                'status'  => 'force_change_password',
                'message' => 'Silakan ubah password default Anda',
                'user'    => [
                    'id_user'   => $user['id_user'],
                    'username'  => $user['username'],
                    'full_name' => $user['full_name'],
                    'role'      => $user['role'],
                ]
            ]);
        }

        // ✅ set session kalau bukan default password
        $session = session();
        $session->set([
            'id_user'   => $user['id_user'],
            'username'  => $user['username'],
            'full_name' => $user['full_name'],
            'role'      => $user['role'],
            'isLoggedIn'=> true
        ]);
        $session->regenerate();

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

    // ================================
    // GET /api/ping
    // ================================
    public function ping()
    {
        return $this->respond([
            'status'  => 'ok',
            'message' => 'API is running',
            'time'    => date('Y-m-d H:i:s')
        ]);
    }

    // ================================
    // GET /auth/change-password
    // ================================
    public function changePasswordForm()
    {
        // Pastikan file ada di: app/Views/change_password.php
        return view('change_password');
    }

    // ================================
    // POST /auth/change-password
    // ================================
    public function changePassword()
    {
        $idUser       = session()->get('id_user');
        $oldPass      = $this->request->getPost('old_password');
        $newPass      = $this->request->getPost('new_password');
        $confirmPass  = $this->request->getPost('confirm_password');

        if ($newPass !== $confirmPass) {
            return redirect()->back()->with('error', 'Password baru dan konfirmasi tidak sama');
        }

        $userModel = new UserModel();
        $user = $userModel->find($idUser);

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan');
        }

        if (!isset($user['password']) || !password_verify($oldPass, $user['password'])) {
            return redirect()->back()->with('error', 'Password lama salah');
        }

        // update password
        $userModel->update($idUser, [
            'password' => password_hash($newPass, PASSWORD_BCRYPT)
        ]);

        // logout user
        session()->destroy();

        return redirect()->to('/login')->with('success', 'Password berhasil diubah, silakan login ulang.');
    }

    // ================================
    // GET /logout
    // ================================
    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/login');
    }
}
