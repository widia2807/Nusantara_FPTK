<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>History Pengajuan - HR</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/admin-shared.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/admin-history.css') ?>">
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="text-center mb-4">
      <img src="<?= base_url('assets/images/logo-nusantara-group.png') ?>" alt="Logo" height="40">
      <h6 class="mt-2">Nusantara Portal</h6>
    </div>
    <a href="<?= base_url('dashboard/hr') ?>">📊 Dashboard</a>
    <a href="<?= base_url('admin_menu/create') ?>">➕ Tambah Akun</a>
    <a href="<?= base_url('admin_menu/manage') ?>">👥 Manajemen User</a>
    <a href="<?= base_url('admin_menu/manage_all') ?>" class="active">⚙️ Kelola Data</a>
    <a href="<?= base_url('history/hr') ?>">📂 History</a>
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
      const res = await fetch('http://localhost/nusantara_api/public/api/history'); 
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
