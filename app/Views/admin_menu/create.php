<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah User - Nusantara Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/divisi.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/admin-shared.css') ?>">

</head>

<body class="role-divisi page-history">

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
    <div class="form-card">
      <h3>Tambah User Baru</h3>

      <form id="createUserForm">
        <div class="mb-3">
          <label class="form-label">Username (Email)</label>
          <input type="email" name="username" class="form-control" placeholder="contoh: user@gmail.com" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Full Name</label>
          <input type="text" name="full_name" class="form-control" placeholder="Masukkan nama lengkap" required>
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

        <small class="text-muted d-block mb-3">*Akun baru otomatis tidak aktif. Aktifkan nanti melalui menu Aktivasi.</small>

        <div class="d-flex justify-content-end gap-2">
          <a href="<?= base_url('dashboard/hr') ?>" class="btn btn-secondary">Batal</a>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const BASE = "<?= rtrim(base_url(), '/') ?>"; // contoh: http://localhost/nusantara_api/public

    document.getElementById('createUserForm').addEventListener('submit', async function(e) {
      e.preventDefault();

      const emailRegex = /^[\w\.-]+@([\w-]+\.)+[\w-]{2,4}$/;
      const username = this.username.value.trim();

      if (!emailRegex.test(username)) {
        alert("Username harus berupa alamat email yang valid!");
        return;
      }

      const data = {
        username: username,
        full_name: this.full_name.value.trim(),
        password: "123456",     // default password
        role: this.role.value,
        is_active: 0            // default akun belum aktif
      };

      try {
        const response = await fetch(`${BASE}/api/users`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "Accept": "application/json"
          },
          
          body: JSON.stringify(data)
        });

        if (!response.ok) {
          const text = await response.text();
          throw new Error("HTTP " + response.status + ": " + text);
        }

        const result = await response.json();

        if (result.status === "success") {
          alert("User berhasil dibuat dengan password default 123456. Akun belum aktif.");
          window.location.href = `${BASE}/dashboard/hr`;
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
