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
    try {
      const res = await fetch('http://localhost/nusantara_api/public/api/history');
      const json = await res.json();

      const tbody = document.getElementById('historyTable');
      tbody.innerHTML = '';

      const rows = json?.data || [];
      if (rows.length === 0) {
        tbody.innerHTML = `<tr><td colspan="12" class="text-center text-muted">Belum ada history</td></tr>`;
        return;
      }

      rows.forEach(item => {
        // Ambil label & warna badge dari API; fallback kalau belum ada
        const label = item.label || 'Pending';
        const badgeColor = item.badge || 'secondary';
        const badge = `<span class="badge bg-${badgeColor}">${label}</span>`;

        tbody.innerHTML += `
          <tr>
            <td>${item.id_pengajuan}</td>
            <td>${item.nama_divisi ?? '-'}</td>
            <td>${item.nama_posisi ?? '-'}</td>
            <td>${item.nama_cabang ?? '-'}</td>
            <td>${item.jumlah_karyawan ?? '-'}</td>
            <td>${item.job_post_number ?? '-'}</td>
            <td>${item.tipe_pekerjaan ?? '-'}</td>
            <td>${item.created_at ?? '-'}</td>
            <td>${item.full_name || '-'}</td>
            <td>${item.role_user || '-'}</td>
            <td>${badge}</td>
            <td>${item.comment || '-'}</td>
          </tr>
        `;
      });
    } catch (e) {
      console.error(e);
      document.getElementById('historyTable').innerHTML =
        `<tr><td colspan="12" class="text-center text-danger">Gagal memuat data</td></tr>`;
    }
  }

  loadHistory();
</script>


</body>
</html>
