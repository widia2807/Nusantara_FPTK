<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>History Pengajuan - Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background: #f9fafc;
    }

    /* Sidebar */
    .sidebar {
      width: 220px; position: fixed; top: 0; left: 0; height: 100%;
      background: #fff; border-right: 1px solid #ddd; padding-top: 20px;
      box-shadow: 2px 0 6px rgba(0,0,0,0.05);
    }
    .sidebar h6 { color: #0d6efd; font-weight: 700; }
    .sidebar a {
      display: block; padding: 10px 20px;
      color: #333; text-decoration: none;
      font-size: 14px; border-left: 3px solid transparent;
      transition: all 0.2s;
    }
    .sidebar a:hover {
      background: #e7f1ff;
      border-left: 3px solid #0d6efd;
      color: #0d6efd;
    }

    /* Content */
    .content {
      margin-left: 240px; padding: 30px;
    }

    /* Table */
    .table thead {
      background: #0d6efd; color: #fff;
    }
    .table-compact th, .table-compact td {
      padding: 6px 8px !important;
      font-size: 13px;
      vertical-align: middle;
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
    <a href="<?= base_url('dashboard/management') ?>">📊 Dashboard</a>
    <a href="<?= base_url('history/management') ?>">📂 History</a>
  </div>

  <!-- Content -->
  <div class="content">
    <div class="card p-4 shadow-sm">
      <h4 class="mb-3">History Pengajuan Management</h4>
      <div class="table-responsive">
        <table class="table table-hover table-sm align-middle table-compact">
          <thead>
            <tr>
              <th>ID</th>
              <th>Divisi</th>
              <th>Posisi</th>
              <th>Cabang</th>
              <th>Jumlah</th>
              <th>Job Post</th>
              <th>Tipe</th>
              <th>Tanggal</th>
              <th>Reviewer</th>
              <th>Status</th>
              <th>Komentar</th>
            </tr>
          </thead>
          <tbody id="historyTable">
            <tr><td colspan="11" class="text-center text-muted">Loading...</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Script -->
  <script>
    async function loadHistory() {
      try {
        const res = await fetch('http://localhost/nusantara_api/public/api/history');
        const json = await res.json();
        const tbody = document.getElementById('historyTable');
        tbody.innerHTML = '';

        if (!json.data || json.data.length === 0) {
          tbody.innerHTML = `<tr><td colspan="11" class="text-center text-muted">Belum ada history</td></tr>`;
          return;
        }

        let filtered = json.data.filter(item => item.role_user === 'Management');

        if (filtered.length === 0) {
          tbody.innerHTML = `<tr><td colspan="11" class="text-center text-muted">Belum ada history dari Management</td></tr>`;
          return;
        }

        filtered.forEach(item => {
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
              <td>${badge}</td>
              <td>${item.comment || '-'}</td>
            </tr>
          `;
        });
      } catch (err) {
        console.error(err);
        document.getElementById('historyTable').innerHTML =
          `<tr><td colspan="11" class="text-center text-danger">Gagal memuat data</td></tr>`;
      }
    }

    loadHistory();
  </script>

</body>
</html>
