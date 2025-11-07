<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Form Pengajuan - Nusantara Portal</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>

  <!-- CSS Global Kamu -->
  <link rel="stylesheet" href="<?= base_url('assets/css/divisi.css') ?>"/>

  <!-- Quill (rich text) -->
  <link href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css" rel="stylesheet"/>
  <script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
</head>

<body class="role-divisi page-pengajuan">
  <!-- Header -->
  <div class="header">
    <h6 class="mb-0">Form Pengajuan</h6>
  </div>

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="text-center mb-4">
      <img src="<?= base_url('assets/images/logo-nusantara-group.png') ?>" alt="Logo" height="40">
      <h6 class="mt-2">Nusantara Portal</h6>
    </div>
    <a href="<?= base_url('dashboard/divisi') ?>">📊 Dashboard</a>
    <a href="<?= base_url('pengajuan') ?>">
      <img src="<?= base_url('assets/images/checklist.png') ?>" alt="Pengajuan" height="18" class="me-2">Pengajuan
    </a>
    <a href="<?= base_url('history/divisi') ?>">📂 History</a>
  </div>

  <!-- Content -->
  <div class="content">
    <div class="form-card">
      <h4>Form Pengajuan</h4>

      <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
          <?= session()->getFlashdata('success') ?>
        </div>
      <?php endif; ?>

      <form id="formPengajuan" action="<?= base_url('pengajuan/store') ?>" method="post">
       
      <!-- Job Post -->
      <div class="form-group">
        <label class="form-label">Job Post</label>
        <div class="form-field" style="width:320px">
          <input type="text" id="jobPost" name="job_post_number" class="form-control"
                placeholder="JB/HO/2025.10/0001" value="" readonly required>
        </div>
      </div>

        <!-- Jenis Lokasi -->
        <div class="form-group">
          <label class="form-label">Jenis Lokasi</label>
          <select id="jenisLokasi" class="form-select" required>
            <option value="" selected>Pilih Jenis Lokasi</option>
            <option value="HO">HO</option>
            <option value="Cabang">Cabang</option>
          </select>
        </div>

        <!-- Pilihan HO -->
        <div class="form-group" id="groupHO" style="display:none;">
          <label class="form-label">Pilih HO</label>
          <select id="pilihanHO" class="form-select">
            <option value="" selected>Pilih HO</option>
            <!-- Pastikan value sesuai id_cabang di DB -->
            <option value="3">HO-MTH (Nusantara Group)</option>
            <option value="10">HO-Antasari (Nusantara Royal Enfields Jakarta)</option>
          </select>
        </div>

        <!-- Pilihan Cabang -->
        <div class="form-group" id="groupCabang" style="display:none;">
          <label class="form-label">Cabang</label>
          <select id="pilihanCabang" class="form-select">
            <option value="" selected>Pilih Cabang</option>
            <?php foreach($cabang as $c): ?>
              <option value="<?= $c['id_cabang'] ?>"><?= esc($c['nama_cabang']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Divisi -->
        <div class="form-group">
          <label class="form-label">Divisi</label>
          <select name="id_divisi" class="form-select" required>
            <option value="">Pilih Divisi</option>
            <?php foreach($divisi as $d): ?>
              <option value="<?= $d['id_divisi'] ?>"><?= esc($d['nama_divisi']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Posisi -->
        <div class="form-group">
          <label class="form-label">Posisi</label>
          <select name="id_posisi" class="form-select" required>
            <option value="">Pilih Posisi</option>
            <?php foreach($posisi as $p): ?>
              <option value="<?= $p['id_posisi'] ?>"><?= esc($p['nama_posisi']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Jumlah -->
        <div class="form-group">
          <label class="form-label">Jumlah</label>
          <div class="form-field" style="width:320px">
            <input type="number" name="jumlah_karyawan" class="form-control" placeholder="contoh: 5" required>
          </div>
        </div>
        <!-- Tipe -->
        <div class="form-group">
          <label class="form-label">Tipe</label>
          <select name="tipe_pekerjaan" class="form-select" required>
            <option value="">Pilih Tipe</option>
            <option value="Intern">Intern</option>
            <option value="Kontrak">Kontrak</option>
            <option value="Tetap">Tetap</option>
            <option value="Freelance">Freelance</option>
          </select>
        </div>

        <!-- Range Umur (MIN–MAX) -->
      <div class="form-group">
        <label class="form-label">Range Umur</label>
        <div class="d-flex align-items-center gap-2 form-field" style="width:320px">
          <input type="number" id="ageMin" class="form-control" placeholder="Min" min="15" max="70" required>
          <span class="text-muted">–</span>
          <input type="number" id="ageMax" class="form-control" placeholder="Max" min="15" max="70" required>
        </div>
        <!-- dikirim ke server -->
        <input type="hidden" name="range_umur" id="rangeUmurHidden">
      </div>

          <!-- Tempat -->
          <div class="form-group">
            <label class="form-label">Tempat</label>
            <div class="form-field" style="width:320px">
              <input type="text" name="tempat_kerja" class="form-control" placeholder="Jakarta" required>
            </div>
          </div>

        <!-- Jenis Pengajuan -->
        <div class="form-group">
          <label class="form-label">Jenis Pengajuan</label>
          <div class="d-flex gap-3">
            <div class="form-check">
              <input class="form-check-input" type="radio" name="request_type" id="reqAdd" value="Penambahan" checked>
              <label class="form-check-label" for="reqAdd">Penambahan</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="request_type" id="reqReplace" value="Pergantian">
              <label class="form-check-label" for="reqReplace">Pergantian</label>
            </div>
          </div>
        </div>

<!-- Nama yang Diganti (muncul saat Pergantian) -->
<div class="form-group" id="groupReplace" style="display:none;">
  <label class="form-label">Nama yang Diganti</label>
  <div class="form-field" style="width:320px">
    <input type="text" class="form-control" id="replaceName" name="replace_employee_name"
           placeholder="contoh: Budi Santoso" disabled>
  </div>
</div>

        <!-- Kualifikasi (Quill) -->
       <div class="form-group form-richtext">
  <label class="form-label">Kualifikasi</label>

  <div class="form-field">
    <!-- Toolbar -->
    <div id="kualifikasiToolbar" class="quill-wrap mb-2">
      <span class="ql-formats">
        <button class="ql-bold"></button>
        <button class="ql-italic"></button>
        <button class="ql-underline"></button>
      </span>
      <span class="ql-formats">
        <select class="ql-align"></select>
        <button class="ql-list" value="ordered"></button>
        <button class="ql-list" value="bullet"></button>
      </span>
      <span class="ql-formats">
        <button class="ql-clean"></button>
      </span>
    </div>

    <!-- Editor -->
    <div id="kualifikasiEditor" class="quill-editor"></div>
    <input type="hidden" name="kualifikasi" id="kualifikasiHidden" required>
  </div>
</div>

        <div class="d-flex justify-content-end mt-4">
          <button type="reset" class="btn btn-secondary me-2">Reset</button>
          <button type="submit" class="btn btn-primary">Ajukan</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
document.addEventListener('DOMContentLoaded', async () => {
  const form = document.getElementById('formPengajuan');
  const jenis = document.getElementById('jenisLokasi');
  const groupHO = document.getElementById('groupHO');
  const groupCabang = document.getElementById('groupCabang');
  const ho = document.getElementById('pilihanHO');
  const cab = document.getElementById('pilihanCabang');
  const jobPost = document.getElementById('jobPost');
  const ageMin = document.getElementById('ageMin');
  const ageMax = document.getElementById('ageMax');
  const rangeUmurHidden = document.getElementById('rangeUmurHidden');

  // --- Jenis Pengajuan (baru)
  const reqAdd = document.getElementById('reqAdd');
  const reqReplace = document.getElementById('reqReplace');
  const groupReplace = document.getElementById('groupReplace');
  const replaceName = document.getElementById('replaceName');

  // Quill
  const quill = new Quill('#kualifikasiEditor', {
    theme: 'snow',
    placeholder: '• S1 TI\n• Pengalaman 1–2 tahun\n• ...',
    modules: { toolbar: '#kualifikasiToolbar' }
  });

  let nextSeq = '0001';

  // Ambil total pengajuan → hitung sequence 4 digit
  try {
    const res = await fetch('http://localhost/nusantara_api/public/api/pengajuan');
    const json = await res.json();
    const total = Array.isArray(json?.data) ? json.data.length : 0;
    nextSeq = String(total + 1).padStart(4, '0');
  } catch (e) {
    console.warn('Gagal ambil total pengajuan, pakai 0001', e);
  }

  // Helper aktif/nonaktif + required
  function setRequiredEnabled(el, enabled) {
    if (enabled) {
      el.removeAttribute('disabled');
      el.setAttribute('required', 'required');
    } else {
      el.value = '';
      el.setAttribute('disabled', 'disabled');
      el.removeAttribute('required');
    }
  }

  // Toggle HO/Cabang (nama field backend = id_cabang)
  function setActive(selectEl, active) {
    if (active) {
      selectEl.setAttribute('name', 'id_cabang');
      selectEl.setAttribute('required', 'required');
      selectEl.removeAttribute('disabled');
    } else {
      selectEl.removeAttribute('name');
      selectEl.removeAttribute('required');
      selectEl.setAttribute('disabled', 'disabled');
      selectEl.value = '';
    }
  }

  function updateLokasiUI() {
    if (jenis.value === 'HO') {
      groupHO.style.display = '';
      groupCabang.style.display = 'none';
      setActive(ho, true);
      setActive(cab, false);
    } else if (jenis.value === 'Cabang') {
      groupHO.style.display = 'none';
      groupCabang.style.display = '';
      setActive(ho, false);
      setActive(cab, true);
    } else {
      groupHO.style.display = 'none';
      groupCabang.style.display = 'none';
      setActive(ho, false);
      setActive(cab, false);
    }
  }

  function updateRequestTypeUI() {
    const isReplace = reqReplace.checked;
    groupReplace.style.display = isReplace ? '' : 'none';
    setRequiredEnabled(replaceName, isReplace); // wajib saat Pergantian
  }

  function allRequiredFilledExceptKualifikasiAndJobPost() {
  const requiredEls = Array.from(form.querySelectorAll('[required]'));
  const filtered = requiredEls.filter(el => {
    const isKualifikasi = (el.id === 'kualifikasiHidden' || (el.tagName === 'TEXTAREA' && el.name === 'kualifikasi'));
    const isJobPost = (el.id === 'jobPost' || el.name === 'job_post_number');
    const isReplaceName = (el.id === 'replaceName' || el.name === 'replace_employee_name'); // ⬅️ tambahkan ini
    return !isKualifikasi && !isJobPost && !isReplaceName; // ⬅️ dikecualikan dari prasyarat generate JP
  });
  return filtered.every(el => {
    if (el.offsetParent === null) return true; // hidden → skip
    return el.value !== '';
  });
}

  function generateJobPostIfReady() {
    if (!allRequiredFilledExceptKualifikasiAndJobPost()) {
      jobPost.value = '';
      return;
    }
    const mode = (jenis.value === 'HO') ? 'HO' : 'CABANG';
    const d = new Date();
    const y = d.getFullYear();
    const m = String(d.getMonth() + 1).padStart(2, '0');
    jobPost.value = `JP/${mode}/${y}.${m}/${nextSeq}`;
  }

  // =========================
  // NEW: trigger saat umur diinput → tetap pakai generator lama
  // =========================
  ageMin.addEventListener('input', generateJobPostIfReady); // NEW
  ageMax.addEventListener('input', generateJobPostIfReady); // NEW

  // init
  updateLokasiUI();
  updateRequestTypeUI();
  generateJobPostIfReady();

  // listeners
  jenis.addEventListener('change', () => { updateLokasiUI(); generateJobPostIfReady(); });
  ho.addEventListener('change', generateJobPostIfReady);
  cab.addEventListener('change', generateJobPostIfReady);

  reqAdd.addEventListener('change', () => { updateRequestTypeUI(); generateJobPostIfReady(); });
  reqReplace.addEventListener('change', () => { updateRequestTypeUI(); generateJobPostIfReady(); });

  Array.from(form.querySelectorAll('input[required], select[required], textarea[required]')).forEach(el => {
    const skip = (el.id === 'jobPost' || el.name === 'job_post_number' || el.id === 'kualifikasiHidden');
    if (skip) return;
    el.addEventListener('input', generateJobPostIfReady);
    el.addEventListener('change', generateJobPostIfReady);
  });

  // submit
  form.addEventListener('submit', (e) => {

    // =========================
    // NEW: validasi & set hidden range_umur
    // =========================
    const minVal = parseInt(ageMin.value, 10);
    const maxVal = parseInt(ageMax.value, 10);
    const MIN_ALLOWED = 15;
    const MAX_ALLOWED = 70;

    if (
      Number.isNaN(minVal) || Number.isNaN(maxVal) ||
      minVal > maxVal ||
      minVal < MIN_ALLOWED || maxVal > MAX_ALLOWED
    ) {
      e.preventDefault();
      alert('Range umur tidak valid. Pastikan Min ≤ Max dan di antara 15–70.');
      (Number.isNaN(minVal) ? ageMin : ageMax).focus();
      return;
    }
    rangeUmurHidden.value = `${minVal}-${maxVal} tahun`; // NEW

    // Validasi Quill
    const plain = quill.getText().trim();
    if (!plain) {
      e.preventDefault();
      document.querySelector('#kualifikasiEditor .ql-editor').focus();
      return;
    }
    // Masukkan HTML Quill ke hidden input
    document.getElementById('kualifikasiHidden').value = quill.root.innerHTML.trim();

    // Pastikan Job Post sudah terisi
    if (!jobPost.value) {
      e.preventDefault();
      if (!jenis.value) jenis.reportValidity();
      else jobPost.reportValidity();
      return;
    }

    // Validasi khusus Pergantian
    if (reqReplace.checked && !replaceName.value.trim()) {
      e.preventDefault();
      replaceName.focus();
      replaceName.reportValidity?.();
      return;
    }
  });
});
</script>

</body>
</html>
