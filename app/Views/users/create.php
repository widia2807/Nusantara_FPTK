<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah User - Nusantara Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background-color: #f9fafc;
      color: #212529;
    }

    /* Sidebar */
    .sidebar {
      width: 220px;
      position: fixed;
      top: 0;
      left: 0;
      height: 100%;
      background: #fff;
      border-right: 1px solid #e5e7eb;
      padding-top: 20px;
      box-shadow: 2px 0 6px rgba(0,0,0,0.05);
    }
    .sidebar h6 {
      color: #0d6efd;
      font-weight: 700;
    }
    .sidebar a {
      display: block;
      padding: 10px 20px;
      color: #444;
      text-decoration: none;
      font-size: 14px;
      border-left: 3px solid transparent;
      transition: all 0.2s ease;
    }
    .sidebar a:hover {
      background: #e7f1ff;
      border-left: 3px solid #0d6efd;
      color: #0d6efd;
    }

    /* Content */
    .content {
      margin-left: 240px;
      padding: 30px;
    }

    /* Form Card */
    .form-card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      padding: 25px 30px;
      max-width: 600px;
      margin: auto;
    }
    .form-card h3 {
      color: #0d6efd;
      font-weight: 700;
      margin-bottom: 20px;
    }
    .form-label {
      font-weight: 600;
    }

    /* Button */
    .btn-primary {
      background: #0d6efd;
      border: none;
      font-weight: 500;
      border-radius: 8px;
      padding: 8px 16px;
    }
    .btn-primary:hover {
      background: #0b5ed7;
    }
    .btn-secondary {
      border-radius: 8px;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="text-center mb-4">
      <img src="<?= base_url('assets/images/logo-nusantara-group.png') ?>" alt="Logo" height="40">
      <h6 class="mt-2">Nusantara Portal</h6>
    </div>
    <a href="<?= base_url('dashboard/hr') ?>">📊 Dashboard</a>
    <a href="<?= base_url('users/create') ?>">➕ Tambah Akun</a>
    <a href="<?= base_url('users/hr_history') ?>">📂 History</a>
  </div>

  <!-- Content -->
  <div class="content">
    <div class="form-card">
      <h3>Tambah User Baru</h3>

      <form id="createUserForm">
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Full Name</label>
          <input type="text" name="full_name" class="form-control" placeholder="Masukkan nama lengkap" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Role</label>
          <select name="role" class="form-select" required>
            <option value="">-- Pilih Role --</option>
            <option value="HR">HR</option>
            <option value="Management">Management</option>
            <option value="Rekrutmen">Rekrutmen</option>
            <option value="Divisi">Divisi</option>
          </select>
        </div>

        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
          <label class="form-check-label">Aktif</label>
        </div>

        <div class="d-flex justify-content-end gap-2">
          <a href="<?= base_url('dashboard/hr') ?>" class="btn btn-secondary">Batal</a>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById('createUserForm').addEventListener('submit', async function(e) {
      e.preventDefault();

      const data = {
        username: this.username.value.trim(),
        full_name: this.full_name.value.trim(),
        password: this.password.value.trim(),
        role: this.role.value,
        is_active: this.is_active.checked ? 1 : 0
      };

      try {
        let response = await fetch("http://localhost/nusantara_api/public/api/users", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "Accept": "application/json"
          },
          credentials: "include",
          body: JSON.stringify(data)
        });

        if (!response.ok) {
          let text = await response.text();
          throw new Error("HTTP " + response.status + ": " + text);
        }

        let result = await response.json();

        if (result.status === "success") {
          alert("User berhasil ditambahkan!");
          window.location.href = "<?= base_url('dashboard/hr') ?>";
        } else {
          alert(result.error || "Gagal menambahkan user");
        }
      } catch (err) {
        alert("Terjadi error: " + err.message);
        console.error(err);
      }
    });
  </script>
</body>
</html>
