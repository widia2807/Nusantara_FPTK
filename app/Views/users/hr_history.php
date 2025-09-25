<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>History Pengajuan - Divisi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { font-family: Arial, sans-serif; }
    .sidebar {
      width: 220px; position: fixed; top: 0; left: 0; height: 100%;
      background: #f8f9fa; border-right: 1px solid #ddd; padding-top: 20px;
    }
    .sidebar a { display: block; padding: 10px 20px; color: #333; text-decoration: none; }
    .sidebar a:hover { background: #e9ecef; }
    .content { margin-left: 220px; padding: 20px; }
    footer {
      margin-left: 220px; background: #222; color: #fff;
      text-align: center; padding: 15px;
    }
    .table-compact th, .table-compact td {
      padding: 6px 8px !important; font-size: 13px; vertical-align: middle;
    }
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
    <a href="<?= base_url('users/create') ?>">
  <img src="https://img.icons8.com/ios-filled/50/000000/add-user-male.png" width="30"> Tambah Akun
</a>
   <a href="<?= base_url('history/hr') ?>">📂 History</a>
    
  </div>

  <!-- Content -->
  <div class="content">
    <div class="card p-4">
      <h4 class="mb-3">History Pengajuan Divisi</h4>
      <div class="table-responsive">
        <table class="table table-hover table-sm align-middle table-compact">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Divisi</th>
              <th>Posisi</th>
              <th>Cabang</th>
              <th>Jumlah</th>
              <th>Job Post</th>
              <th>Tipe</th>
              <th>Tanggal</th>
              <th>  Reviewer</th>
              <th>Role</th>
              <th>Aksi</th>
              <th>Komentar</th>
            </tr>
          </thead>
          <tbody id="historyTable">
            <tr><td colspan="12" class="text-center">Loading...</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer>
    NusantaraIT © 2025. All rights reserved.
  </footer>

  <script>
    async function loadHistory() {
      const res = await fetch('http://localhost/nusantara_api/public/api/history'); 
      const json = await res.json();
      const tbody = document.getElementById('historyTable');
      tbody.innerHTML = '';

      if (!json.data || json.data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="12" class="text-center">Belum ada history</td></tr>`;
        return;
      }

      json.data.forEach(item => {
        const badge = `<span class="badge bg-${
          item.action === 'Approved' ? 'success' : 
          item.action === 'Rejected' ? 'danger' : 'secondary'
        }">${item.action}</span>`;

        tbody.innerHTML += `
          <tr>
            <td>${item.id_pengajuan}</td>
            <td>${item.nama_divisi}</td>
            <td>${item.nama_posisi}</td>
            <td>${item.nama_cabang}</td>
            <td>${item.jumlah_karyawan}</td>
            <td>${item.job_post_number}</td>
            <td>${item.tipe_pekerjaan}</td>
            <td>${item.created_at}</td>
            <td>${item.full_name || '-'}</td>
            <td>${item.role_user}</td>
            <td>${badge}</td>
            <td>${item.comment || '-'}</td>
          </tr>
        `;
      });
    }

    loadHistory();
  </script>

</body>
</html>
