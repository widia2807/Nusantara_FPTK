<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Rekrutmen - Nusantara Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- CSS global portal -->
  <link rel="stylesheet" href="<?= base_url('assets/css/divisi.css') ?>">

  <!-- Khusus alignment & preview kualifikasi -->
  <style>
    .ql-align-center { text-align: center; }
    .ql-align-right { text-align: right; }
    .ql-align-justify { text-align: justify; }

    #detailKualifikasiPreview ol, 
    #detailKualifikasiPreview ul {
      margin: 0 0 0.5rem 1.25rem;
      list-style-position: outside;
    }
    #detailKualifikasiPreview .ql-align-center { text-align:center; }
    #detailKualifikasiPreview .ql-align-right { text-align:right; }
    #detailKualifikasiPreview .ql-align-justify { text-align:justify; }
  </style>
</head>
<body class="role-rekrutmen page-dashboard">

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="text-center mb-4">
      <img src="<?= base_url('assets/images/logo-nusantara-group.png') ?>" alt="Logo" height="40">
      <h6 class="mt-2">Nusantara Portal</h6>
    </div>
    <a href="<?= base_url('dashboard/rekrutmen') ?>" class="active">📊 Dashboard</a>
    <a href="<?= base_url('history/rekrutmen') ?>">📂 History</a>
  </div>

  <!-- Content -->
  <div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Dashboard Rekrutmen</h2>
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
          <span class="fw-semibold">Rekrutmen</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 p-3" 
            style="width: 250px;">
          <div class="text-center">
            <img id="profilePreview" 
                 src="<?= base_url('uploads/profile/default.png') ?>" 
                 class="rounded-circle mb-2 border border-2 border-primary" 
                 width="70" height="70" 
                 style="object-fit: cover;">
            <h6 class="fw-bold mb-0"><?= session()->get('nama_user') ?? 'Nama Rekrutmen' ?></h6>
            <p class="text-muted small mb-2"><?= session()->get('email_user') ?? 'rekrutmen@example.com' ?></p>
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
      const tbody = document.getElementById('pengajuanTable');
      tbody.innerHTML = `<tr><td colspan="10" class="text-center">Loading...</td></tr>`;

      try {
        const res = await fetch('http://localhost/nusantara_api/public/api/pengajuan');
        if (!res.ok) throw new Error('HTTP ' + res.status);
        const json = await res.json();

        tbody.innerHTML = '';
        const list = Array.isArray(json.data) ? json.data : [];

        let pendingCount = 0, doneCount = 0, rendered = 0;

        list.forEach(item => {
          if (item.status_hr !== 'Approved' || item.status_management !== 'Approved') return;

          if (item.status_rekrutmen === 'Pending') pendingCount++;
          else if (item.status_rekrutmen === 'Selesai') doneCount++;

          if (item.status_rekrutmen === 'Pending') {
            const badgeRek = `<span class="badge bg-secondary">${item.status_rekrutmen}</span>`;

            const safeItem = JSON.stringify({
              id_pengajuan: item.id_pengajuan,
              nama_divisi: item.nama_divisi,
              nama_posisi: item.nama_posisi,
              nama_cabang: item.nama_cabang,
              jumlah_karyawan: item.jumlah_karyawan,
              job_post_number: item.job_post_number,
              tipe_pekerjaan: item.tipe_pekerjaan,
              created_at: item.created_at,
              min_gaji: item.min_gaji,
              max_gaji: item.max_gaji,
              kualifikasi: item.kualifikasi,
              status_hr: item.status_hr,
              status_management: item.status_management,
              status_rekrutmen: item.status_rekrutmen,
              comment: item.comment
            }).replace(/</g, '\\u003c');

            tbody.insertAdjacentHTML('beforeend', `
              <tr>
                <td>${item.id_pengajuan}</td>
                <td>${item.nama_divisi ?? ''}</td>
                <td>${item.nama_posisi ?? ''}</td>
                <td>${item.nama_cabang ?? ''}</td>
                <td>${item.jumlah_karyawan ?? ''}</td>
                <td>${item.job_post_number ?? ''}</td>
                <td>${item.tipe_pekerjaan ?? ''}</td>
                <td>${item.created_at ?? ''}</td>
                <td>${badgeRek}</td>
                <td><button class="btn btn-sm btn-info" data-item='${safeItem}' onclick="showDetail(this)">Detail</button></td>
              </tr>
            `);
            rendered++;
          }
        });

        document.getElementById('cardPending').textContent = String(pendingCount);
        document.getElementById('cardDone').textContent = String(doneCount);

        if (rendered === 0) {
          tbody.innerHTML = `<tr><td colspan="10" class="text-center text-muted">
            Tidak ada pengajuan <b>Pending</b> dengan status HR & Management = Approved.<br>
            Pending: ${pendingCount}, Selesai: ${doneCount}.
          </td></tr>`;
        }
      } catch (err) {
        console.error('[Rekrutmen] gagal load:', err);
        tbody.innerHTML = `<tr><td colspan="10" class="text-center text-danger">
          Gagal memuat data (cek Console).
        </td></tr>`;
        document.getElementById('cardPending').textContent = '0';
        document.getElementById('cardDone').textContent = '0';
      }
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
          if ((name === 'src' || name === 'href') && val.startsWith('javascript:')) el.removeAttribute(attr.name);
        });
      });
      return tpl.innerHTML;
    }

    function ensureKualifikasiPreview() {
      const ta = document.getElementById('detailKualifikasi');
      if (!ta) return null;

      let preview = document.getElementById('detailKualifikasiPreview');
      if (!preview) {
        preview = document.createElement('div');
        preview.id = 'detailKualifikasiPreview';
        preview.className = 'form-control mt-2';
        preview.style.minHeight = '100px';
        preview.style.maxHeight = '250px';
        preview.style.overflow = 'auto';
        preview.style.background = '#fff';
        ta.parentElement.insertAdjacentElement('afterend', preview);
      }
      return preview;
    }

    function normalizeLists(html) {
      const tpl = document.createElement('template');
      tpl.innerHTML = html;

      const frag = tpl.content;
      const nodes = Array.from(frag.childNodes);
      const groups = [];
      let current = [];

      nodes.forEach(n => {
        if (n.nodeType === 1 && (n.tagName === 'OL' || n.tagName === 'UL')) {
          current.push(n);
        } else {
          if (current.length) groups.push(current), current = [];
          groups.push([n]);
        }
      });
      if (current.length) groups.push(current);

      const outFrag = document.createDocumentFragment();
      groups.forEach(group => {
        const onlyLists = group.every(n => n.nodeType === 1 && (n.tagName === 'OL' || n.tagName === 'UL'));
        if (onlyLists) {
          const merged = document.createElement('ol');
          group.forEach(list => {
            Array.from(list.children).forEach(li => merged.appendChild(li.cloneNode(true)));
          });
          outFrag.appendChild(merged);
        } else {
          group.forEach(n => outFrag.appendChild(n.cloneNode(true)));
        }
      });

      const wrapper = document.createElement('div');
      wrapper.appendChild(outFrag);
      return wrapper.innerHTML;
    }

    function showDetail(btn) {
      const dataAttr = btn.getAttribute('data-item') || '{}';
      let data = {};
      try { data = JSON.parse(dataAttr); } catch (e) { console.error('Parse data-item:', e, dataAttr); }

      currentId = data.id_pengajuan;

      document.getElementById('detailId').value        = data.id_pengajuan ?? '';
      document.getElementById('detailDivisi').value    = data.nama_divisi ?? '';
      document.getElementById('detailPosisi').value    = data.nama_posisi ?? '';
      document.getElementById('detailCabang').value    = data.nama_cabang ?? '';
      document.getElementById('detailJumlah').value    = data.jumlah_karyawan ?? '';
      document.getElementById('detailJobPost').value   = data.job_post_number ?? '';
      document.getElementById('detailTipe').value      = data.tipe_pekerjaan ?? '';
      document.getElementById('detailUmur').value      = data.range_umur ?? '';
      document.getElementById('detailTanggal').value   = data.created_at ?? '';
      document.getElementById('detailMinGaji').value   = data.min_gaji ?? '';
      document.getElementById('detailMaxGaji').value   = data.max_gaji ?? '';
      document.getElementById('detailStatusHR').value  = data.status_hr ?? '';
      document.getElementById('detailStatusMng').value = data.status_management ?? '';
      document.getElementById('detailStatusRek').value = data.status_rekrutmen ?? '';
      document.getElementById('detailComment').value   = data.comment ?? '';

      const ta = document.getElementById('detailKualifikasi');
      ta.value = data.kualifikasi ?? '';

      const preview = ensureKualifikasiPreview();
      const decoded    = decodeEntities(ta.value || '');
      const normalized = normalizeLists(decoded);
      const safeHtml   = sanitize(normalized);
      preview.innerHTML = safeHtml;

      ta.classList.add('visually-hidden');
      preview.dataset.plain = (preview.textContent || '').trim();

      if (window.bootstrap && bootstrap.Modal) {
        new bootstrap.Modal(document.getElementById('detailModal')).show();
      }
    }

    async function copyField(id) {
      if (id === 'detailKualifikasi') {
        const preview = document.getElementById('detailKualifikasiPreview');
        if (!preview) return alert('Tidak ada konten untuk disalin.');

        const html  = preview.innerHTML
          .replace(/<ul>/g, '<ul style="list-style-type:disc;margin-left:1.5em;">')
          .replace(/<ol>/g, '<ol style="list-style-type:decimal;margin-left:1.5em;">');
        const plain = preview.textContent.trim();

        if (navigator.clipboard && window.ClipboardItem) {
          try {
            const item = new ClipboardItem({
              "text/html": new Blob([html],  { type: "text/html" }),
              "text/plain": new Blob([plain], { type: "text/plain" }),
            });
            await navigator.clipboard.write([item]);
            alert("✅ Kualifikasi berhasil disalin dengan format.");
            return;
          } catch (e) {
            console.warn("ClipboardItem gagal, fallback", e);
          }
        }

        const temp = document.createElement("div");
        temp.innerHTML = html;
        temp.contentEditable = true;
        temp.style.position = "fixed";
        temp.style.left = "-9999px";
        document.body.appendChild(temp);

        const range = document.createRange();
        range.selectNodeContents(temp);
        const sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(range);
        document.execCommand("copy");
        document.body.removeChild(temp);
        sel.removeAllRanges();

        alert("✅ Kualifikasi berhasil disalin (fallback).");
        return;
      }

      const el = document.getElementById(id);
      const value = el ? (el.value || el.textContent || '') : '';
      navigator.clipboard.writeText(value).then(() => {
        alert('Disalin: ' + value);
      }).catch(err => console.error('Gagal copy', err));
    }

    async function selesaiPengajuan() {
      try {
        await fetch(`http://localhost/nusantara_api/public/api/pengajuan/${currentId}/rekrutmen-review`, {
          method: 'PUT',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ status_rekrutmen: 'Selesai' })
        });
        if (window.bootstrap && bootstrap.Modal) {
          bootstrap.Modal.getInstance(document.getElementById('detailModal'))?.hide();
        }
        loadPengajuanRekrutmen();
      } catch (e) {
        console.error(e);
        alert('Gagal update status rekrutmen');
      }
    }

    loadPengajuanRekrutmen();
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
