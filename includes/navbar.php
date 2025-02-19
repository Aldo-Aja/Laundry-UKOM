<link href="../assets/css/tabler.min.css?1692870487" rel="stylesheet"/>
<link href="../assets/css/tabler-flags.min.css?1692870487" rel="stylesheet"/>
<link href="../assets/css/tabler-payments.min.css?1692870487" rel="stylesheet"/>
<link href="../assets/css/tabler-vendors.min.css?1692870487" rel="stylesheet"/>
<link href="../assets/css/demo.min.css?1692870487" rel="stylesheet"/>
<script src="./dist/js/demo-theme.min.js?1692870487"></script>

<!-- Sidebar -->
<aside class="navbar navbar-vertical navbar-expand-lg navbar-transparent">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu" aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <h1 class="navbar-brand navbar-brand-autodark">
      <a href="../views/dashboard.php">
        <img src="../assets/img/washify_hori.png" width="300px" height="100px" alt="Logo" class="navbar-brand-image">
      </a>
    </h1>
    <!-- Bagian user dan mode -->
    <div class="navbar-nav flex-row d-lg-none">
      <!-- Contoh link mode, notifikasi, dll -->
      <!-- ... -->
      <div class="nav-item dropdown">
        <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
          <span class="avatar avatar-sm" style="background-image: url(./static/avatars/000m.jpg)"></span>
          <div class="d-none d-xl-block ps-2">
            <div>
              <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : "Guest"; ?>
            </div>
            <div class="mt-1 small text-secondary">
              <?php echo isset($_SESSION['role']) ? htmlspecialchars($_SESSION['role']) : "Guest"; ?>
            </div>
          </div>
        </a>
        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
          <a href="../public/logout.php" class="dropdown-item">Logout</a>
        </div>
      </div>
    </div>
    <!-- Sidebar Menu -->
    <div class="collapse navbar-collapse" id="sidebar-menu">
      <ul class="navbar-nav pt-lg-3">
        <!-- Menu: Home (untuk semua role) -->
        <li class="nav-item">
          <a class="nav-link" href="dashboard.php">
            <span class="nav-link-icon d-md-none d-lg-inline-block">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
              </svg>
            </span>
            <span class="nav-link-title">Home</span>
          </a>
        </li>

        <!-- Menu Khusus Admin -->
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
          <!-- Regis Pelanggan -->
          <li class="nav-item">
            <a class="nav-link" href="tambahuser.php">
              <span class="nav-link-icon d-md-none d-lg-inline-block">
                <!-- Icon untuk Regis Pelanggan -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                  <path d="M5 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                  <path d="M3 21v-2a4 4 0 0 1 4 -4h4c.96 0 1.84 .338 2.53 .901" />
                  <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                  <path d="M16 19h6" />
                  <path d="M19 16v6" />
                </svg>
              </span>
              <span class="nav-link-title">Regis Pelanggan</span>
            </a>
          </li>

          <!-- CRUD Outlet, Paket Cucian & Pelanggan -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#admin-menu" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="false">
              <span class="nav-link-icon d-md-none d-lg-inline-block">
                <!-- Icon untuk pengelolaan data -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                  <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" />
                  <path d="M12 12l8 -4.5" />
                  <path d="M12 12l0 9" />
                  <path d="M12 12l-8 -4.5" />
                  <path d="M16 5.25l-8 4.5" />
                </svg>
              </span>
              <span class="nav-link-title">Pengelolaan Data</span>
            </a>
            <div class="dropdown-menu">
              <div class="dropdown-menu-columns">
                <div class="dropdown-menu-column">
                  <a class="dropdown-item" href="outlet.php">Outlet</a>
                  <a class="dropdown-item" href="paket.php">Paket Cucian</a>
                  <!-- Jika ada halaman tersendiri untuk data pelanggan, misalnya pelanggan.php -->
                  <a class="dropdown-item" href="pelanggan.php">Data Pelanggan</a>
                </div>
              </div>
            </div>
          </li>
        <?php endif; ?>

        <!-- Menu Entri Transaksi (untuk admin dan kasir) -->
        <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin','kasir'])): ?>
          <li class="nav-item">
            <a class="nav-link" href="transaksi.php">
              <span class="nav-link-icon d-md-none d-lg-inline-block">
                <!-- Icon Entri Transaksi -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                  <path d="M4 6h16" />
                  <path d="M4 12h16" />
                  <path d="M4 18h16" />
                </svg>
              </span>
              <span class="nav-link-title">Entri Transaksi</span>
            </a>
          </li>
        <?php endif; ?>

        <!-- Menu Generate Laporan (untuk semua role: admin, kasir, owner) -->
        <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin','kasir','owner'])): ?>
          <li class="nav-item">
            <a class="nav-link" href="laporan.php">
              <span class="nav-link-icon d-md-none d-lg-inline-block">
                <!-- Icon Laporan -->
               <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-printer"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" /></svg>
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                  <path d="M3 3h18v18h-18z" />
                </svg>
              </span>
              <span class="nav-link-title">Laporan</span>
            </a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</aside>

<!-- Header -->
<header class="navbar navbar-expand-md d-none d-lg-flex d-print-none">
  <div class="container-xl">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-nav flex-row order-md-last">
      <div class="d-none d-md-flex">
        <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Enable dark mode" data-bs-toggle="tooltip" data-bs-placement="bottom">
          <!-- Icon Moon -->
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" />
          </svg>
        </a>
        <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Enable light mode" data-bs-toggle="tooltip" data-bs-placement="bottom">
          <!-- Icon Sun -->
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
            <path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" />
          </svg>
        </a>
      </div>
      <div class="nav-item dropdown">
        <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
          <span class="avatar avatar-sm" style="background-image: url(./static/avatars/000m.jpg)"></span>
          <div class="d-none d-xl-block ps-2">
            <div>
              <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : "Guest"; ?>
            </div>
          </div>
        </a>
        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
          <a href="../public/logout.php" class="dropdown-item">Logout</a>
        </div>
      </div>
    </div>
    <div class="collapse navbar-collapse" id="navbar-menu">
      <!-- Menu tambahan jika diperlukan -->
    </div>
  </div>
</header>
