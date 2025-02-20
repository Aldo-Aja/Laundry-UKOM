<?php
session_start();
include __DIR__ . '/../config/koneksi.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Use prepared statements to prevent SQL injection
    $query = "SELECT * FROM tb_user WHERE username = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Compare plain text password (for testing only)
        if ($password === $user['password']) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // Store user role in session

            // Redirect to dashboard
            header("Location: ../views/dashboard.php");
            exit();
        } else {
            // Invalid password
            $error = "Password salah!";
        }
    } else {
        // User not found
        $error = "Username tidak ditemukan!";
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Sign in - Washify</title>
    <!-- CSS files -->
    <link href="../assets/css/tabler.min.css?1692870487" rel="stylesheet"/>
    <link href="../assets/css/tabler-flags.min.css?1692870487" rel="stylesheet"/>
    <link href="../assets/css/tabler-payments.min.css?1692870487" rel="stylesheet"/>
    <link href="../assets/css/tabler-vendors.min.css?1692870487" rel="stylesheet"/>
    <link href="../assets/css/demo.min.css?1692870487" rel="stylesheet"/>
    <style>
      @import url('https://rsms.me/inter/inter.css');
      :root {
      	--tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
      }
      body {
      	font-feature-settings: "cv03", "cv04", "cv11";
      }
    </style>
  </head>
  <body class="d-flex flex-column">
    <script src="./dist/js/demo-theme.min.js?1692870487"></script>
    <div class="page page-center">
      <div class="container container-tight py-4">
        <div class="card card-md">
          <div class="card-body">
            <img src="../assets/img/washify_verti.png" alt="">
            <h2 class="h2 text-center mb-4">Silahkan Masuk </h2>
            <?php if (isset($error)): ?>
              <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <form action="login.php" method="post" autocomplete="off">
              <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" name="username" placeholder="Username" autocomplete="off" required>
              </div>
              <div class="mb-2">
                <label class="form-label">
                  Password
                  <span class="form-label-description">
                    <!-- <a href="./forgot-password.html">I forgot password</a> -->
                  </span>
                </label>
                <div class="input-group input-group-flat">
                  <input type="password" class="form-control" name="password" placeholder="Your password" autocomplete="off" required>
                  <span class="input-group-text">
                    <a href="#" class="link-secondary" title="Show password" data-bs-toggle="tooltip" aria-label="Toggle password visibility">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                    </a>
                  </span>
                </div>
              </div>
              <div class="mb-2">
                <label class="form-check">
                  <input type="checkbox" class="form-check-input" name="remember"/>
                  <span class="form-check-label">Remember me on this device</span>
                </label>
              </div>
              <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">Sign in</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- Libs JS -->
    <script src="./dist/js/tabler.min.js?1692870487" defer></script>
    <script src="./dist/js/demo.min.js?1692870487" defer></script>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.querySelector('.input-group-text a');
        const passwordInput = document.querySelector('input[type="password"]');

        togglePassword.addEventListener('click', function (e) {
          e.preventDefault();
          const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
          passwordInput.setAttribute('type', type);
          this.querySelector('svg').classList.toggle('icon-eye');
        });
      });
    </script>
  </body>
</html>