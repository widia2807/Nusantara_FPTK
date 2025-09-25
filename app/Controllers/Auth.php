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
    $remember = $request->rememberMe ?? false; // ✅ tangkap rememberMe dari request

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
        $session = session();
        $session->set([
            'temp_user_id'   => $user['id_user'],
            'temp_username'  => $user['username'],
            'temp_full_name' => $user['full_name'],
            'temp_role'      => $user['role'],
            'force_change_password' => true
        ]);

        return $this->respond([
            'status'  => 'force_change_password',
            'message' => 'Silakan ubah password default Anda',
            'user'    => [
                'id_user'   => $user['id_user'],
                'username'  => $user['username'],
                'full_name' => $user['full_name'],
                'role'      => $user['role'],
            ],
            'redirect_url' => base_url('auth/change-password')
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

    // ✅ remember me logic
    if ($remember) {
        helper('text');
        $token   = bin2hex(random_bytes(32));
        $expired = date('Y-m-d H:i:s', strtotime('+30 days'));

        // simpan token ke DB
        $db = \Config\Database::connect();
        $db->table('user_tokens')->insert([
            'id_user'    => $user['id_user'],
            'token'      => $token,
            'expired_at' => $expired
        ]);

        // simpan token ke cookie
        setcookie("remember_token", $token, time() + (86400 * 30), "/");
    }

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
    // GET /auth/change-password
    // ================================
    public function changePasswordForm()
    {
        // Cek apakah user dalam mode force change password
        $session = session();
        if (!$session->get('force_change_password')) {
            return redirect()->to('/login')->with('error', 'Akses tidak valid');
        }

        return view('change_password');
    }

    // ================================
    // POST /auth/change-password
    // ================================
    public function changePassword()
    {
        $post = $this->request->getPost(); 
        log_message('debug', 'POST DATA: ' . json_encode($post));

        $session = session();
        
        // ✅ AMBIL ID USER DARI SESSION TEMP ATAU SESSION NORMAL
        $idUser = $session->get('temp_user_id') ?? $session->get('id_user') ?? $post['id_user'] ?? null;
        
        $oldPass     = $post['old_password'] ?? null;
        $newPass     = $post['new_password'] ?? null;
        $confirmPass = $post['confirm_password'] ?? null;

        log_message('debug', 'ID User from session: ' . $idUser);

        if (!$idUser) {
            return redirect()->back()->with('error', '❌ ID User kosong, tidak bisa update password');
        }

        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($idUser);

        if (!$user) {
            return redirect()->back()->with('error', '❌ User tidak ditemukan');
        }

        // cek password lama
        if (!password_verify($oldPass, $user['password'])) {
            return redirect()->back()->with('error', '❌ Password lama salah');
        }

        if ($newPass !== $confirmPass) {
            return redirect()->back()->with('error', '❌ Konfirmasi password tidak sama');
        }

        if (strlen($newPass) < 6) {
            return redirect()->back()->with('error', '❌ Password minimal 6 karakter');
        }

        // Update password
        $update = $userModel->update($idUser, [
            'password' => password_hash($newPass, PASSWORD_DEFAULT)
        ]);

        log_message('debug', 'UPDATE RESULT: ' . json_encode($update));

        if (!$update) {
            return redirect()->back()->with('error', '❌ Gagal update password');
        }

        // ✅ CLEAR SEMUA SESSION DATA
        $session->destroy();
        
        return redirect()->to('/login')->with('success', '✅ Password berhasil diubah, silakan login ulang.');
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
    // GET /logout
    // ================================
   public function logout()
{
    $session = session();
    $session->destroy();

    // hapus cookie
    if (isset($_COOKIE['remember_token'])) {
        $db = \Config\Database::connect();
        $db->table('user_tokens')->where('token', $_COOKIE['remember_token'])->delete();
        setcookie('remember_token', '', time() - 3600, "/");
    }

    return redirect()->to(base_url('login'))->with('success', 'Berhasil logout');
}
}