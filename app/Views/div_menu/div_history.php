<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>History Pengajuan - Divisi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { font-family: Arial, sans-serif; background: #f8f9fa; }
    .sidebar {
      width: 220px; position: fixed; top: 0; left: 0; bottom: 0;
      background: #fff; border-right: 1px solid #ddd; padding: 20px 10px;
    }
    .sidebar h5 { font-weight: bold; margin-bottom: 20px; }
    .sidebar a {
      display: block; padding: 10px 15px; margin-bottom: 5px;
      color: #333; text-decoration: none; border-radius: 6px;
    }
    .sidebar a:hover { background: #f0f0f0; }
    .header {
      height: 50px; background: #222; color: #fff;
      display: flex; align-items: center; padding: 0 20px;
    }
    .content {
      margin-left: 220px; padding: 30px;
    }
    .card {
      background: #fff; border-radius: 8px; 
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    footer {
      margin-left: 220px; background: #222; color: #fff;
      text-align: center; padding: 12px; margin-top: 30px;
    }
  </style>
</head>
<body>

  <!-- Header -->
  <div class="header">
    <h6 class="mb-0">History Pengajuan</h6>
  </div>

  <!-- Sidebar -->
  <div class="sidebar">
    <h5>Nusantara</h5>
    <a href="<?= base_url('dashboard/divisi') ?>">📊 Dashboard</a>
    <a href="<?= base_url('form') ?>">📝 Pengajuan</a>
    <a href="<?= base_url('history') ?>">📂 History</a>
    <a href="<?= base_url('logout') ?>" class="btn btn-dark w-100 mt-4">Logout</a>
  </div>

  <!-- Content -->
  <div class="content">
    <div class="card p-4">
      <h4 class="mb-3">History Pengajuan Divisi</h4>
      <div class="table-responsive">
        <table class="table table-hover table-sm align-middle">
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
              <th>Status HR</th>
              <th>Status Mng</th>
              <th>Comment</th>
            </tr>
          </thead>
          <tbody id="historyTable">
            <tr><td colspan="11" class="text-center">Loading...</td></tr>
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
      const res = await fetch('http://localhost/nusantara_api/public/api/pengajuan');
      const json = await res.json();
      const tbody = document.getElementById('historyTable');
      tbody.innerHTML = '';

      if (!json.data || json.data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="11" class="text-center">Belum ada history</td></tr>`;
        return;
      }

      json.data.forEach(item => {
        // tampilkan hanya yang sudah ditolak HR atau Management
        if (item.status_hr === 'Rejected' || item.status_management === 'Rejected') {
          const badgeHR  = `<span class="badge bg-${item.status_hr === 'Rejected' ? 'danger' : 'secondary'}">${item.status_hr}</span>`;
          const badgeMng = `<span class="badge bg-${item.status_management === 'Rejected' ? 'danger' : 'secondary'}">${item.status_management}</span>`;

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
              <td>${badgeHR}</td>
              <td>${badgeMng}</td>
              <td>${item.comment || '-'}</td>
            </tr>
          `;
        }
      });
    }

    loadHistory();
  </script>

</body>
</html>
