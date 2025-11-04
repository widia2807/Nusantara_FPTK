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
      <h2>Dashboard Divisi</h2>
      <!-- Dropdown profil -->
      <div class="dropdown text-end">
        <button class="btn btn-light d-flex align-items-center gap-2 shadow-sm" 
                type="button" data-bs-toggle="dropdown" aria-expanded="false" 
                style="border-radius: 50px;">
          <img id="profilePic" 
               src="<?= base_url('uploads/profile/default.png') ?>" 
               class="rounded-circle border border-primary" 
               width="32" height="32" 
               style="object-fit: cover;">
          <span class="fw-semibold">Divisi</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 p-3" 
            style="width: 250px;">
          <div class="text-center">
            <img id="profilePreview" 
                 src="<?= base_url('uploads/profile/default.png') ?>" 
                 class="rounded-circle mb-2 border border-2 border-primary" 
                 width="70" height="70" 
                 style="object-fit: cover;">
            <h6 class="fw-bold mb-0"><?= session()->get('nama_user') ?? 'Nama Divisi' ?></h6>
            <p class="text-muted small mb-2"><?= session()->get('email_user') ?? 'divisi@example.com' ?></p>
            <input type="file" id="uploadProfile" accept="image/*" 
                   class="form-control form-control-sm mb-2" onchange="previewProfile(event)">
            <button class="btn btn-primary btn-sm w-100 mb-2" onclick="saveProfile()">Simpan Foto</button>
            <hr class="my-2">
            <a href="<?= base_url('logout') ?>" class="btn btn-outline-danger btn-sm w-100">Logout</a>
          </div>
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
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Detail Pengajuan Rekrutmen</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" style="max-height:75vh; overflow-y:auto;">
        <form id="rekForm">
          <input type="hidden" id="detailId">

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Divisi</label>
              <div class="input-group">
                <input type="text" id="detailDivisi" class="form-control" disabled>
                <button class="btn btn-outline-secondary" type="button" onclick="copyField('detailDivisi')">📋</button>
              </div>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Posisi</label>
              <div class="input-group">
                <input type="text" id="detailPosisi" class="form-control" disabled>
                <button class="btn btn-outline-secondary" type="button" onclick="copyField('detailPosisi')">📋</button>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Cabang</label>
              <div class="input-group">
                <input type="text" id="detailCabang" class="form-control" disabled>
                <button class="btn btn-outline-secondary" type="button" onclick="copyField('detailCabang')">📋</button>
              </div>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Jumlah Karyawan</label>
              <div class="input-group">
                <input type="text" id="detailJumlah" class="form-control" disabled>
                <button class="btn btn-outline-secondary" type="button" onclick="copyField('detailJumlah')">📋</button>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Job Post Number</label>
              <div class="input-group">
                <input type="text" id="detailJobPost" class="form-control" disabled>
                <button class="btn btn-outline-secondary" type="button" onclick="copyField('detailJobPost')">📋</button>
              </div>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Tipe Pekerjaan</label>
              <div class="input-group">
                <input type="text" id="detailTipe" class="form-control" disabled>
                <button class="btn btn-outline-secondary" type="button" onclick="copyField('detailTipe')">📋</button>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Range Umur</label>
              <div class="input-group">
                <input type="text" id="detailUmur" class="form-control" disabled>
                <button class="btn btn-outline-secondary" type="button" onclick="copyField('detailUmur')">📋</button>
              </div>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Tanggal</label>
              <div class="input-group">
                <input type="text" id="detailTanggal" class="form-control" disabled>
                <button class="btn btn-outline-secondary" type="button" onclick="copyField('detailTanggal')">📋</button>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Min Gaji</label>
              <div class="input-group">
                <input type="number" id="detailMinGaji" class="form-control" disabled>
                <button class="btn btn-outline-secondary" type="button" onclick="copyField('detailMinGaji')">📋</button>
              </div>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Max Gaji</label>
              <div class="input-group">
                <input type="number" id="detailMaxGaji" class="form-control" disabled>
                <button class="btn btn-outline-secondary" type="button" onclick="copyField('detailMaxGaji')">📋</button>
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Kualifikasi</label>
            <div class="input-group">
              <textarea id="detailKualifikasi" class="form-control" rows="3" disabled></textarea>
              <button class="btn btn-outline-secondary" type="button" onclick="copyField('detailKualifikasi')">📋</button>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label">Status HR</label>
              <input type="text" id="detailStatusHR" class="form-control" disabled>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Status Management</label>
              <input type="text" id="detailStatusMng" class="form-control" disabled>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Status Rekrutmen</label>
              <input type="text" id="detailStatusRek" class="form-control" disabled>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Comment</label>
            <div class="input-group">
              <textarea id="detailComment" class="form-control" rows="3" disabled></textarea>
              <button class="btn btn-outline-secondary" type="button" onclick="copyField('detailComment')">📋</button>
            </div>
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="btnSelesai" onclick="selesaiPengajuan()">Tandai Selesai</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
  document.getElementById('detailJobPost').value = data.job_post_number || '';
  document.getElementById('detailTipe').value = data.tipe_pekerjaan || '';
  document.getElementById('detailUmur').value = data.range_umur || '';
  document.getElementById('detailTanggal').value = data.created_at || '';
  document.getElementById('detailMinGaji').value = data.min_gaji || '';
  document.getElementById('detailMaxGaji').value = data.max_gaji || '';
  document.getElementById('detailKualifikasi').value = data.kualifikasi || '';
  document.getElementById('detailStatusHR').value = data.status_hr || '';
  document.getElementById('detailStatusMng').value = data.status_management || '';
  document.getElementById('detailStatusRek').value = data.status_rekrutmen || '';
  document.getElementById('detailComment').value = data.comment || '';

  new bootstrap.Modal(document.getElementById('detailModal')).show();
}

function copyField(id) {
  const el = document.getElementById(id);
  const value = el.value || el.textContent;
  navigator.clipboard.writeText(value).then(() => {
    alert("Disalin: " + value);
  }).catch(err => {
    console.error("Gagal copy", err);
  });
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
