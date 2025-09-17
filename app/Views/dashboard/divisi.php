<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Divisi - Nusantara Portal</title>
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
    footer {
      margin-left: 220px;
      background: #222;
      color: #fff;
      text-align: center;
      padding: 15px;
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
   <a href="<?= base_url('history') ?>">📂 History</a>
    <a href="#" class="btn btn-dark w-100 mt-4">Logout</a>
  </div>

  <!-- Content -->
  <div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Dashboard Divisi</h2>
      <div class="dropdown">
        <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
          <img src="https://via.placeholder.com/30" class="rounded-circle"> Divisi
        </button>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="#">Profile</a></li>
          <li><a class="dropdown-item" href="#">Settings</a></li>
          <li><a href="<?= base_url('logout') ?>" class="btn btn-dark w-100 mt-4">Logout</a></li>
        </ul>
      </div>
    </div>

   <!-- Cards -->
<div class="row mb-4">
  <div class="col-md-4">
    <div class="card text-center">
      <div class="card-body">
        <h6 class="text-muted">Total Pengajuan</h6>
        <h3 id="cardTotal">0</h3>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card text-center">
      <div class="card-body">
        <h6 class="text-muted">Disetujui</h6>
        <h3 id="cardApproved">0</h3>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card text-center">
      <div class="card-body">
        <h6 class="text-muted">Ditolak</h6>
        <h3 id="cardRejected">0</h3>
      </div>
    </div>
  </div>
</div>

    <!-- Tabel Status Pengajuan -->
    <div class="card shadow mt-4">
      <div class="card-body">
        <h5 class="mb-3">Status Pengajuan Divisi</h5>
        <div class="table-responsive">
          <table class="table table-hover table-sm table-compact align-middle">
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
                <th class="status-col">HR</th>
                <th class="status-col">Mng</th>
                <th class="status-col">Rek</th>
                <th>Detail</th>
              </tr>
            </thead>
            <tbody id="pengajuanTable">
              <tr><td colspan="12" class="text-center">Loading...</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer>
    NusantaraIT © 2025. All rights reserved.
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 <script>
  async function loadPengajuan() {
    const res = await fetch('http://localhost/nusantara_api/public/api/pengajuan');
    const json = await res.json();
    const tbody = document.getElementById('pengajuanTable');
    tbody.innerHTML = '';

    if (json.data.length === 0) {
      tbody.innerHTML = `<tr><td colspan="12" class="text-center">Belum ada data pengajuan</td></tr>`;
      return;
    }

    // --- Hitung statistik ---
    let total = json.data.length;
    let approved = 0;
    let rejected = 0;

    json.data.forEach(item => {
      if (item.status_hr === 'Approved' && item.status_management === 'Approved') {
        approved++;
      } else if (item.status_hr === 'Rejected' || item.status_management === 'Rejected') {
        rejected++;
      }
    });

    // --- Tampilkan di Cards ---
    document.getElementById('cardTotal').innerText = total;
    document.getElementById('cardApproved').innerText = approved;
    document.getElementById('cardRejected').innerText = rejected;

    // --- Render tabel, skip yang Rejected ---
    json.data.forEach(item => {
      if (item.status_hr === 'Rejected' || item.status_management === 'Rejected') {
        return; // <-- skip tampil di dashboard
      }

      const badgeHR  = `<span class="badge bg-${item.status_hr === 'Approved' ? 'success' : item.status_hr === 'Rejected' ? 'danger' : 'secondary'}">${item.status_hr}</span>`;
      const badgeMng = `<span class="badge bg-${item.status_management === 'Approved' ? 'success' : item.status_management === 'Rejected' ? 'danger' : 'secondary'}">${item.status_management}</span>`;
      const badgeRek = `<span class="badge bg-${item.status_rekrutmen === 'Selesai' ? 'success' : 'secondary'}">${item.status_rekrutmen}</span>`;

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
          <td class="status-col">${badgeHR}</td>
          <td class="status-col">${badgeMng}</td>
          <td class="status-col">${badgeRek}</td>
          <td><button class="btn btn-sm btn-info" data-item='${JSON.stringify(item)}' onclick="showDetail(this)">Detail</button></td>
        </tr>
      `;
    });
  }

  function showDetail(btn) {
    const data = JSON.parse(btn.getAttribute('data-item'));

    document.getElementById('detailDivisi').value      = data.nama_divisi;
    document.getElementById('detailPosisi').value      = data.nama_posisi;
    document.getElementById('detailCabang').value      = data.nama_cabang;
    document.getElementById('detailJumlah').value      = data.jumlah_karyawan;
    document.getElementById('detailJobPost').value     = data.job_post_number;
    document.getElementById('detailTipe').value        = data.tipe_pekerjaan;
    document.getElementById('detailUmur').value        = data.range_umur;
    document.getElementById('detailTempat').value      = data.tempat_kerja;
    document.getElementById('detailKualifikasi').value = data.kualifikasi;
    document.getElementById('detailCreated').value     = data.created_at;

    const modal = new bootstrap.Modal(document.getElementById('detailModal'));
    modal.show();
  }

  loadPengajuan();
</script>


  <!-- Modal Detail Pengajuan -->
  <div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Detail Pengajuan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Divisi</label>
                <input type="text" id="detailDivisi" class="form-control" disabled>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Posisi</label>
                <input type="text" id="detailPosisi" class="form-control" disabled>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Cabang</label>
                <input type="text" id="detailCabang" class="form-control" disabled>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Jumlah Karyawan</label>
                <input type="text" id="detailJumlah" class="form-control" disabled>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Job Post Number</label>
              <input type="text" id="detailJobPost" class="form-control" disabled>
            </div>

            <div class="mb-3">
              <label class="form-label">Tipe Pekerjaan</label>
              <input type="text" id="detailTipe" class="form-control" disabled>
            </div>

            <div class="mb-3">
              <label class="form-label">Range Umur</label>
              <input type="text" id="detailUmur" class="form-control" disabled>
            </div>

            <div class="mb-3">
              <label class="form-label">Tempat Kerja</label>
              <input type="text" id="detailTempat" class="form-control" disabled>
            </div>

            <div class="mb-3">
              <label class="form-label">Kualifikasi</label>
              <textarea id="detailKualifikasi" class="form-control" rows="3" disabled></textarea>
            </div>

            <div class="mb-3">
              <label class="form-label">Tanggal Dibuat</label>
              <input type="text" id="detailCreated" class="form-control" disabled>
            </div>

             <!-- Tambahan Comment HR -->
          <div class="mb-3">
            <label class="form-label">Comment HR</label>
            <textarea id="detailCommentHR" class="form-control" rows="2" disabled></textarea>
          </div>

          <!-- Tambahan Comment Management -->
          <div class="mb-3">
            <label class="form-label">Comment Management</label>
            <textarea id="detailCommentMng" class="form-control" rows="2" disabled></textarea>
          </div>

          </form>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
