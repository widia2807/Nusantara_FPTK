<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form Pengajuan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background: #f9fafc;
      color: #212529;
    }

    /* Header */
    .header {
      height: 55px;
      background: linear-gradient(90deg, #0d6efd, #0dcaf0);
      color: #fff;
      display: flex;
      align-items: center;
      padding: 0 20px;
      font-weight: 600;
      letter-spacing: .5px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }

    /* Sidebar */
    .sidebar {
      width: 220px;
      position: fixed;
      top: 0;
      left: 0;
      bottom: 0;
      background: #fff;
      border-right: 1px solid #e5e7eb;
      padding: 20px 10px;
      box-shadow: 2px 0 6px rgba(0,0,0,0.05);
    }
    .sidebar h6 {
      color: #0d6efd;
      font-weight: 700;
    }
    .sidebar a {
      display: block;
      padding: 10px 15px;
      margin-bottom: 5px;
      color: #444;
      text-decoration: none;
      font-size: 14px;
      border-left: 3px solid transparent;
      border-radius: 6px;
      transition: all 0.2s ease;
    }
    .sidebar a:hover {
      background: #e7f1ff;
      border-left: 3px solid #0d6efd;
      color: #0d6efd;
    }

    /* Content */
    .content {
      margin-left: 220px;
      padding: 30px;
    }

    /* Form Card */
    .form-card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      padding: 30px;
      transition: transform 0.2s ease;
    }
    .form-card:hover {
      transform: translateY(-2px);
    }
    .form-card h4 {
      font-weight: 700;
      color: #0d6efd;
    }

    /* Label */
    .form-label {
      font-weight: 600;
      color: #333;
    }

    /* Button */
    .btn-primary {
      background: #0d6efd;
      border: none;
      transition: all 0.2s ease;
    }
    .btn-primary:hover {
      background: #0b5ed7;
    }
    .btn-secondary {
      background: #6c757d;
      border: none;
    }

    /* Footer */
    footer {
      margin-left: 220px;
      background: #111;
      color: #bbb;
      text-align: center;
      padding: 12px;
      font-size: 14px;
      margin-top: 30px;
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
  <div class="text-center mb-4">
    <img src="<?= base_url('assets/images/logo-nusantara-group.png') ?>" alt="Logo" height="40">
    <h6 class="mt-2">Nusantara Portal</h6>
  </div>

  <a href="<?= base_url('dashboard/divisi') ?>">📊 Dashboard</a>
  <a href="<?= base_url('pengajuan') ?>">
    <img src="<?= base_url('assets/images/checklist.png') ?>" alt="Pengajuan" height="18" class="me-2">
    Pengajuan
  </a>
  <a href="<?= base_url('history') ?>">📂 History</a>
  
</div>


  <!-- Content -->
  <div class="content">
    <div class="form-card">
      <h4 class="mb-4 text-center">Form Pengajuan</h4>

      <!-- Flash message -->
      <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
          <?= session()->getFlashdata('success') ?>
        </div>
      <?php endif; ?>

      <form action="<?= base_url('pengajuan/store') ?>" method="post">
  <div class="mb-3">
    <label class="form-label">Divisi</label>
    <select name="id_divisi" class="form-select" required>
      <option value="">Pilih Divisi</option>
      <?php if(!empty($divisi)): ?>
        <?php foreach($divisi as $d): ?>
          <option value="<?= $d['id_divisi'] ?>"><?= esc($d['nama_divisi']) ?></option>
        <?php endforeach; ?>
      <?php endif; ?>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label">Posisi</label>
    <select name="id_posisi" class="form-select" required>
      <option value="">Pilih Posisi</option>
      <?php if(!empty($posisi)): ?>
        <?php foreach($posisi as $p): ?>
          <option value="<?= $p['id_posisi'] ?>"><?= esc($p['nama_posisi']) ?></option>
        <?php endforeach; ?>
      <?php endif; ?>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label">Cabang</label>
    <select name="id_cabang" class="form-select" required>
      <option value="">Pilih Cabang</option>
      <?php if(!empty($cabang)): ?>
        <?php foreach($cabang as $c): ?>
          <option value="<?= $c['id_cabang'] ?>"><?= esc($c['nama_cabang']) ?></option>
        <?php endforeach; ?>
      <?php endif; ?>
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
    </div>
  </div>

  <!-- Footer -->
  <!-- <footer>
    NusantaraIT © 2025. All rights reserved.
  </footer> -->

</body>
</html>
