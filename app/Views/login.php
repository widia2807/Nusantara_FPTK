<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Nusantara Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background: linear-gradient(135deg, #313233ff, #0dcaf0);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .login-container {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .login-card {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.12);
      padding: 2.5rem;
      width: 100%;
      max-width: 420px;
      animation: fadeIn 0.8s ease;
    }

    .login-card img {
      height: 65px;
    }

    .login-card h3 {
      font-weight: 700;
      color: #2f2f30ff;
    }

    .form-label {
      font-weight: 500;
    }

    .btn-login {
      background: linear-gradient(90deg, #525457ff, #0dcaf0);
      border: none;
      border-radius: 30px;
      font-weight: 600;
      padding: 10px;
      transition: all 0.3s ease;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      opacity: 0.9;
    }

    footer {
      background: #111;
      color: #bbb;
      padding: 15px;
      font-size: 14px;
      text-align: center;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-card">
      <div class="text-center mb-4">
        <img src="<?= base_url('assets/images/logo-nusantara-group.png') ?>" alt="Logo">
        <h3 class="mt-3">Nusantara Portal</h3>
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
        <div class="mb-3 form-check">
          <input type="checkbox" class="form-check-input" id="rememberMe">
          <label class="form-check-label" for="rememberMe">Remember me</label>
        </div>

        <button type="submit" class="btn btn-login w-100">Log In</button>
      </form>
    </div>
  </div>

  <footer>
    NusantaraIT © 2025. All rights reserved.
  </footer>

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

      // Handle force password change
      if(result.status === "force_change_password"){
        alert(result.message || "Anda harus ubah password default terlebih dahulu.");
        window.location.href = result.redirect_url || "<?= base_url('auth/change-password') ?>";
        return;
      }

      // Handle successful login
      if(result.status === "success" && result.user){
        if(document.getElementById("rememberMe").checked){
          localStorage.setItem("user", JSON.stringify(result.user));
        } else {
          sessionStorage.setItem("user", JSON.stringify(result.user));
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
