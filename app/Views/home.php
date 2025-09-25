<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NusantaraPortal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background: #f9fafc;
      color: #333;
    }

    /* Navbar */
    .navbar {
      background: #fff;
      border-bottom: 1px solid #eee;
    }

    /* Hero */
    .hero-section {
      min-height: 90vh;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      padding: 60px 0;
    }
    .hero-text {
      max-width: 550px;
    }
    .hero-text h1 {
      font-size: 2.8rem;
      color: #222;
    }
    .hero-text h4 {
      font-size: 1.25rem;
      font-weight: normal;
      color: #555;
    }
    .hero-text p.text-muted {
      font-size: 0.95rem;
      line-height: 1.6;
    }

    /* Hero image */
    .hero-image img {
      max-width: 500px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }

    /* Button style */
    .btn-login {
      background: linear-gradient(90deg, #0d6efd, #0dcaf0);
      border: none;
      color: #fff !important;
      font-weight: 600;
      border-radius: 30px;
      padding: 8px 24px;
      transition: 0.3s ease;
    }
    .btn-login:hover {
      opacity: 0.9;
      transform: translateY(-2px);
    }

    /* Footer */
    footer {
      background: #111;
      color: #aaa;
      padding: 18px;
      font-size: 14px;
      margin-top: 50px;
    }
  </style>
</head>
<body>
<!-- Navbar -->
<nav class="navbar px-4 shadow-sm">
  <a class="navbar-brand d-flex align-items-center" href="#">
    <img src="<?= base_url('assets/images/logo-nusantara-group.png') ?>" 
         alt="Logo Nusantara Group" 
         style="height:50px; width:auto; object-fit:contain;">
    <span class="ms-2 fw-bold text-dark" style="font-size: 1.2rem;">
      Nusantara Portal
    </span>
  </a>
  <a href="<?= base_url('login') ?>" class="btn btn-login">Log In</a>
</nav>

<!-- Hero Section -->
<div class="container hero-section">
  <!-- Text -->
  <div class="hero-text">
    <p class="text-primary fw-bold">Nusantara Group Internal Website</p>
    <h1 class="fw-bold">FPTK Website</h1>
    <h4 class="mt-3">
      Website ini dibuat untuk mengautomasi pengajuan karyawan tiap divisi<br>
      dan diharapkan dapat mempermudah proses kebutuhan SDM
    </h4>
    <p class="text-muted mt-4">
      Sistem ini dirancang untuk mempermudah koordinasi antar divisi, memastikan kebutuhan karyawan dapat dipantau dengan baik, 
      serta mendukung transparansi dalam setiap proses pengajuan.
    </p>
  </div>

  <!-- Image -->
  <div class="hero-image">
    <img src="<?= base_url('assets/images/mtharyono.webp') ?>" alt="Gedung MT Haryono" class="img-fluid">
  </div>
</div>

<!-- Footer -->
<footer class="text-center">
  NusantaraIT © 2025. All rights reserved.
</footer>
</body>
</html>
