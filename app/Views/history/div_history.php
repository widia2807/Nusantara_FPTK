<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>History Pengajuan - Divisi</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- CSS Global Divisi -->
  <link rel="stylesheet" href="<?= base_url('assets/css/divisi.css') ?>">
</head>

<body class="role-divisi page-history">

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="text-center mb-4">
      <img src="<?= base_url('assets/images/logo-nusantara-group.png') ?>" alt="Logo" height="40">
      <h6 class="mt-2">Nusantara Portal</h6>
    </div>
    <a href="<?= base_url('dashboard/divisi') ?>">📊 Dashboard</a>
    <a href="<?= base_url('pengajuan') ?>">
      <img src="<?= base_url('assets/images/checklist.png') ?>" alt="Pengajuan" height="18" class="me-2">
      Pengajuan
    </a>
    <a href="<?= base_url('history/divisi') ?>">📂 History</a>
  </div>

  <!-- Content -->
  <div class="content">
    <div class="card p-4">
      <h4 class="mb-3">History Pengajuan Divisi</h4>
      <div class="table-responsive">
        <table class="table table-hover table-sm align-middle">
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
              <th>Status</th>
              <th>Reviewer</th>
              <th>Role</th>
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
        tbody.innerHTML = `<tr><td colspan="12" class="text-center">Belum ada history</td></tr>`;
        return;
      }

      json.data.forEach(item => {
  let statusBadge = `<span class="badge bg-secondary">Pending</span>`;
  let reviewer = item.full_name || "-";
  let role = item.role_user || "-";

  // Status badge berdasar role dan status terkait
  if (item.role_user === 'HR') {
    if (item.status_hr?.toLowerCase() === 'approved') {
      statusBadge = `<span class="badge bg-primary">HR Approved</span>`;
    } else if (item.status_hr?.toLowerCase() === 'rejected') {
      statusBadge = `<span class="badge bg-danger">HR Rejected</span>`;
    }
  } 
  else if (item.role_user === 'Management') {
    if (item.status_management?.toLowerCase() === 'approved') {
      statusBadge = `<span class="badge bg-primary">Mng Approved</span>`;
    } else if (item.status_management?.toLowerCase() === 'rejected') {
      statusBadge = `<span class="badge bg-danger">Mng Rejected</span>`;
    }
  } 
  else if (item.role_user === 'Rekrutmen') {
    if (item.status_rekrutmen?.toLowerCase() === 'selesai') {
      statusBadge = `<span class="badge bg-success">Rekrutmen Selesai</span>`;
    }
  }

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
      <td>${statusBadge}</td>
      <td>${reviewer}</td>
      <td>${role}</td>
      <td>${item.comment || '-'}</td>
    </tr>
  `;
});

    }

    loadHistory();
  </script>

</body>
</html>
