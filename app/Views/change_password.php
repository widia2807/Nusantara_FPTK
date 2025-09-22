<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Change Password - Nusantara Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f8f9fa;
      height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .card {
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      padding: 2rem;
      width: 100%;
      max-width: 420px;
    }
    .container-center {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    footer {
      background: #222;
      color: #fff;
      padding: 15px;
      font-size: 14px;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="container-center">
    <div class="card">
      <div class="text-center mb-4">
        <img src="<?= base_url('assets/images/logo-nusantara-group.png') ?>" alt="Logo" height="60">
        <h4 class="fw-bold mt-2">Change Password</h4>
      </div>

      <!-- Form Change Password -->
      <form action="<?= base_url('auth/change-password') ?>" method="post">
        <?= csrf_field() ?>
        <div class="mb-3">
          <label class="form-label">Password Lama</label>
          <input type="password" name="old_password" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password Baru</label>
          <input type="password" name="new_password" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Konfirmasi Password Baru</label>
          <input type="password" name="confirm_password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success w-100">Ubah Password</button>
      </form>
    </div>
  </div>

  <footer>
    NusantaraIT © 2025. All rights reserved.
  </footer>
</body>
</html>
