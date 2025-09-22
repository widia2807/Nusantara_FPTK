<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Nusantara Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f8f9fa;
      height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .login-card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      padding: 2rem;
      width: 100%;
      max-width: 400px;
    }
    .login-container {
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
  <!-- Content -->
  <div class="login-container">
    <div class="login-card">
      <div class="text-center mb-4">
        <img src="<?= base_url('assets/images/logo-nusantara-group.png') ?>" alt="Logo" height="60">
        <h3 class="fw-bold mt-2">Nusantara Portal</h3>
      </div>

      <!-- Form Login -->
      <form id="loginForm">
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" name="username" class="form-control" placeholder="Enter username" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" placeholder="Enter password" required>
        </div>
        <div class="mb-3">
  <input type="checkbox" id="rememberMe"> Remember me
</div>

        <button type="submit" class="btn btn-primary w-100">Log In</button>
      </form>
    </div>
  </div>

  <!-- Footer -->
  <footer>
    NusantaraIT © 2025. All rights reserved.
  </footer>

  <!-- Script -->
  <script>
  document.getElementById('loginForm').addEventListener('submit', async function(e){
    e.preventDefault();

    let data = {
      username: this.username.value.trim(),
      password: this.password.value.trim()
    };

    try {
      let response = await fetch("<?= base_url('api/login') ?>", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
      });

      let result = await response.json();

      // 🔴 Kalau status "force_change_password" dari backend
if(result.status === "force_change_password"){
  // Simpan user sementara (supaya tahu ID siapa yg mau ubah password)
  sessionStorage.setItem("user", JSON.stringify(result.user));
  alert(result.message || "Anda harus ubah password default terlebih dahulu.");
  window.location.href = "<?= base_url('auth/change-password') ?>"; 
  return;
}

      if(result.user){
  if(document.getElementById("rememberMe").checked){
    localStorage.setItem("user", JSON.stringify(result.user));  // tetap tersimpan
  } else {
    sessionStorage.setItem("user", JSON.stringify(result.user)); // hilang saat close browser
  }
        switch(result.user.role){
          case "HR":
            window.location.href = "<?= base_url('dashboard/hr') ?>";
            break;
          case "Management":
            window.location.href = "<?= base_url('dashboard/management') ?>";
            break;
          case "Rekrutmen":
            window.location.href = "<?= base_url('dashboard/rekrutmen') ?>";
            break;
          case "Divisi":
            window.location.href = "<?= base_url('dashboard/divisi') ?>";
            break;
          default:
            alert("Role tidak dikenal!");
        }
      } else {
        alert(result.error || "Login gagal");
      }

    } catch(err) {
      alert("Terjadi error: " + err.message);
      console.error(err);
    }
  });
  </script>
</body>
</html>
