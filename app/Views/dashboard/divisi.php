<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Divisi - Nusantara Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/divisi.css') ?>">
</head>

<body class="role-divisi page-dashboard">
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
               onerror="this.onerror=null;this.src='<?= base_url('assets/images/default.png') ?>';"
               class="rounded-circle border border-primary"
               width="32" height="32"
               style="object-fit: cover;">
          <span class="fw-semibold">Divisi</span>
        </button>

        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 p-3" style="width: 250px;">
          <div class="text-center">
            <img id="profilePreview"
                 src="<?= base_url('uploads/profile/default.png') ?>"
                 onerror="this.onerror=null;this.src='<?= base_url('assets/images/default.png') ?>';"
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

  </div><!-- /.content -->

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Script 1: load & render tabel (dipisah agar tidak nested) -->
  <script>
  async function loadPengajuan() {
    const tbody = document.getElementById('pengajuanTable');

    try {
      const res = await fetch('http://localhost/nusantara_api/public/api/pengajuan');
      if (!res.ok) throw new Error('HTTP ' + res.status);
      const json = await res.json();

      const data = Array.isArray(json?.data) ? json.data : [];
      tbody.innerHTML = '';

      if (data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="12" class="text-center">Belum ada data pengajuan</td></tr>`;
        document.getElementById('cardTotal').innerText = 0;
        document.getElementById('cardApproved').innerText = 0;
        document.getElementById('cardRejected').innerText = 0;
        return;
      }

      // Hitung kartu
      let total = data.length;
      let approved = 0;
      let rejected = 0;

      data.forEach(item => {
        if (item.status_hr === 'Approved' && item.status_management === 'Approved') approved++;
        else if (item.status_hr === 'Rejected' || item.status_management === 'Rejected') rejected++;
      });

      document.getElementById('cardTotal').innerText = total;
      document.getElementById('cardApproved').innerText = approved;
      document.getElementById('cardRejected').innerText = rejected;

      // Render tabel (skip selesai/ditolak)
      data.forEach(item => {
        if (
          item.status_rekrutmen === 'Selesai' ||
          item.status_hr === 'Rejected' ||
          item.status_management === 'Rejected'
        ) return;

        const badgeHR  = `<span class="badge bg-${item.status_hr === 'Approved' ? 'success' : item.status_hr === 'Rejected' ? 'danger' : 'secondary'}">${item.status_hr ?? '-'}</span>`;
        const badgeMng = `<span class="badge bg-${item.status_management === 'Approved' ? 'success' : item.status_management === 'Rejected' ? 'danger' : 'secondary'}">${item.status_management ?? '-'}</span>`;
        const badgeRek = `<span class="badge bg-${item.status_rekrutmen === 'Selesai' ? 'success' : 'secondary'}">${item.status_rekrutmen ?? '-'}</span>`;

        // encode agar aman dimasukkan ke atribut data-item
        const safe = encodeURIComponent(JSON.stringify(item));

        tbody.insertAdjacentHTML('beforeend', `
          <tr>
            <td>${item.id_pengajuan}</td>
            <td>${item.nama_divisi ?? '-'}</td>
            <td>${item.nama_posisi ?? '-'}</td>
            <td>${item.nama_cabang ?? '-'}</td>
            <td>${item.jumlah_karyawan ?? '-'}</td>
            <td>${item.job_post_number ?? '-'}</td>
            <td>${item.tipe_pekerjaan ?? '-'}</td>
            <td>${item.created_at ?? '-'}</td>
            <td class="status-col">${badgeHR}</td>
            <td class="status-col">${badgeMng}</td>
            <td class="status-col">${badgeRek}</td>
            <td><button class="btn btn-sm btn-info" data-item="${safe}" onclick="showDetail(this)">Detail</button></td>
          </tr>
        `);
      });

    } catch (err) {
      console.error(err);
      tbody.innerHTML = `<tr><td colspan="12" class="text-center text-danger">Gagal memuat data pengajuan</td></tr>`;
      document.getElementById('cardTotal').innerText = 0;
      document.getElementById('cardApproved').innerText = 0;
      document.getElementById('cardRejected').innerText = 0;
    }
  }

  function showDetail(btn) {
    try {
      const data = JSON.parse(decodeURIComponent(btn.getAttribute('data-item')));

      document.getElementById('detailDivisi').value      = data.nama_divisi ?? '';
      document.getElementById('detailPosisi').value      = data.nama_posisi ?? '';
      document.getElementById('detailCabang').value      = data.nama_cabang ?? '';
      document.getElementById('detailJumlah').value      = data.jumlah_karyawan ?? '';
      document.getElementById('detailJobPost').value     = data.job_post_number ?? '';
      document.getElementById('detailTipe').value        = data.tipe_pekerjaan ?? '';
      document.getElementById('detailUmur').value        = data.range_umur ?? '';
      document.getElementById('detailTempat').value      = data.tempat_kerja ?? '';
      // Quill HTML -> plain text
      const qual = (data.kualifikasi ?? '').replace(/<[^>]*>/g, '');
      document.getElementById('detailKualifikasi').value = qual;
      document.getElementById('detailCreated').value     = data.created_at ?? '';

      // Optional jika field ini ada di API
      if (document.getElementById('detailCommentHR')) {
        document.getElementById('detailCommentHR').value = data.comment_hr ?? '';
      }
      if (document.getElementById('detailCommentMng')) {
        document.getElementById('detailCommentMng').value = data.comment_management ?? '';
      }

      const modal = new bootstrap.Modal(document.getElementById('detailModal'));
      modal.show();
    } catch (e) {
      console.error('Bad data-item', e);
    }
  }

  // initial load
  loadPengajuan();
  </script>

  <!-- Script 2: profil (dipisah, tidak nested) -->
  <script>
  function previewProfile(event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
      document.getElementById('profilePreview').src = e.target.result;
    };
    reader.readAsDataURL(file);
  }

  async function saveProfile() {
    const fileInput = document.getElementById('uploadProfile');
    const file = fileInput.files[0];

    if (!file) {
      alert('Pilih foto profil terlebih dahulu.');
      return;
    }

    const formData = new FormData();
    formData.append('profile', file);

    try {
      // Ganti ke host API kamu yang benar
      const res = await fetch('http://10.101.56.69:8080/api/users/upload-profile', {
        method: 'POST',
        body: formData
      });

      const data = await res.json();
      if (!res.ok) throw new Error(data?.message || 'Upload gagal');

      alert('Foto profil berhasil diperbarui!');
      if (data.url) {
        document.getElementById('profilePic').src = data.url;
        document.getElementById('profilePreview').src = data.url;
      }
    } catch (err) {
      console.error(err);
      alert('Gagal mengupload foto.');
    }
  }
  </script>

  <!-- Modal Detail Pengajuan -->
  <div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
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
