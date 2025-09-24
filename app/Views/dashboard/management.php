<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Management - Nusantara Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { font-family: Arial, sans-serif; }
    .sidebar {
      width: 220px;
      position: fixed;
      top: 0; left: 0; height: 100%;
      background: #f8f9fa; border-right: 1px solid #ddd;
      padding-top: 20px;
    }
    .sidebar a {
      display: block; padding: 10px 20px;
      color: #333; text-decoration: none;
    }
    .sidebar a:hover { background: #e9ecef; }
    .content { margin-left: 220px; padding: 20px; }
    footer {
      margin-left: 220px; background: #222; color: #fff;
      text-align: center; padding: 15px;
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
    <a href="<?= base_url('dashboard/management') ?>">📊 Dashboard</a>
    <a href="<?= base_url('history') ?>">📂 History</a>
  </div>

  <!-- Content -->
  <div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Dashboard Management</h2>
      <div class="dropdown">
        <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
          <img src="https://via.placeholder.com/30" class="rounded-circle"> Management
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

    <!-- Table -->
    <div class="card shadow mt-4">
      <div class="card-body">
        <h5 class="mb-3">Status Pengajuan Management</h5>
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
                <th>Status Management</th>
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

      if (!json.data || json.data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="10" class="text-center">Belum ada data pengajuan</td></tr>`;
        return;
      }

      let total = 0, approved = 0, rejected = 0;

      json.data.forEach(item => {
        // hanya tampil kalau HR sudah Approved
        if (item.status_hr !== 'Approved') return;

        total++;
        if (item.status_management === 'Approved') approved++;
        if (item.status_management === 'Rejected') rejected++;

        const badgeMng = `<span class="badge bg-${item.status_management === 'Approved' ? 'success' : item.status_management === 'Rejected' ? 'danger' : 'secondary'}">${item.status_management}</span>`;

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
            <td>${badgeMng}</td>
            <td>
              <button class="btn btn-sm btn-info" 
                data-item='${JSON.stringify(item)}' 
                onclick="showDetail(this)">
                Detail
              </button>
            </td>
          </tr>
        `;
      });

      document.getElementById('cardTotal').innerText = total;
      document.getElementById('cardApproved').innerText = approved;
      document.getElementById('cardRejected').innerText = rejected;
    }

    function showDetail(btn) {
  const data = JSON.parse(btn.getAttribute('data-item'));

  document.getElementById('detailIdPengajuan').value = data.id_pengajuan;
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
  document.getElementById('detailMinGaji').value     = data.min_gaji ?? '-';
  document.getElementById('detailMaxGaji').value     = data.max_gaji ?? '-';
  document.getElementById('detailCommentMng').value  = data.comment_management ?? '';

  const modal = new bootstrap.Modal(document.getElementById('detailModal'));
  modal.show();
}

async function updateStatus(id, status) {
  const comment = document.getElementById('detailCommentMng').value.trim();

  if (status === 'Rejected' && comment === '') {
    alert('Comment wajib diisi jika Reject!');
    return;
  }

  try {
    const res = await fetch(`http://localhost/nusantara_api/public/api/pengajuan/${id}/management-review`, {
  method: 'PUT',   // ✅ harus PUT, bukan POST
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    status_management: status,
    comment: comment   // backend-mu expect key 'comment'
  }),
 credentials: 'include' 
});


    const json = await res.json();
    if (res.ok) {
      alert(`Pengajuan berhasil di${status}`);
      const modal = bootstrap.Modal.getInstance(document.getElementById('detailModal'));
      modal.hide();
      loadPengajuan(); // reload tabel
    } else {
      alert('Gagal update: ' + (json.error || 'Unknown error'));
    }
  } catch (err) {
    alert('Error: ' + err.message);
  }
}

// Pasang ke tombol
document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('btnReject').addEventListener('click', () => {
    const id = document.getElementById('detailIdPengajuan').value;
    updateStatus(id, 'Rejected');
  });

  document.getElementById('btnAccept').addEventListener('click', () => {
    const id = document.getElementById('detailIdPengajuan').value;
    updateStatus(id, 'Approved');
  });
});

    loadPengajuan();
  </script>

  <!-- Modal Detail Pengajuan -->
<div class="modal fade" id="detailModal" tabindex="-1">
  <div class="modal-dialog modal-xl"> <!-- modal diperbesar -->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detail Pengajuan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      
      <!-- Modal Body -->
      <div class="modal-body" style="max-height:70vh; overflow-y:auto;"> 
        <form>
          <!-- hidden id -->
          <input type="hidden" id="detailIdPengajuan">

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

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Min Gaji</label>
              <input type="text" id="detailMinGaji" class="form-control" disabled>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Max Gaji</label>
              <input type="text" id="detailMaxGaji" class="form-control" disabled>
            </div>
          </div>

          <!-- COMMENT EDITABLE -->
          <div class="mb-3">
            <label class="form-label">Comment Management</label>
            <textarea id="detailCommentMng" class="form-control" rows="2"></textarea>
          </div>
        </form>
      </div>

      <!-- Modal Footer dengan tombol aksi -->
      <div class="modal-footer">
        <button type="button" id="btnReject" class="btn btn-danger">Reject</button>
        <button type="button" id="btnAccept" class="btn btn-success">Accept</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


       
</body>
</html>