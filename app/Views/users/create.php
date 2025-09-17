<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah User - Nusantara Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f8f9fa;
    }
    .sidebar {
      width: 220px;
      position: fixed;
      top: 0;
      left: 0;
      height: 100%;
      background: #f8f9fa;
      border-right: 1px solid #ddd;
      padding-top: 20px;
    }
    .sidebar a {
      display: block;
      padding: 10px 20px;
      color: #333;
      text-decoration: none;
    }
    .sidebar a:hover {
      background: #e9ecef;
    }
    .content {
      margin-left: 240px;
      padding: 20px;
    }
    footer {
      margin-left: 240px;
      background: #222;
      color: #fff;
      text-align: center;
      padding: 15px;
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
     <a href="<?= base_url('dashboard/hr') ?>"> Dashboard</a>
    <a href="<?= base_url('users/create') ?>">
  <img src="https://img.icons8.com/ios-filled/50/000000/add-user-male.png" width="30"> Tambah Akun
</a>

    <a href="#">📂 History</a>
    <a href="#">📝 Thirteen</a>
    <a href="#" class="btn btn-dark w-100 mt-4">Logout</a>
  </div>

  <!-- Content -->
  <div class="content">
    <h3 class="mb-4">Tambah User Baru</h3>

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

      <button type="submit" class="btn btn-primary">Simpan</button>
      <a href="<?= base_url('dashboard/hr') ?>" class="btn btn-secondary">Batal</a>
    </form>
  </div>

  <!-- Footer -->
  <footer>
    NusantaraIT © 2025. All rights reserved.
  </footer>

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
        let response = await fetch("<?= base_url('api/users') ?>", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "Accept": "application/json"
          },
          credentials: "include",  // ✅ penting agar cookie session kebawa
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
