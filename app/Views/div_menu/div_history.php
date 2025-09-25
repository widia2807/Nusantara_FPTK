<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>History Pengajuan - Divisi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
    }
    .sidebar {
      width: 220px;
      position: fixed;
      top: 0;
      left: 0;
      height: 100%;
      background: #f8f9fa;
      border-right: 1px solid #ddd;
      padding-top: 20px;
    }
    .sidebar a {
      display: block;
      padding: 10px 20px;
      color: #333;
      text-decoration: none;
    }
    .sidebar a:hover {
      background: #e9ecef;
    }
    .content {
      margin-left: 220px;
      padding: 20px;
    }
    

    /* Tabel compact */
    .table-compact th, 
    .table-compact td {
      padding: 4px 6px !important;
      font-size: 12px;
      white-space: nowrap;
      text-overflow: ellipsis;
      overflow: hidden;
      vertical-align: middle;
    }

    /* Batasi lebar kolom */
    .table-compact th:nth-child(1), .table-compact td:nth-child(1) { width: 40px; }   /* ID */
    .table-compact th:nth-child(2), .table-compact td:nth-child(2) { max-width: 80px; } /* Divisi */
    .table-compact th:nth-child(3), .table-compact td:nth-child(3) { max-width: 100px; } /* Posisi */
    .table-compact th:nth-child(4), .table-compact td:nth-child(4) { max-width: 80px; } /* Cabang */
    .table-compact th:nth-child(5), .table-compact td:nth-child(5) { width: 50px; }   /* Jumlah */
    .table-compact th:nth-child(6), .table-compact td:nth-child(6) { width: 80px; }   /* Job Post */
    .table-compact th:nth-child(7), .table-compact td:nth-child(7) { width: 80px; }   /* Tipe */
    .table-compact th:nth-child(8), .table-compact td:nth-child(8) { width: 110px; }  /* Tanggal */
    .table-compact th.status-col, .table-compact td.status-col { width: 60px; text-align: center; }
    .table-compact th:last-child, .table-compact td:last-child { width: 65px; text-align: center; } /* Detail */
  </style>
</head>
<body>

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
    
  </div>z

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


  <script>
    async function loadHistory() {
      const res = await fetch('http://localhost/nusantara_api/public/api/history');
      const json = await res.json();
      console.log(json); 
      const tbody = document.getElementById('historyTable');
      tbody.innerHTML = '';

      if (!json.data || json.data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="11" class="text-center">Belum ada history</td></tr>`;
        return;
      }

      json.data.forEach(item => {
  // hanya tampilkan yang action = Rejected
  if (item.action === 'Rejected') {
    const badge = `<span class="badge bg-danger">Rejected</span>`;

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
        <td>${item.role_user}</td>
        <td>${badge}</td>
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
