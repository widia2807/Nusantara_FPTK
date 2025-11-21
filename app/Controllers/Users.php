<?php
namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;

class Users extends BaseController
{
    use ResponseTrait; // kalau BaseController sudah pakai ini, baris ini boleh dihapus

    private function corsHeaders()
    {
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, OPTIONS');
        $this->response->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        $this->response->setHeader('Access-Control-Max-Age', '86400');
    }

    public function createForm()
    {
        return view('admin_menu/create');
    }

    public function manageAll()
    {
        return view('admin_menu/manage_all');
    }

    public function hr_history()
    {
        return view('history/hr');
    }

    public function create()
    {
        $this->corsHeaders();

        if (strtolower($this->request->getMethod()) !== 'post') {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_METHOD_NOT_ALLOWED)
                ->setJSON(['error' => 'Method not allowed']);
        }

        $role = session('role');

        if ($role !== 'HR') {
            log_message('notice', 'Create user blocked: required HR, got {role}', ['role' => $role ?: '(none)']);
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_FORBIDDEN)
                ->setJSON(['error' => 'Hanya HR yang boleh membuat akun']);
        }

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

        $validRoles = ['HR','Management','Rekrutmen','Divisi'];
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

        $users = new UserModel();
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

    public function index()
    {
        $this->corsHeaders();
        $users = new UserModel();
        $data = $users->select('id_user, username, full_name, role, is_active, created_at')->findAll();
        return $this->response->setJSON(['status' => 'success', 'data' => $data]);
    }

    public function activate($id)
{
    $model = new UserModel();
    $model->update($id, ['is_active' => 1]);

    return $this->respond([
        'status' => 'success',
        'message' => 'User berhasil diaktifkan'
    ]);
}

public function deactivate($id)
{
    $model = new UserModel();
    $model->update($id, ['is_active' => 0]);

    return $this->respond([
        'status' => 'success',
        'message' => 'User berhasil dinonaktifkan'
    ]);
}

    public function reset_password($id = null)
    {
        $this->corsHeaders();
        if (!$id) return $this->response->setStatusCode(400)->setJSON(['error'=>'Missing id']);
        
        $users = new UserModel();
        $user = $users->find($id);
        if (!$user) return $this->response->setStatusCode(404)->setJSON(['error'=>'User tidak ditemukan']);

        $data = $this->request->getJSON(true) ?? [];
        $newPass = trim($data['password'] ?? '123456');

        $hash = password_hash($newPass, PASSWORD_DEFAULT);
        $users->update($id, ['password'=>$hash]);

        return $this->response->setJSON(['status'=>'success','message'=>'Password direset ke default']);
    }

  public function uploadProfile()
{
    // 🔐 1. Pastikan user sudah login (pakai session, bukan token)
    $userId = session()->get('id_user'); // ✔ sesuai Auth::login()
    if (!$userId) {
        return $this->failUnauthorized('User belum login.');
    }

    // 🔍 2. Ambil file dari FormData
    $file = $this->request->getFile('profile');
    if (!$file || !$file->isValid()) {
        return $this->failValidationErrors('File foto tidak valid.');
    }

    // 🎨 3. Validasi tipe gambar
    $validMime = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
    if (!in_array($file->getMimeType(), $validMime)) {
        return $this->failValidationErrors('Tipe file harus gambar (JPG/PNG/WEBP).');
    }

    // 📁 4. Siapkan folder upload
    $uploadPath = FCPATH . 'uploads/profile';
    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0775, true);
    }

    // 🎯 5. Buat nama file unik
    $ext     = $file->getExtension();
    $newName = 'user_' . $userId . '_' . time() . '.' . $ext;

    // 🗂 6. Ambil data user lama
    $userModel = new UserModel();
    $user      = $userModel->find($userId);

    if (!$user) {
        return $this->failNotFound('User tidak ditemukan.');
    }

    // 💾 7. Pindahkan file ke folder upload
    if (!$file->move($uploadPath, $newName, true)) {
        return $this->fail('Gagal menyimpan file.');
    }

    // 🧹 8. Hapus foto lama jika ada dan bukan default
    if (!empty($user['profile_photo']) && $user['profile_photo'] !== 'default.png') {
        $oldPath = $uploadPath . '/' . $user['profile_photo'];
        if (is_file($oldPath)) {
            @unlink($oldPath);
        }
    }

    // 📝 9. Update server-side (database)
    $userModel->update($userId, [
        'profile_photo' => $newName,
    ]);

    // 🔄 10. Refresh ulang dari DB
    $user = $userModel->find($userId);

    // 🔁 11. Update session (supaya UI langsung ganti)
    session()->set([
        'nama_user'     => $user['full_name'] ?? $user['username'] ?? 'User Portal',
        'email_user'    => $user['username'] ?? $user['email'] ?? 'user@example.com',
        'profile_photo' => $user['profile_photo'] ?? 'default.png',
    ]);

    // 🌐 12. Kirim URL ke frontend
    $url = base_url('uploads/profile/' . $newName);

    return $this->respond([
        'status'  => 'success',
        'message' => 'Foto profil berhasil diupdate.',
        'url'     => $url,
    ]);
}

}
