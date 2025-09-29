<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>History Pengajuan - HR</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background: #f9fafc;
      color: #212529;
    }

    /* Sidebar */
    .sidebar {
      width: 220px;
      position: fixed;
      top: 0; left: 0; height: 100%;
      background: #fff;
      border-right: 1px solid #e5e7eb;
      padding-top: 20px;
      box-shadow: 2px 0 6px rgba(0,0,0,0.05);
    }
    .sidebar h6 {
      color: #0d6efd;
      font-weight: 700;
    }
    .sidebar a {
      display: block;
      padding: 10px 20px;
      color: #444;
      text-decoration: none;
      font-size: 14px;
      border-left: 3px solid transparent;
      transition: all 0.2s ease;
    }
    .sidebar a:hover {
      background: #e7f1ff;
      border-left: 3px solid #0d6efd;
      color: #0d6efd;
    }

    /* Content */
    .content {
      margin-left: 240px;
      padding: 30px;
    }

    /* Card */
    .card {
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .card h4 {
      color: #0d6efd;
      font-weight: 700;
    }

    /* Table */
    .table-compact th, 
    .table-compact td {
      padding: 6px 10px !important;
      font-size: 13px;
      vertical-align: middle;
    }
    .table-dark th {
      background: #0d6efd !important;
      border-color: #0b5ed7;
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
    <a href="<?= base_url('users/create') ?>">➕ Tambah Akun</a>
    <a href="<?= base_url('users/manage') ?>" class="active">👥 Manajemen User</a>
    <a href="<?= base_url('users/hr_history') ?>">📂 History</a>
  </div>

  <!-- Content -->
  <div class="content">
    <div class="card p-4">
      <h4 class="mb-3">History Pengajuan HR</h4>
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
              <th>Reviewer</th>
              <th>Role</th>
              <th>Status</th>
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

  <script>
    async function loadHistory() {
      const res = await fetch('http://10.101.56.69:8080/api/history'); 
      const json = await res.json();
      const tbody = document.getElementById('historyTable');
      tbody.innerHTML = '';

      if (!json.data || json.data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="12" class="text-center text-muted">Belum ada history</td></tr>`;
        return;
      }

      json.data.forEach(item => {
  let statusText = "Pending";
  let badgeClass = "secondary";

  const hr = item.status_hr ? item.status_hr.toLowerCase() : "";
  const mng = item.status_management ? item.status_management.toLowerCase() : "";
  const rek = item.status_rekrutmen ? item.status_rekrutmen.toLowerCase() : "";

  if (rek === "selesai") {
    statusText = "Rekrutmen Selesai";
    badgeClass = "success";
  }
  else if (mng === "rejected") {
    statusText = "Mng Rejected";
    badgeClass = "danger";
  }
  else if (hr === "rejected") {
    statusText = "HR Rejected";
    badgeClass = "danger";
  }
  else if (mng === "accepted") {
    statusText = "Mng Accepted";
    badgeClass = "primary";
  }
  else if (hr === "accepted") {
    statusText = "HR Accepted";
    badgeClass = "primary";
  }

  const badge = `<span class="badge bg-${badgeClass}">${statusText}</span>`;

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
