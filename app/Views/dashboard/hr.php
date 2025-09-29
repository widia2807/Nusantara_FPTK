<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard HR - Nusantara Portal</title>
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

    /* Header */
    h2 {
      font-weight: 700;
      color: #0d6efd;
    }

    /* Cards */
    .card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      transition: transform 0.2s ease;
    }
    .card:hover {
      transform: translateY(-3px);
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

    /* Badge */
    .badge {
      font-size: 11px;
      padding: 5px 8px;
      border-radius: 8px;
    }

    /* Modal */
    .modal-content {
      border-radius: 12px;
      border: none;
      box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }
    .modal-header {
      background: linear-gradient(90deg, #0d6efd, #0dcaf0);
      color: #fff;
      border-top-left-radius: 12px;
      border-top-right-radius: 12px;
    }
    .modal-title {
      font-weight: 600;
    }
    .modal-footer .btn {
      border-radius: 8px;
      font-weight: 500;
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
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Dashboard HR</h2>
      <div class="dropdown">
        <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
          <img src="https://via.placeholder.com/30" class="rounded-circle"> HR
        </button>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="#">Profile</a></li>
          <li><a class="dropdown-item" href="#">Settings</a></li>
          <li><a href="<?= base_url('logout') ?>" class="dropdown-item text-danger">Logout</a></li>
        </ul>
      </div>
    </div>

    <!-- Cards -->
    <div class="row mb-4">
      <div class="col-md-4">
        <div class="card text-center">
          <div class="card-body">
            <h6 class="text-muted">Belum Direview</h6>
            <h3 id="cardPending">0</h3>
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

    <!-- Table -->
    <div class="card shadow mt-4">
      <div class="card-body">
        <h5 class="mb-3">Review Pengajuan HR</h5>
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
                <th>Status HR</th>
                <th>Status Mng</th>
                <th>Status Rek</th>
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

  <!-- Modal Detail -->
<div class="modal fade" id="detailModal" tabindex="-1">
  <div class="modal-dialog modal-xl"> <!-- ✅ lebih besar -->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detail Pengajuan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="hrForm">
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
              <input type="number" id="detailJumlah" class="form-control" disabled>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Job Post Number</label>
              <input type="text" id="detailJobPost" class="form-control" disabled>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Tipe Pekerjaan</label>
              <input type="text" id="detailTipe" class="form-control" disabled>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Range Umur</label>
              <input type="text" id="detailUmur" class="form-control" disabled>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Tanggal Pengajuan</label>
              <input type="text" id="detailTanggal" class="form-control" disabled>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Kualifikasi</label>
            <textarea id="detailKualifikasi" class="form-control" rows="3" disabled></textarea>
          </div>

          <div class="row" id="gajiRow" style="display:none;">
            <div class="col-md-6 mb-3">
              <label class="form-label">Min Gaji</label>
              <input type="number" id="minGaji" class="form-control" placeholder="5000000">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Max Gaji</label>
              <input type="number" id="maxGaji" class="form-control" placeholder="7000000">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Komentar</label>
            <textarea id="detailComment" class="form-control" rows="3"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer d-flex justify-content-end gap-2">
        <button type="button" class="btn btn-success" id="btnAccept" onclick="acceptPengajuan()">Accept</button>
        <button type="button" class="btn btn-danger" id="btnReject" onclick="rejectPengajuan()">Reject</button>
        <button type="button" class="btn btn-primary" id="btnSend" onclick="sendPengajuan()" disabled>Send</button>
      </div>
    </div>
  </div>
</div>

 
  <script>
let currentId = null;

async function loadPengajuan() {
  const res = await fetch('http://10.101.56.69:8080/api/pengajuan');
  const json = await res.json();
  const tbody = document.getElementById('pengajuanTable');
  tbody.innerHTML = '';

  if (!json.data || json.data.length === 0) {
    tbody.innerHTML = `<tr><td colspan="12" class="text-center">Belum ada data pengajuan</td></tr>`;
    document.getElementById('cardPending').textContent = 0;
    document.getElementById('cardApproved').textContent = 0;
    document.getElementById('cardRejected').textContent = 0;
    return;
  }

  let pendingCount = 0, approvedCount = 0, rejectedCount = 0;

  json.data.forEach(item => {
    const hr = (item.status_hr || '').toLowerCase();
    const mng = (item.status_management || '').toLowerCase();

    if (hr === 'pending') pendingCount++;
    else if (hr === 'approved') approvedCount++;
    else if (hr === 'rejected') rejectedCount++;

    // ❌ skip kalau archived
    if (item.archived == 1) return;

    // ❌ skip kalau HR sudah reject
    if (item.status_hr === 'Rejected') return;

    // ❌ skip kalau Management sudah reject
    if (item.status_management === 'Rejected') return;

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
        <td>${badgeHR}</td>
        <td>${badgeMng}</td>
        <td>${badgeRek}</td>
        <td>
          <button class="btn btn-sm btn-info" data-item='${JSON.stringify(item)}' onclick="showDetail(this)">Detail</button>
        </td>
      </tr>`;
  });

  document.getElementById('cardPending').textContent = pendingCount;
  document.getElementById('cardApproved').textContent = approvedCount;
  document.getElementById('cardRejected').textContent = rejectedCount;
}



function showDetail(btn) {
  const data = JSON.parse(btn.getAttribute('data-item'));
  currentId = data.id_pengajuan;

  document.getElementById('detailId').value = data.id_pengajuan;
  document.getElementById('detailDivisi').value = data.nama_divisi;
  document.getElementById('detailPosisi').value = data.nama_posisi;
  document.getElementById('detailUmur').value = data.range_umur || '';
  document.getElementById('detailKualifikasi').value = data.kualifikasi || '';
  document.getElementById('detailCabang').value = data.nama_cabang || '';
document.getElementById('detailJumlah').value = data.jumlah_karyawan || '';
document.getElementById('detailJobPost').value = data.job_post_number || '';
document.getElementById('detailTipe').value = data.tipe_pekerjaan || '';
document.getElementById('detailTanggal').value = data.created_at || '';



  document.getElementById('detailComment').value = data.comment || '';

  // default reset
  document.getElementById('minGaji').value = '';
  document.getElementById('maxGaji').value = '';
  document.getElementById('gajiRow').style.display = "none";

  // reset tombol
  document.getElementById('btnSend').disabled = true;
  document.getElementById('btnAccept').disabled = false;
  document.getElementById('btnReject').disabled = false;

  // cek status untuk atur tombol
  if (data.status_hr === 'Approved' && data.status_management === 'Pending') {
    // artinya sudah Accept tapi belum send → form gaji tampil
    document.getElementById('gajiRow').style.display = "flex";
    document.getElementById('btnSend').disabled = false;
    document.getElementById('btnAccept').disabled = true;
    document.getElementById('btnReject').disabled = true;

    // kalau ada gaji yang sudah pernah disimpan, tampilkan
    if (data.min_gaji) document.getElementById('minGaji').value = data.min_gaji;
    if (data.max_gaji) document.getElementById('maxGaji').value = data.max_gaji;
  }

  if (data.status_management && data.status_management !== 'Pending') {
    // kalau sudah dikirim ke management → semua tombol disable
    document.getElementById('btnAccept').disabled = true;
    document.getElementById('btnReject').disabled = true;
    document.getElementById('btnSend').disabled = true;

    // tampilkan gaji readonly
    document.getElementById('gajiRow').style.display = "flex";
    if (data.min_gaji) document.getElementById('minGaji').value = data.min_gaji;
    if (data.max_gaji) document.getElementById('maxGaji').value = data.max_gaji;
    document.getElementById('minGaji').readOnly = true;
    document.getElementById('maxGaji').readOnly = true;
  }

  new bootstrap.Modal(document.getElementById('detailModal')).show();
}


async function acceptPengajuan() {
  try {
    // panggil API untuk update status_hr
    const res = await fetch(`http://10.101.56.69:8080/api/pengajuan/${currentId}/hr-review`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        status_hr: 'Approved',
        action: 'accept'
      })
    });

    if (!res.ok) {
      const err = await res.json();
      alert("Gagal update status: " + (err.message || res.status));
      return;
    }

    // tampilkan form gaji & aktifkan tombol send
    document.getElementById('gajiRow').style.display = "flex";
    document.getElementById('btnSend').disabled = false;

    // disable tombol accept & reject
    document.getElementById('btnAccept').disabled = true;
    document.getElementById('btnReject').disabled = true;

    // refresh data tabel biar status langsung kelihatan "Approved"
    loadPengajuan();
  } catch (e) {
    console.error(e);
    alert("Terjadi error saat update status HR");
  }
}


async function rejectPengajuan() {
  const comment = document.getElementById('detailComment').value;
  if (!comment.trim()) {
    alert("Harus isi alasan jika Reject!");
    return;
  }

  await fetch(`http://10.101.56.69:8080/nusantara_api/public/api/pengajuan/${currentId}/hr-review`, {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ status_hr: 'Rejected', comment })
  });

  bootstrap.Modal.getInstance(document.getElementById('detailModal')).hide();
  loadPengajuan();
}

async function sendPengajuan() {
  const minGaji = document.getElementById('minGaji').value;
  const maxGaji = document.getElementById('maxGaji').value;

  if (!minGaji || !maxGaji) {
    alert("Isi range gaji dulu sebelum kirim!");
    return;
  }

  await fetch(`http://10.101.56.69:8080/api/pengajuan/${currentId}/hr-review`, {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      status_hr: 'Approved',
      min_gaji: minGaji,
      max_gaji: maxGaji,
      action: 'send'
    })
  });

  // disable tombol send setelah kirim
  document.getElementById('btnSend').disabled = true;

  bootstrap.Modal.getInstance(document.getElementById('detailModal')).hide();
  loadPengajuan();
}

loadPengajuan();
</script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
