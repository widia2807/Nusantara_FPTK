<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NusantaraPortal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
    }
    .hero-section {
      min-height: 90vh;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
    }
    .hero-text {
      max-width: 550px;
    }
    footer {
      background: #222;
      color: #fff;
      padding: 15px;
      font-size: 14px;
    }
  </style>
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-light bg-light px-4 shadow-sm">
  <a class="navbar-brand d-flex align-items-center" href="#">
    <img src="<?= base_url('assets/images/logo-nusantara-group.png') ?>" 
         alt="Logo Nusantara Group" 
         style="height:50px; width:auto; object-fit:contain;">
    <span class="ms-2 fw-bold text-dark" style="font-size: 1.2rem;">
      Nusantara Portal
    </span>
  </a>
  <a href="<?= base_url('login') ?>" class="btn btn-outline-primary px-4">Log In</a>

</nav>


  <!-- Hero Section -->
  <div class="container hero-section">
    <!-- Text -->
    <div class="hero-text">
      <p class="text-primary fw-bold">Nusantara Group Internal Website</p>
      <h1 class="fw-bold">Purus sagittis fringilla arcu neque.</h1>
      <h4 class="mt-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>
      Bibendum amet at molestie mattis.</h4>
      <p class="text-muted mt-4">
        Rhoncus morbi et augue nec, in id ullamcorper at sit. Condimentum sit nunc in eros scelerisque sed.
        Commodo in viverra nunc, ullamcorper ut. Non, amet, aliquet scelerisque nullam sagittis.
      </p>
    </div>

    <!-- Image -->
    <div class="hero-image">
      <img src="<?= base_url('assets/images/mtharyono.webp') ?>" alt="Gedung MT Haryono" class="img-fluid" style="max-width:450px;">
    </div>
  </div>

  <!-- Footer -->
  <footer class="text-center">
    NusantaraIT © 2025. All rights reserved.
  </footer>
</body>
</html>
