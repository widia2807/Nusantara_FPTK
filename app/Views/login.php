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
          <input type="checkbox"> Remember me
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

    let formData = new FormData(this);

    try {
      let response = await fetch("<?= base_url('api/login') ?>", {
        method: "POST",
        body: formData
      });

      let result = await response.json();

      if(result.user){   // sesuai Auth.php (return 'user')
        // simpan data user ke localStorage
        localStorage.setItem("user", JSON.stringify(result.user));

        // kalau nanti ada token tinggal ditambah:
        // localStorage.setItem("auth_token", result.token);

        // redirect ke dashboard
        window.location.href = "<?= base_url('dashboard') ?>";
      } else {
        alert(result.error || "Login gagal");
      }
    } catch(err) {
      alert("Terjadi error: " + err.message);
    }
  });
  </script>
</body>
</html>
