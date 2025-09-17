<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Nusantara Portal</title>
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
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="text-center mb-4">
      <img src="<?= base_url('assets/images/logo-nusantara-group.png') ?>" alt="Logo" height="40">
      <h6 class="mt-2">Nusantara Portal</h6>
    </div>
    <a href="#">📊 Dashboard</a>
    <a href="<?= base_url('users/create') ?>">
  <img src="https://img.icons8.com/ios-filled/50/000000/add-user-male.png" width="30"> Tambah Akun
</a>

    <a href="#">📂 History</a>
    <a href="#">📝 Thirteen</a>
    <a href="#" class="btn btn-dark w-100 mt-4">Logout</a>
  </div>

  <!-- Content -->
  <div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Dashboard</h2>
      <div class="dropdown">
        <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
          <img src="https://via.placeholder.com/30" class="rounded-circle"> Admin
        </button>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="#">Profile</a></li>
          <li><a class="dropdown-item" href="#">Settings</a></li>
          <li><a href="<?= base_url('logout') ?>" class="btn btn-dark w-100 mt-4">Logout</a>
</li>
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


  <!-- Tabel Status Pengajuan -->
<div class="card shadow mt-4">
  <div class="card-body">
    <h5 class="mb-3">Review Pengajuan HR</h5>
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


<!-- Modal Detail -->
<div class="modal fade" id="detailModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
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
              <label class="form-label">Status Management</label>
              <input type="text" id="detailMng" class="form-control" disabled>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Status Rekrutmen</label>
              <input type="text" id="detailRek" class="form-control" disabled>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Range Gaji</label>
            <input type="text" id="detailGaji" class="form-control" placeholder="cth: 5.000.000 - 7.000.000">
          </div>

          <div class="mb-3">
            <label class="form-label">Comment (wajib jika Reject)</label>
            <textarea id="detailComment" class="form-control" rows="3" placeholder="Alasan ditolak..."></textarea>
          </div>

          <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-success me-2" onclick="submitReview('Approved')">Approve</button>
            <button type="button" class="btn btn-danger" onclick="submitReview('Rejected')">Reject</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
async function loadPengajuan() {
  const res = await fetch('http://localhost/nusantara_api/public/api/pengajuan');
  const json = await res.json();
  const tbody = document.getElementById('pengajuanTable');
  tbody.innerHTML = '';

    if (json.data.length === 0) {
    tbody.innerHTML = `<tr><td colspan="12" class="text-center">Belum ada data pengajuan</td></tr>`;
    document.getElementById('cardPending').innerText  = 0;
    document.getElementById('cardApproved').innerText = 0;
    document.getElementById('cardRejected').innerText = 0;
    return;
  }

  // 🔹 Counter
  let pending = 0, approved = 0, rejected = 0;

  json.data.forEach(item => {
    if (
      (item.status_hr === null || item.status_hr === 'Pending') &&
      (item.status_management === null || item.status_management === 'Pending')
    ) {
      pending++;
    } else if (item.status_hr === 'Approved' && item.status_management === 'Approved') {
      approved++;
    } else if (item.status_hr === 'Rejected' || item.status_management === 'Rejected') {
      rejected++;
    }
  });

  // 🔹 Update ke card
  document.getElementById('cardPending').innerText  = pending;
  document.getElementById('cardApproved').innerText = approved;
  document.getElementById('cardRejected').innerText = rejected;
  json.data.forEach(item => {
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
        <td><button class="btn btn-sm btn-info" data-item='${JSON.stringify(item)}' onclick="showDetail(this)">Detail</button></td>
      </tr>
    `;
  });
}

function showDetail(btn) {
  const data = JSON.parse(btn.getAttribute('data-item'));

  document.getElementById('detailId').value    = data.id_pengajuan;
  document.getElementById('detailDivisi').value= data.nama_divisi;
  document.getElementById('detailPosisi').value= data.nama_posisi;
  document.getElementById('detailMng').value   = data.status_management;
  document.getElementById('detailRek').value   = data.status_rekrutmen;
  document.getElementById('detailGaji').value  = data.range_gaji || '';
  document.getElementById('detailComment').value = data.comment || '';

  const modal = new bootstrap.Modal(document.getElementById('detailModal'));
  modal.show();
}

async function submitReview(status) {
  const id    = document.getElementById('detailId').value;
  const gaji  = document.getElementById('detailGaji').value;
  const comment = document.getElementById('detailComment').value;

  if (status === 'Rejected' && !comment.trim()) {
    alert("Harus isi alasan jika menolak!");
    return;
  }

  const res = await fetch(`http://localhost/nusantara_api/public/api/pengajuan/${id}/hr-review`, {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ status_hr: status, range_gaji: gaji, comment: comment })
  });

  if (res.ok) {
    alert(`Pengajuan ${status}!`);
    bootstrap.Modal.getInstance(document.getElementById('detailModal')).hide();
    loadPengajuan();
  } else {
    alert("Gagal update data");
  }
}

loadPengajuan();
</script>

  <!-- Footer -->
  <footer>
    NusantaraIT © 2025. All rights reserved.
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>  