<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Management - Nusantara Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- CSS global portal -->
  <link rel="stylesheet" href="<?= base_url('assets/css/divisi.css') ?>">

  <!-- Quill alignment helper (boleh juga dipindah ke CSS global nanti) -->
  <style>
    .ql-align-center { text-align:center; }
    .ql-align-right { text-align:right; }
    .ql-align-justify { text-align:justify; }
  </style>
</head>
<body class="role-management page-dashboard">

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="text-center mb-4">
      <img src="<?= base_url('assets/images/logo-nusantara-group.png') ?>" alt="Logo" height="40">
      <h6 class="mt-2">Nusantara Portal</h6>
    </div>
    <a href="<?= base_url('dashboard/management') ?>" class="active">📊 Dashboard</a>
    <a href="<?= base_url('history/management') ?>">📂 History</a>
  </div>

  <!-- Content -->
  <?php
  $profilePhoto = session()->get('profile_photo') ?: 'default.png';
  $namaUser     = session()->get('nama_user')  ?? 'User Portal';
  $emailUser    = session()->get('email_user') ?? 'user@example.com';
  $roleLabel    = session()->get('role')       ?? 'User';
?>

<div class="dropdown text-end">
  <button class="btn btn-light d-flex align-items-center gap-2 shadow-sm" 
          type="button" data-bs-toggle="dropdown" aria-expanded="false" 
          style="border-radius: 50px;">
    <img id="profilePic" 
         src="<?= base_url('uploads/profile/' . $profilePhoto) ?>" 
         onerror="this.onerror=null;this.src='<?= base_url('assets/images/default.png') ?>';"
         class="rounded-circle border border-primary" 
         width="32" height="32" 
         style="object-fit: cover;">
    <span class="fw-semibold"><?= esc($roleLabel) ?></span>
  </button>

  <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 p-3" 
      style="width: 250px;">
    <div class="text-center">
      <img id="profilePreview" 
           src="<?= base_url('uploads/profile/' . $profilePhoto) ?>" 
           onerror="this.onerror=null;this.src='<?= base_url('assets/images/default.png') ?>';"
           class="rounded-circle mb-2 border border-2 border-primary" 
           width="70" height="70" 
           style="object-fit: cover;">

      <h6 class="fw-bold mb-0"><?= esc($namaUser) ?></h6>
      <p class="text-muted small mb-2"><?= esc($emailUser) ?></p>

      <input type="file" id="uploadProfile" accept="image/*" 
             class="form-control form-control-sm mb-2" onchange="previewProfile(event)">
      <button class="btn btn-primary btn-sm w-100 mb-2" onclick="saveProfile()">Simpan Foto</button>

      <hr class="my-2">
      <a href="<?= base_url('logout') ?>" class="btn btn-outline-danger btn-sm w-100">Logout</a>
    </div>
  </ul>
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
          <table class="table table-hover table-sm table-compact align-middle">
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
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Detail Pengajuan</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" style="max-height:75vh; overflow-y:auto;">
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

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Min Gaji</label>
                <input type="number" id="detailMinGaji" class="form-control" disabled>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Max Gaji</label>
                <input type="number" id="detailMaxGaji" class="form-control" disabled>
              </div>
            </div>

            <div id="detailKualifikasi"
                 class="form-control"
                 style="min-height:100px; max-height:250px; overflow:auto; background:#fff;"></div>

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
              <label class="form-label">Komentar Management <span class="text-danger">*</span></label>
              <textarea id="detailCommentMng" class="form-control" rows="3" placeholder="Wajib diisi jika reject..."></textarea>
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
        document.getElementById('cardTotal').innerText = 0;
        document.getElementById('cardApproved').innerText = 0;
        document.getElementById('cardRejected').innerText = 0;
        return;
      }

      let total = 0, approved = 0, rejected = 0;

      json.data.forEach(item => {
  // Hanya yang sudah lewat HR
  if (item.status_hr !== 'Approved') return;

  // Hitung untuk kartu
  total++;
  if (item.status_management === 'Approved') {
    approved++;
  } else if (item.status_management === 'Rejected') {
    rejected++;
  }

  // ⛔️ Jangan tampilkan yang sudah di-approve / reject di tabel
  if (item.status_management === 'Approved' || item.status_management === 'Rejected') {
    return;
  }

  const badgeMng = `<span class="badge bg-${
    item.status_management === 'Approved' ? 'success' : 
    item.status_management === 'Rejected' ? 'danger' : 'secondary'
  }">${item.status_management || 'Pending'}</span>`;

  tbody.insertAdjacentHTML('beforeend', `
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
                data-item='${JSON.stringify(item).replace(/</g,"\\u003c")}' 
                onclick="showDetail(this)">Detail</button>
      </td>
    </tr>
  `);
});

      document.getElementById('cardTotal').innerText = total;
      document.getElementById('cardApproved').innerText = approved;
      document.getElementById('cardRejected').innerText = rejected;
    }

    function decodeEntities(html) {
      const ta = document.createElement('textarea');
      ta.innerHTML = html ?? '';
      return ta.value;
    }

    function sanitize(html) {
      const tpl = document.createElement('template');
      tpl.innerHTML = html || '';

      tpl.content.querySelectorAll('script, iframe, object, embed, link, meta').forEach(el => el.remove());

      tpl.content.querySelectorAll('*').forEach(el => {
        [...el.attributes].forEach(attr => {
          const name = attr.name.toLowerCase();
          const val  = (attr.value || '').toLowerCase();
          if (name.startsWith('on')) el.removeAttribute(attr.name);
          if ((name === 'src' || name === 'href') && val.startsWith('javascript:')) {
            el.removeAttribute(attr.name);
          }
        });
      });

      return tpl.innerHTML;
    }
