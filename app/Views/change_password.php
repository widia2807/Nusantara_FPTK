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
        .change-password-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            padding: 2rem;
            width: 100%;
            max-width: 450px;
        }
        .change-password-container {
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
    <div class="change-password-container">
        <div class="change-password-card">
            <div class="text-center mb-4">
                <img src="<?= base_url('assets/images/logo-nusantara-group.png') ?>" alt="Logo" height="60">
                <h3 class="fw-bold mt-2">Change Default Password</h3>
                <p class="text-muted">Ubah password default Anda untuk keamanan</p>
            </div>

            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Form Change Password -->
            <form method="POST" action="<?= base_url('auth/change-password') ?>">
                <?= csrf_field() ?>
                
                <!-- Hidden field untuk ID User -->
                <input type="hidden" name="id_user" value="<?= session()->get('temp_user_id') ?? session()->get('id_user') ?>">
                
                <div class="mb-3">
                    <label class="form-label">Password Lama</label>
                    <input type="password" name="old_password" class="form-control" placeholder="Masukkan password lama" required>
                    <small class="text-muted">Password default: 123456</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="new_password" class="form-control" placeholder="Masukkan password baru" required minlength="6">
                    <small class="text-muted">Minimal 6 karakter</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="confirm_password" class="form-control" placeholder="Ulangi password baru" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Ubah Password</button>
            </form>

            <div class="text-center mt-3">
                <a href="<?= base_url('logout') ?>" class="text-decoration-none">Kembali ke Login</a>
            </div>
        </div>
    </div>

    <footer>
        NusantaraIT © 2025. All rights reserved.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>