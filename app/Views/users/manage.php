<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manajemen User - Nusantara Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { font-family: 'Segoe UI', Arial, sans-serif; background: #f9fafc; color: #212529; }
    /* Sidebar */
    .sidebar {
      width: 220px; position: fixed; top: 0; left: 0; height: 100%;
      background: #fff; border-right: 1px solid #e5e7eb; padding-top: 20px;
      box-shadow: 2px 0 6px rgba(0,0,0,0.05);
    }
    .sidebar h6 { color: #0d6efd; font-weight: 700; }
    .sidebar a {
      display: block; padding: 10px 20px; color: #444; text-decoration: none;
      font-size: 14px; border-left: 3px solid transparent; transition: all 0.2s ease;
    }
    .sidebar a:hover, .sidebar a.active {
      background: #e7f1ff; border-left: 3px solid #0d6efd; color: #0d6efd;
    }

    /* Content */
    .content { margin-left: 240px; padding: 25px; }
    h2 { font-weight: 700; color: #0d6efd; }

    /* Table */
    .table thead { background: linear-gradient(90deg, #0d6efd, #0dcaf0); color: #fff; }
    .table-hover tbody tr:hover { background: #f1f5ff; }

    /* Badge */
    .badge { font-size: 12px; padding: 5px 10px; border-radius: 8px; }

    /* Modal */
    .modal-content { border-radius: 12px; border: none; box-shadow: 0 6px 20px rgba(0,0,0,0.15); }
    .modal-header { background: linear-gradient(90deg, #0d6efd, #0dcaf0); color: #fff; }
    .modal-title { font-weight: 600; }
    .modal-footer .btn { border-radius: 8px; font-weight: 500; }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="text-center mb-4">
      <img src="<?= base_url('assets/images/logo-nusantara-group.png') ?>" alt="Logo" height="40">
      <h6 class="mt-2">Nusantara Portal</h6>
    </div>
    <a href="<?= base_url('dashboard/hr') ?>">📊 Dashboard</a>
    <a href="<?= base_url('users/create') ?>">➕ Tambah Akun</a>
    <a href="<?= base_url('users/manage') ?>" class="active">👥 Manajemen User</a>
        <a href="<?= base_url('users/hr_history') ?>">📂 History</a>
  </div>

  <!-- Content -->
  <div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Manajemen User</h2>
    </div>

    <div class="card shadow">
      <div class="card-body">
        <h5 class="mb-3">Daftar User</h5>
        <div id="alert"></div>
        <div class="table-responsive">
          <table class="table table-hover table-sm align-middle">
            <thead>
              <tr>
                <th>ID</th><th>Username</th><th>Nama</th><th>Role</th><th>Status</th><th>Aksi</th>
              </tr>
            </thead>
            <tbody id="usersTable">
              <tr><td colspan="6" class="text-center">Loading...</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Reset Password -->
  <div class="modal fade" id="resetModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Reset Password</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>Reset password user <b id="resetUsername"></b> ke default <code>123456</code>?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-primary" id="confirmReset">Ya, Reset</button>
        </div>
      </div>
    </div>
  </div>

  <script>
  const apiBase = "http://10.101.56.69:8080/api";
  let currentResetId = null;

  function showAlert(msg, type='success') {
    document.getElementById('alert').innerHTML =
      `<div class="alert alert-${type}">${msg}</div>`;
    setTimeout(()=> document.getElementById('alert').innerHTML = '', 3000);
  }

  async function loadUsers() {
    const res = await fetch(`${apiBase}/users`);
    const json = await res.json();
    const tbody = document.getElementById('usersTable');
    tbody.innerHTML = '';

    if (!json.data || json.data.length === 0) {
      tbody.innerHTML = `<tr><td colspan="6" class="text-center">Belum ada user</td></tr>`;
      return;
    }

    json.data.forEach(u => {
      const badge = `<span class="badge bg-${u.is_active==1 ? 'success' : 'secondary'}">${u.is_active==1 ? 'Aktif' : 'Nonaktif'}</span>`;
      tbody.innerHTML += `
        <tr>
          <td>${u.id_user}</td>
          <td>${u.username}</td>
          <td>${u.full_name}</td>
          <td>${u.role}</td>
          <td>${badge}</td>
          <td>
            ${u.is_active==1 
              ? `<button class="btn btn-sm btn-warning" onclick="toggleActive(${u.id_user}, false)">Nonaktifkan</button>`
              : `<button class="btn btn-sm btn-success" onclick="toggleActive(${u.id_user}, true)">Aktifkan</button>`}
            <button class="btn btn-sm btn-secondary ms-1" onclick="openReset(${u.id_user}, '${u.username}')">Reset Password</button>
          </td>
        </tr>`;
    });
  }

  async function toggleActive(id, activate) {
    const url = activate ? `${apiBase}/users/activate/${id}` : `${apiBase}/users/deactivate/${id}`;
    const res = await fetch(url, { method: 'POST' });
    const json = await res.json();
    if (json.status === 'success') {
      showAlert(json.message, 'success');
      loadUsers();
    } else {
      showAlert(json.message || 'Gagal', 'danger');
    }
  }

  function openReset(id, username) {
    currentResetId = id;
    document.getElementById('resetUsername').textContent = username;
    new bootstrap.Modal(document.getElementById('resetModal')).show();
  }

  document.getElementById('confirmReset').addEventListener('click', async () => {
    if (!currentResetId) return;
    const res = await fetch(`${apiBase}/users/reset_password/${currentResetId}`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ password: '123456' })
    });
    const json = await res.json();
    if (json.status === 'success') {
      showAlert("Password berhasil direset ke 123456", 'success');
      bootstrap.Modal.getInstance(document.getElementById('resetModal')).hide();
    } else {
      showAlert("Gagal reset password", 'danger');
    }
  });

  loadUsers();
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
