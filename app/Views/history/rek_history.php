<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>History Pengajuan - Rekrutmen</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background-color: #f9fafc;
      color: #212529;
    }

    /* Sidebar */
    .sidebar {
      width: 220px;
      position: fixed;
      top: 0;
      left: 0;
      height: 100%;
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
    .sidebar a:hover,
    .sidebar a.active {
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
    .card-custom {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      padding: 25px 30px;
    }
    .card-custom h4 {
      color: #0d6efd;
      font-weight: 700;
      margin-bottom: 20px;
    }

    /* Table */
    .table-custom th {
      background: #0d6efd !important;
      color: #fff;
      font-size: 14px;
    }
    .table-custom td {
      font-size: 13px;
      vertical-align: middle;
    }
    .badge {
      font-size: 12px;
      padding: 5px 10px;
      border-radius: 8px;
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
    <a href="<?= base_url('dashboard/rekrutmen') ?>">📊 Dashboard</a>
    <a href="<?= base_url('history/rekrutmen') ?>" class="active">📂 History</a>
  </div>

  <!-- Content -->
  <div class="content">
    <div class="card-custom">
      <h4>History Pengajuan Rekrutmen</h4>
      <div class="table-responsive">
        <table class="table table-hover table-striped table-custom">
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
            <tr><td colspan="11" class="text-center">Loading...</td></tr>
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
        tbody.innerHTML = `<tr><td colspan="11" class="text-center">Belum ada history</td></tr>`;
        return;
      }

      // ✅ Tampilkan hanya entri dari Rekrutmen yang SUDAH approved HR & Management
      const filtered = rows.filter(item => {
        const roleOk = item.role_user === 'Rekrutmen';
        const hr = (item.status_hr || '').toLowerCase();
        const mg = (item.status_management || '').toLowerCase();
        const approvedBoth = (hr === 'approved' && mg === 'approved');
        return roleOk && approvedBoth;
      });

      if (filtered.length === 0) {
        tbody.innerHTML = `<tr><td colspan="11" class="text-center">Belum ada history (menunggu Approve HR & Management)</td></tr>`;
        return;
      }

      filtered.forEach(item => {
        // Pakai label & badge dari API (fallback kalau belum ada)
        let label = item.label || 'Pending';
        let badgeColor = item.badge || 'secondary';

        // Fallback kecil jika server belum mengirim label/badge
        if (!item.label || !item.badge) {
          const a  = (item.action || '').toLowerCase();
          const rk = (item.status_rekrutmen || '').toLowerCase();
          if (a.startsWith('reject') || a === 'rejected') label = 'Rejected';
          else if (a.includes('approve') || a === 'approved') label = 'Approved';
          else if (['selesai','done','complete'].includes(rk)) label = 'Rekrutmen Selesai';
          badgeColor = (label === 'Rejected') ? 'danger'
                     : (label === 'Approved' || label === 'Rekrutmen Selesai') ? 'primary'
                     : 'secondary';
        }

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
            <td>${badge}</td>
            <td>${item.comment || '-'}</td>
          </tr>
        `;
      });
    } catch (e) {
      console.error(e);
      document.getElementById('historyTable').innerHTML =
        `<tr><td colspan="11" class="text-center text-danger">Gagal memuat data</td></tr>`;
    }
  }

  loadHistory();
</script>


</body>
</html>
