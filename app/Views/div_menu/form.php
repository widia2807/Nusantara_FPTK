<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form Pengajuan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { font-family: Arial, sans-serif; background: #f8f9fa; }
    .sidebar {
      width: 220px; position: fixed; top: 0; left: 0; bottom: 0;
      background: #fff; border-right: 1px solid #ddd; padding: 20px 10px;
    }
    .sidebar h5 { font-weight: bold; margin-bottom: 20px; }
    .sidebar a {
      display: block; padding: 10px 15px; margin-bottom: 5px;
      color: #333; text-decoration: none; border-radius: 6px;
    }
    .sidebar a:hover { background: #f0f0f0; }
    .header {
      height: 50px; background: #222; color: #fff;
      display: flex; align-items: center; padding: 0 20px;
    }
    .content {
      margin-left: 220px; padding: 30px;
    }
    .form-card {
      background: #fff; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      padding: 30px;
    }
    .form-label { font-weight: 600; }
    footer {
      margin-left: 220px; background: #222; color: #fff;
      text-align: center; padding: 12px; margin-top: 30px;
    }
  </style>
</head>
<body>

  <!-- Header -->
  <div class="header">
    <h6 class="mb-0">Form Pengajuan</h6>
  </div>

  <!-- Sidebar -->
  <div class="sidebar">
    <h5>Nusantara</h5>
    <a href="<?= base_url('dashboard/divisi') ?>">📊 Dashboard</a>
    <a href="<?= base_url('form') ?>">📝 Pengajuan</a>
    <a href="<?= base_url('history') ?>">📂 History</a>
    <a href="<?= base_url('logout') ?>" class="btn btn-dark w-100 mt-4">Logout</a>
  </div>

  <!-- Content -->
  <div class="content">
    <div class="form-card">
      <h4 class="mb-4 text-center">Form Pengajuan</h4>

      <form id="pengajuanForm">
        <div class="mb-3">
          <label class="form-label">Divisi</label>
          <select name="id_divisi" class="form-select" required>
            <option value="">Pilih Divisi</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Posisi</label>
          <select name="id_posisi" class="form-select" required>
            <option value="">Pilih Posisi</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Cabang</label>
          <select name="id_cabang" class="form-select" required>
            <option value="">Pilih Cabang</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Jumlah Karyawan</label>
          <input type="number" name="jumlah_karyawan" class="form-control" placeholder="contoh: 5" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Job Post Number</label>
          <input type="text" name="job_post_number" class="form-control" placeholder="JP-001" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Tipe Pekerjaan</label>
          <select name="tipe_pekerjaan" class="form-select" required>
            <option value="">Pilih Tipe</option>
            <option value="Intern">Intern</option>
            <option value="Kontrak">Kontrak</option>
            <option value="Tetap">Tetap</option>
            <option value="Freelance">Freelance</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Range Umur</label>
          <input type="text" name="range_umur" class="form-control" placeholder="20-30 tahun">
        </div>
        <div class="mb-3">
          <label class="form-label">Tempat Kerja</label>
          <input type="text" name="tempat_kerja" class="form-control" placeholder="Jakarta">
        </div>
        <div class="mb-3">
          <label class="form-label">Kualifikasi</label>
          <textarea name="kualifikasi" rows="3" class="form-control" placeholder="contoh: S1 Teknik Informatika"></textarea>
        </div>
        <div class="d-flex justify-content-end">
          <button type="reset" class="btn btn-secondary me-2">Reset</button>
          <button type="submit" class="btn btn-primary">Ajukan</button>
        </div>
      </form>

      <div class="mt-4">
        <h6>Respon API:</h6>
        <pre id="result" class="bg-light p-3 border"></pre>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer>
    NusantaraIT © 2025. All rights reserved.
  </footer>

  <script>
    document.getElementById('pengajuanForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      const formData = new FormData(e.target);
      const data = Object.fromEntries(formData);

      const res = await fetch('http://localhost/nusantara_api/public/api/pengajuan', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      });

      const result = await res.json();
      document.getElementById('result').textContent = JSON.stringify(result, null, 2);
    });
  </script>

</body>
</html>
