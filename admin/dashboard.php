<?php
    session_start();

    if (!isset($_SESSION['id_user'])) {
        header("Location: ../login.php");
        exit;
    }
?>

<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Admin Mahatar</title>
    <link rel="stylesheet" href="assets/style/WebMahatarAMNI (STYLE).css" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
      rel="stylesheet"
    />

    <!-- FontAwesome untuk Icon -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
  </head>
  <body>
    <main class="dashboard-container">
      <header class="dashboard-header">
        <h1 class="dashboard-title">Dashboard Admin Mahatar</h1>
      </header>

      <div class="alert-banner">
        <i
          class="fa-solid fa-triangle-exclamation"
          style="font-size: 1.2rem"
        ></i>
        <span
          ><strong>Ingat!</strong> Selalu logout setelah menggunakan dashboard
          admin untuk menghindari akses yang tidak diinginkan.</span
        >
      </div>

      <h2 class="section-title">Menu Utama</h2>

      <ul class="admin-menu-grid">
        <li>
          <a href="berita/index.php" class="menu-card">
            <i class="fa-solid fa-newspaper"></i>
            <span>Kelola Berita</span>
          </a>
        </li>
        
        <li>
          <a href="galeri/index.php" class="menu-card">
            <i class="fa-solid fa-images"></i>
            <span>Kelola Galeri</span>
          </a>
        </li>

        <li>
          <a href="prestasi/index.php" class="menu-card">
            <i class="fa-solid fa-trophy"></i>
            <span>Kelola Prestasi</span>
          </a>
        </li>
      </ul>

        <div class="dashboard-footer">
          <a href="logout.php" class="btn-logout">
            <i class="fa-solid fa-right-from-bracket"></i> Logout
          </a>
        </div>
    </main>
  </body>
</html>