function previewProfile(event) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = e => {
      const preview = document.getElementById('profilePreview');
      if (preview) preview.src = e.target.result;
    };
    reader.readAsDataURL(file);
  }

  async function saveProfile() {
    const input = document.getElementById('uploadProfile');
    const file  = input?.files?.[0];

    if (!file) {
      alert('Pilih file foto terlebih dahulu.');
      return;
    }

    const formData = new FormData();
    formData.append('profile', file);

    try {
      const res = await fetch('<?= base_url('api/users/upload-profile') ?>', {
        method: 'POST',
        body: formData
      });

      let data = {};
      try { data = await res.json(); } catch (e) {}

      if (!res.ok) {
        alert(data.message || data.error || 'Upload gagal.');
        console.error('Upload error:', data);
        return;
      }

      if (data.url) {
        const pic     = document.getElementById('profilePic');
        const preview = document.getElementById('profilePreview');

        if (pic)     pic.src     = data.url;
        if (preview) preview.src = data.url;
      }

      alert(data.message || 'Foto profil berhasil diupload.');
      
    } catch (err) {
      console.error(err);
      alert('Terjadi error jaringan saat upload foto.');
    }
  }
    function showDetail(btn) {
      const data = JSON.parse(btn.getAttribute('data-item'));

      document.getElementById('detailIdPengajuan').value = data.id_pengajuan;
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
      document.getElementById('detailStatusHR').value = data.status_hr || '';
      document.getElementById('detailStatusMng').value = data.status_management || '';
      document.getElementById('detailStatusRek').value = data.status_rekrutmen || '';
      document.getElementById('detailCommentMng').value = data.comment_management || '';

      const kvalRaw = data.kualifikasi || '';
      const kvalDecoded = decodeEntities(kvalRaw);
      const kvalSafe = sanitize(kvalDecoded);
      const kvalEl = document.getElementById('detailKualifikasi');
      kvalEl.innerHTML = kvalSafe;

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

      loadPengajuan();
    });
  </script>
</body>
</html>
