<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Rekrutmen - Nusantara Portal</title>
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
    .sidebar a:hover {
      background: #e7f1ff;
      border-left: 3px solid #0d6efd;
      color: #0d6efd;
    }

    /* Content */
    .content {
      margin-left: 220px;
      padding: 25px;
    }

    h2 {
      font-weight: 700;
      color: #0d6efd;
    }

    /* Cards */
    .card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .card h3 {
      font-weight: 700;
      color: #0d6efd;
    }

    /* Table */
    .table thead {
      background: linear-gradient(90deg, #0d6efd, #0dcaf0);
      color: #fff;
    }
    .table-hover tbody tr:hover {
      background: #f1f5ff;
    }

    .badge {
      font-size: 11px;
      padding: 5px 8px;
      border-radius: 8px;
    }

    /* Modal besar */
    .modal-dialog {
      max-width: 900px;
    }
    .modal-body textarea {
      font-size: 14px;
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
    <a href="<?= base_url('history/rekrutmen') ?>">📂 History</a>
  </div>

  <!-- Content -->
  <div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Dashboard Rekrutmen</h2>
      <div class="dropdown">
        <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
          <img src="https://via.placeholder.com/30" class="rounded-circle"> Rekrutmen
        </button>
        <ul class="dropdown-menu">
          <li><a href="<?= base_url('logout') ?>" class="dropdown-item text-danger">Logout</a></li>
        </ul>
      </div>
    </div>

    <!-- Cards -->
    <div class="row mb-4">
      <div class="col-md-6">
        <div class="card text-center">
          <div class="card-body">
            <h6 class="text-muted">Pending</h6>
            <h3 id="cardPending">0</h3>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card text-center">
          <div class="card-body">
            <h6 class="text-muted">Selesai</h6>
            <h3 id="cardDone">0</h3>
          </div>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="card shadow mt-4">
      <div class="card-body">
        <h5 class="mb-3">Review Pengajuan Rekrutmen</h5>
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
                <th>Detail</th>
              </tr>
            </thead>
            <tbody id="pengajuanTable">
              <tr><td colspan="10" class="text-center">Loading...</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Detail -->
  <div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Detail Pengajuan Rekrutmen</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="rekForm">
            <input type="hidden" id="detailId">

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
              <label class="form-label">Kualifikasi</label>
              <textarea id="detailKualifikasi" class="form-control" rows="3" disabled></textarea>
            </div>

            <div class="mb-3">
              <label class="form-label">Comment</label>
              <textarea id="detailComment" class="form-control" rows="3" disabled></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer d-flex justify-content-end gap-2">
          <button type="button" class="btn btn-success" id="btnSelesai" onclick="selesaiPengajuan()">Selesai</button>
        </div>
      </div>
    </div>
  </div>

  <script>
let currentId = null;

async function loadPengajuanRekrutmen() {
  const res = await fetch('http://localhost/nusantara_api/public/api/pengajuan');
  const json = await res.json();
  const tbody = document.getElementById('pengajuanTable');
  tbody.innerHTML = '';

  if (!json.data || json.data.length === 0) {
    tbody.innerHTML = `<tr><td colspan="10" class="text-center">Belum ada data pengajuan</td></tr>`;
    return;
  }

  let pendingCount = 0, doneCount = 0;

  json.data.forEach(item => {
    // hanya tampilkan yg sudah lolos HR & Mng
    if (item.status_hr !== 'Approved' || item.status_management !== 'Approved') return;

    if (item.status_rekrutmen === 'Pending') pendingCount++;
    else if (item.status_rekrutmen === 'Selesai') doneCount++;

    const badgeRek = `<span class="badge bg-${item.status_rekrutmen === 'Selesai' ? 'success' : 'secondary'}">${item.status_rekrutmen}</span>`;

    // hanya tampilkan yg masih Pending di dashboard
    if (item.status_rekrutmen === 'Pending') {
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
          <td>${badgeRek}</td>
          <td><button class="btn btn-sm btn-info" data-item='${JSON.stringify(item)}' onclick="showDetail(this)">Detail</button></td>
        </tr>`;
    }
  });

  document.getElementById('cardPending').textContent = pendingCount;
  document.getElementById('cardDone').textContent = doneCount;
}

function showDetail(btn) {
  const data = JSON.parse(btn.getAttribute('data-item'));
  currentId = data.id_pengajuan;

  document.getElementById('detailId').value = data.id_pengajuan;
  document.getElementById('detailDivisi').value = data.nama_divisi;
  document.getElementById('detailPosisi').value = data.nama_posisi;
  document.getElementById('detailCabang').value = data.nama_cabang;
  document.getElementById('detailJumlah').value = data.jumlah_karyawan;
  document.getElementById('detailKualifikasi').value = data.kualifikasi || '';
  document.getElementById('detailComment').value = data.comment || '';

  new bootstrap.Modal(document.getElementById('detailModal')).show();
}

async function selesaiPengajuan() {
  try {
    await fetch(`http://localhost/nusantara_api/public/api/pengajuan/${currentId}/rekrutmen-review`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ status_rekrutmen: 'Selesai' })
    });

    bootstrap.Modal.getInstance(document.getElementById('detailModal')).hide();
    loadPengajuanRekrutmen();
  } catch (e) {
    console.error(e);
    alert("Gagal update status rekrutmen");
  }
}

loadPengajuanRekrutmen();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
