<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard HR - Nusantara Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url('assets/css/divisi.css') ?>">
  
</head>
<body class="role-hrd page-dashboard">


  <!-- Sidebar -->
  <div class="sidebar">
    <div class="text-center mb-4">
      <img src="<?= base_url('assets/images/logo-nusantara-group.png') ?>" alt="Logo" height="40">
      <h6 class="mt-2">Nusantara Portal</h6>
    </div>
    <a href="<?= base_url('dashboard/hr') ?>">📊 Dashboard</a>
    <a href="<?= base_url('admin_menu/create') ?>">➕ Tambah Akun</a>
    <a href="<?= base_url('admin_menu/manage') ?>">👥 Manajemen User</a>
    <a href="<?= base_url('admin_menu/manage_all') ?>" class="active">⚙️ Kelola Data</a>
    <a href="<?= base_url('history/hr') ?>">📂 History</a>
  </div>

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
  <div class="modal-dialog modal-xl"> 
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
        <div id="detailKualifikasi" class="form-control quill-content"
            style="min-height:96px; overflow:auto;"></div>
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
  const res = await fetch('http://localhost/nusantara_api/public/api/pengajuan');
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
  document.getElementById('detailKualifikasi').innerHTML = data.kualifikasi || '<em>(Kosong)</em>';
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
    const res = await fetch(`http://localhost/nusantara_api/public/api/pengajuan/${currentId}/hr-review`, {
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

  try {
    const res = await fetch(`http://localhost/nusantara_api/public/api/pengajuan/${currentId}/hr-review`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        status_hr: 'Rejected',
        comment,
        action: 'reject' // ✅ penting!
      })
    });

    // cek response jelas
    let payloadText = await res.text();
    let payload;
    try { payload = JSON.parse(payloadText); } catch { payload = { message: payloadText }; }

    if (!res.ok) {
      alert("Gagal reject: " + (payload?.message || res.status));
      console.error('Reject error:', payload);
      return;
    }

    // tutup modal & refresh tabel
    bootstrap.Modal.getInstance(document.getElementById('detailModal')).hide();
    loadPengajuan();
  } catch (e) {
    console.error(e);
    alert("Terjadi error jaringan saat reject.");
  }
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
      // kalau mau sekalian sync session di server & refresh tampilan:
      // location.reload();
    } catch (err) {
      console.error(err);
      alert('Terjadi error jaringan saat upload foto.');
    }
  }
async function sendPengajuan() {
  const minGaji = document.getElementById('minGaji').value;
  const maxGaji = document.getElementById('maxGaji').value;

  if (!minGaji || !maxGaji) {
    alert("Isi range gaji dulu sebelum kirim!");
    return;
  }

  await fetch(`http://localhost/nusantara_api/public/api/pengajuan/${currentId}/hr-review`, {
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
