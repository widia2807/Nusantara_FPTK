<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Manajemen Data - Divisi / Posisi / Cabang</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/admin-shared.css') ?>"/>
</head>

<body>
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

  <!-- Content -->
  <div class="content">
    <h2 class="mb-4 fw-bold text-gradient">Kelola Divisi, Posisi, & Cabang</h2>

    <!-- ===== Divisi (block vertikal) ===== -->
    <div class="col-12 mb-4">
      <div class="card p-3">
        <h5 class="text-gradient mb-3">Tambah Divisi</h5>
        <form id="formDivisi">
          <input type="hidden" id="id_divisi_edit">
          <input type="text" id="nama_divisi" class="form-control mb-2" placeholder="Nama Divisi" required>
          <button type="submit" class="btn btn-primary w-100">Simpan</button>
        </form>

        <div class="mt-3"></div>

        <div class="table-responsive">
          <table class="table table-sm table-hover align-middle">
            <thead>
              <tr><th>#</th><th>Nama Divisi</th><th>Aksi</th></tr>
            </thead>
            <tbody id="divisiTable">
              <tr><td colspan="3" class="text-center">Loading...</td></tr>
            </tbody>
          </table>
        </div>
        <div class="text-center">
          <button id="btnMoreDivisi" class="btn btn-outline-primary btn-sm" style="display:none;">Lihat lebih banyak</button>
        </div>
      </div>
    </div>

    <!-- ===== Posisi (block vertikal) ===== -->
    <div class="col-12 mb-4">
      <div class="card p-3">
        <h5 class="text-gradient mb-3">Tambah Posisi</h5>
        <form id="formPosisi">
          <input type="hidden" id="id_posisi_edit">
          <select id="posisi_divisi" class="form-select mb-2" required>
            <option value="">-- Pilih Divisi --</option>
          </select>
          <input type="text" id="nama_posisi" class="form-control mb-2" placeholder="Nama Posisi" required>
          <button type="submit" class="btn btn-primary w-100">Simpan</button>
        </form>

        <div class="mt-3"></div>

        <div class="table-responsive">
          <table class="table table-sm table-hover align-middle">
            <thead>
              <tr><th>#</th><th>Nama Posisi</th><th>Aksi</th></tr>
            </thead>
            <tbody id="posisiTable">
              <tr><td colspan="3" class="text-center">Loading...</td></tr>
            </tbody>
          </table>
        </div>
        <div class="text-center">
          <button id="btnMorePosisi" class="btn btn-outline-primary btn-sm" style="display:none;">Lihat lebih banyak</button>
        </div>
      </div>
    </div>

    <!-- ===== Cabang (block vertikal) ===== -->
    <div class="col-12 mb-4">
      <div class="card p-3">
        <h5 class="text-gradient mb-3">Tambah Cabang</h5>
        <form id="formCabang">
          <input type="hidden" id="id_cabang_edit">
          <input type="text" id="nama_cabang" class="form-control mb-2" placeholder="Nama Cabang" required>
          <input type="text" id="lokasi" class="form-control mb-2" placeholder="Lokasi Cabang (opsional)">
          <button type="submit" class="btn btn-primary w-100">Simpan</button>
        </form>

        <div class="mt-3"></div>

        <div class="table-responsive">
          <table class="table table-sm table-hover align-middle">
            <thead>
              <tr><th>#</th><th>Nama Cabang</th><th>Aksi</th></tr>
            </thead>
            <tbody id="cabangTable">
              <tr><td colspan="3" class="text-center">Loading...</td></tr>
            </tbody>
          </table>
        </div>
        <div class="text-center">
          <button id="btnMoreCabang" class="btn btn-outline-primary btn-sm" style="display:none;">Lihat lebih banyak</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Script -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const BASE_API = "http://localhost/nusantara_api/public/api";
    const PAGE_SIZE = 5;

    // state data
    let dataDivisi = [], dataPosisi = [], dataCabang = [];
    let showAllDivisi = false, showAllPosisi = false, showAllCabang = false;

    // ========= Utilities render dengan batas 5 & tombol more =========
    function renderLimited(data, tbodyId, makeRowHtml, showAllFlag, moreBtnId) {
      const tbody = document.getElementById(tbodyId);
      tbody.innerHTML = '';

      if (!data || data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="3" class="text-center">Belum ada data</td></tr>`;
        document.getElementById(moreBtnId).style.display = 'none';
        return;
      }

      const shown = showAllFlag ? data : data.slice(0, PAGE_SIZE);
      shown.forEach((item, i) => {
        tbody.insertAdjacentHTML('beforeend', makeRowHtml(item, i));
      });

      const moreBtn = document.getElementById(moreBtnId);
      if (data.length > PAGE_SIZE) {
        moreBtn.style.display = 'inline-block';
        moreBtn.textContent = showAllFlag ? 'Lihat lebih sedikit' : 'Lihat lebih banyak';
      } else {
        moreBtn.style.display = 'none';
      }
    }

    // ======================== LOAD ========================
    async function loadAll() {
      await loadDivisi();
      await loadPosisi();
      await loadCabang();
    }

    async function loadDivisi() {
      const res = await fetch(`${BASE_API}/divisi`);
      const json = await res.json();
      dataDivisi = Array.isArray(json) ? json : (json.data || []);

      // isi dropdown divisi untuk form posisi
      const selectDiv = document.getElementById('posisi_divisi');
      selectDiv.innerHTML = '<option value="">-- Pilih Divisi --</option>';
      dataDivisi.forEach(d => {
        selectDiv.insertAdjacentHTML('beforeend', `<option value="${d.id_divisi}">${d.nama_divisi}</option>`);
      });

      renderLimited(
        dataDivisi,
        'divisiTable',
        (d, i) => `
          <tr>
            <td>${i + 1}</td>
            <td>${d.nama_divisi}</td>
            <td>
              <button class="btn btn-sm btn-warning" onclick="editDivisi(${d.id_divisi}, '${d.nama_divisi.replace(/'/g, "\\'")}')">✏️</button>
              <button class="btn btn-sm btn-danger" onclick="deleteDivisi(${d.id_divisi})">🗑️</button>
            </td>
          </tr>`,
        showAllDivisi,
        'btnMoreDivisi'
      );
    }

    async function loadPosisi() {
      const res = await fetch(`${BASE_API}/posisi`);
      const json = await res.json();
      dataPosisi = Array.isArray(json) ? json : (json.data || []);

      renderLimited(
        dataPosisi,
        'posisiTable',
        (p, i) => `
          <tr>
            <td>${i + 1}</td>
            <td>${p.nama_posisi}</td>
            <td>
              <button class="btn btn-sm btn-warning" onclick="editPosisi(${p.id_posisi}, '${p.nama_posisi.replace(/'/g, "\\'")}', ${p.id_divisi})">✏️</button>
              <button class="btn btn-sm btn-danger" onclick="deletePosisi(${p.id_posisi})">🗑️</button>
            </td>
          </tr>`,
        showAllPosisi,
        'btnMorePosisi'
      );
    }

    async function loadCabang() {
      const res = await fetch(`${BASE_API}/cabang`);
      const json = await res.json();
      dataCabang = Array.isArray(json) ? json : (json.data || []);

      renderLimited(
        dataCabang,
        'cabangTable',
        (c, i) => `
          <tr>
            <td>${i + 1}</td>
            <td>${c.nama_cabang}</td>
            <td>
              <button class="btn btn-sm btn-warning" onclick="editCabang(${c.id_cabang}, '${(c.nama_cabang || '').replace(/'/g, "\\'")}', '${(c.lokasi || '').replace(/'/g, "\\'")}')">✏️</button>
              <button class="btn btn-sm btn-danger" onclick="deleteCabang(${c.id_cabang})">🗑️</button>
            </td>
          </tr>`,
        showAllCabang,
        'btnMoreCabang'
      );
    }

    // tombol more/less (toggle)
    document.getElementById('btnMoreDivisi').addEventListener('click', () => {
      showAllDivisi = !showAllDivisi;
      renderLimited(
        dataDivisi, 'divisiTable',
        (d, i) => `
          <tr>
            <td>${i + 1}</td>
            <td>${d.nama_divisi}</td>
            <td>
              <button class="btn btn-sm btn-warning" onclick="editDivisi(${d.id_divisi}, '${d.nama_divisi.replace(/'/g, "\\'")}')">✏️</button>
              <button class="btn btn-sm btn-danger" onclick="deleteDivisi(${d.id_divisi})">🗑️</button>
            </td>
          </tr>`,
        showAllDivisi, 'btnMoreDivisi'
      );
    });

    document.getElementById('btnMorePosisi').addEventListener('click', () => {
      showAllPosisi = !showAllPosisi;
      renderLimited(
        dataPosisi, 'posisiTable',
        (p, i) => `
          <tr>
            <td>${i + 1}</td>
            <td>${p.nama_posisi}</td>
            <td>
              <button class="btn btn-sm btn-warning" onclick="editPosisi(${p.id_posisi}, '${p.nama_posisi.replace(/'/g, "\\'")}', ${p.id_divisi})">✏️</button>
              <button class="btn btn-sm btn-danger" onclick="deletePosisi(${p.id_posisi})">🗑️</button>
            </td>
          </tr>`,
        showAllPosisi, 'btnMorePosisi'
      );
    });

    document.getElementById('btnMoreCabang').addEventListener('click', () => {
      showAllCabang = !showAllCabang;
      renderLimited(
        dataCabang, 'cabangTable',
        (c, i) => `
          <tr>
            <td>${i + 1}</td>
            <td>${c.nama_cabang}</td>
            <td>
              <button class="btn btn-sm btn-warning" onclick="editCabang(${c.id_cabang}, '${(c.nama_cabang || '').replace(/'/g, "\\'")}', '${(c.lokasi || '').replace(/'/g, "\\'")}')">✏️</button>
              <button class="btn btn-sm btn-danger" onclick="deleteCabang(${c.id_cabang})">🗑️</button>
            </td>
          </tr>`,
        showAllCabang, 'btnMoreCabang'
      );
    });

    // ======================== FORM HANDLER ========================
    document.getElementById('formDivisi').addEventListener('submit', async (e) => {
      e.preventDefault();
      const id = document.getElementById('id_divisi_edit').value;
      const nama = document.getElementById('nama_divisi').value.trim();
      if (!nama) return alert('Isi nama divisi');

      const method = id ? 'PUT' : 'POST';
      const url = id ? `${BASE_API}/divisi/${id}` : `${BASE_API}/divisi`;
      await fetch(url, { method, headers:{'Content-Type':'application/json'}, body: JSON.stringify({ nama_divisi: nama }) });
      e.target.reset();
      document.getElementById('id_divisi_edit').value = '';
      loadDivisi();
    });

    document.getElementById('formPosisi').addEventListener('submit', async (e) => {
      e.preventDefault();
      const id = document.getElementById('id_posisi_edit').value;
      const id_divisi = document.getElementById('posisi_divisi').value;
      const nama = document.getElementById('nama_posisi').value.trim();
      if (!id_divisi || !nama) return alert('Isi semua kolom');

      const method = id ? 'PUT' : 'POST';
      const url = id ? `${BASE_API}/posisi/${id}` : `${BASE_API}/posisi`;
      await fetch(url, { method, headers:{'Content-Type':'application/json'}, body: JSON.stringify({ id_divisi, nama_posisi: nama }) });
      e.target.reset();
      document.getElementById('id_posisi_edit').value = '';
      loadPosisi();
    });

    document.getElementById('formCabang').addEventListener('submit', async (e) => {
      e.preventDefault();
      const id = document.getElementById('id_cabang_edit').value;
      const nama = document.getElementById('nama_cabang').value.trim();
      const lokasi = document.getElementById('lokasi').value.trim();
      if (!nama) return alert('Isi nama cabang');

      const method = id ? 'PUT' : 'POST';
      const url = id ? `${BASE_API}/cabang/${id}` : `${BASE_API}/cabang`;
      await fetch(url, { method, headers:{'Content-Type':'application/json'}, body: JSON.stringify({ nama_cabang: nama, lokasi }) });
      e.target.reset();
      document.getElementById('id_cabang_edit').value = '';
      loadCabang();
    });

    // ======================== AKSI EDIT & DELETE ========================
    function editDivisi(id, nama) {
      document.getElementById('id_divisi_edit').value = id;
      document.getElementById('nama_divisi').value = nama;
    }
    async function deleteDivisi(id) {
      if (!confirm('Yakin hapus divisi ini?')) return;
      await fetch(`${BASE_API}/divisi/${id}`, { method: 'DELETE' });
      loadDivisi();
    }

    function editPosisi(id, nama, id_divisi) {
      document.getElementById('id_posisi_edit').value = id;
      document.getElementById('nama_posisi').value = nama;
      document.getElementById('posisi_divisi').value = id_divisi;
    }
    async function deletePosisi(id) {
      if (!confirm('Yakin hapus posisi ini?')) return;
      await fetch(`${BASE_API}/posisi/${id}`, { method: 'DELETE' });
      loadPosisi();
    }

    function editCabang(id, nama, lokasi) {
      document.getElementById('id_cabang_edit').value = id;
      document.getElementById('nama_cabang').value = nama;
      document.getElementById('lokasi').value = lokasi || '';
    }
    async function deleteCabang(id) {
      if (!confirm('Yakin hapus cabang ini?')) return;
      await fetch(`${BASE_API}/cabang/${id}`, { method: 'DELETE' });
      loadCabang();
    }

    // INIT
    loadAll();
  </script>
</body>
</html>
