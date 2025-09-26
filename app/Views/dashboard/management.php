<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Management - Nusantara Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background: #f9fafc;
    }

    /* Sidebar */
    .sidebar {
      width: 220px; position: fixed; top: 0; left: 0; height: 100%;
      background: #fff; border-right: 1px solid #ddd;
      padding-top: 20px;
      box-shadow: 2px 0 6px rgba(0,0,0,0.05);
    }
    .sidebar h6 {
      color: #0d6efd; font-weight: 700;
    }
    .sidebar a {
      display: block; padding: 10px 20px;
      color: #333; text-decoration: none;
      font-size: 14px; border-left: 3px solid transparent;
      transition: all 0.2s;
    }
    .sidebar a:hover {
      background: #e7f1ff;
      border-left: 3px solid #0d6efd;
      color: #0d6efd;
    }

    /* Content */
    .content {
      margin-left: 240px; padding: 30px;
    }

    /* Card */
    .card {
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .card h6 { color: #6c757d; }

    /* Table */
    .table-dark th {
      background: #0d6efd !important;
      border-color: #0b5ed7;
    }
    .table th, .table td {
      font-size: 14px;
      padding: 8px 10px;
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
    <a href="<?= base_url('history/management') ?>">📂 History</a>
  </div>

  <!-- Content -->
  <div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Dashboard Management</h2>
      <div class="dropdown">
        <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
          <img src="https://via.placeholder.com/30" class="rounded-circle me-2"> Management
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
            <h6>Total Pengajuan</h6>
            <h3 id="cardTotal">0</h3>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-center">
          <div class="card-body">
            <h6>Disetujui</h6>
            <h3 id="cardApproved">0</h3>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-center">
          <div class="card-body">
            <h6>Ditolak</h6>
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

  <!-- Modal Detail -->
  <div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Detail Pengajuan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" style="max-height:70vh; overflow-y:auto;">
          <form>
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
              <label class="form-label">Kualifikasi</label>
              <textarea id="detailKualifikasi" class="form-control" rows="2" disabled></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label">Comment Management <span class="text-danger">*</span></label>
              <textarea id="detailCommentMng" class="form-control" rows="2" placeholder="Wajib diisi jika reject..."></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" id="btnReject" class="btn btn-danger">Reject</button>
          <button type="button" id="btnAccept" class="btn btn-success">Accept</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    async function loadPengajuan() {
      const res = await fetch('http://localhost/nusantara_api/public/api/pengajuan');
      const json = await res.json();
      const tbody = document.getElementById('pengajuanTable');
      tbody.innerHTML = '';

      if (!json.data || json.data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="10" class="text-center text-muted">Belum ada data</td></tr>`;
        return;
      }

      let total = 0, approved = 0, rejected = 0;

      json.data.forEach(item => {
        if (item.status_hr !== 'Approved') return;
        total++;
        if (item.status_management === 'Approved') approved++;
        if (item.status_management === 'Rejected') rejected++;

        const badgeMng = `<span class="badge bg-${
          item.status_management === 'Approved' ? 'success' : 
          item.status_management === 'Rejected' ? 'danger' : 'warning'
        }">${item.status_management || 'Pending'}</span>`;

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
              <button class="btn btn-sm btn-info" data-item='${JSON.stringify(item)}' onclick="showDetail(this)">Detail</button>
            </td>
          </tr>`;
      });

      document.getElementById('cardTotal').innerText = total;
      document.getElementById('cardApproved').innerText = approved;
      document.getElementById('cardRejected').innerText = rejected;
    }

    function showDetail(btn) {
      const data = JSON.parse(btn.getAttribute('data-item'));
      document.getElementById('detailIdPengajuan').value = data.id_pengajuan;
      document.getElementById('detailDivisi').value = data.nama_divisi;
      document.getElementById('detailPosisi').value = data.nama_posisi;
      document.getElementById('detailCabang').value = data.nama_cabang;
      document.getElementById('detailJumlah').value = data.jumlah_karyawan;
      document.getElementById('detailKualifikasi').value = data.kualifikasi || '';
      document.getElementById('detailCommentMng').value = data.comment_management || '';
      new bootstrap.Modal(document.getElementById('detailModal')).show();
    }

    async function updateStatus(id, status) {
      const comment = document.getElementById('detailCommentMng').value.trim();
      if (status === 'Rejected' && !comment) {
        alert('Comment wajib diisi jika Reject!');
        return;
      }

      await fetch(`http://localhost/nusantara_api/public/api/pengajuan/${id}/management-review`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ status_management: status, comment }),
        credentials: 'include'
      });

      bootstrap.Modal.getInstance(document.getElementById('detailModal')).hide();
      loadPengajuan();
    }

    document.addEventListener('DOMContentLoaded', () => {
      document.getElementById('btnReject').addEventListener('click', () => {
        const id = document.getElementById('detailIdPengajuan').value;
        if (confirm('Yakin ingin menolak pengajuan ini?')) updateStatus(id, 'Rejected');
      });
      document.getElementById('btnAccept').addEventListener('click', () => {
        const id = document.getElementById('detailIdPengajuan').value;
        if (confirm('Yakin ingin menyetujui pengajuan ini?')) updateStatus(id, 'Approved');
      });
    });

    loadPengajuan();
  </script>
</body>
</html>
