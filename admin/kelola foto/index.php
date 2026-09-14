<?php

session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: ../../login.php");
    exit;
}

include "../../config/koneksi.php";


// =========================
// AMBIL DATA FOTO BERITA & PRESTASI
// =========================

$query_berita = "SELECT 
        b.id_berita,
        b.judul,
        b.gambar,
        b.tanggal,
        kb.nama_kategori
    FROM berita b
    LEFT JOIN kategori_berita kb
        ON b.id_kategori = kb.id_kategori
    WHERE b.gambar IS NOT NULL
    AND b.gambar != ''
    ORDER BY b.tanggal DESC
";

$result_berita = mysqli_query($conn, $query_berita);


// =========================
// AMBIL DATA FOTO KEGIATAN
// =========================

$query_kegiatan = "SELECT
        k.id_kegiatan,
        k.judul,
        k.gambar,
        k.tanggal,
        kk.nama_kegiatan
    FROM kegiatan k
    LEFT JOIN kategori_kegiatan kk
        ON k.id_kategori_kegiatan = kk.id_kategori_kegiatan
    WHERE k.gambar IS NOT NULL
    AND k.gambar != ''
    ORDER BY kk.nama_kegiatan ASC, k.tanggal DESC
";

$result_kegiatan = mysqli_query($conn, $query_kegiatan);

?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kelola Foto - Mahatar AMNI</title>

  <!-- Google Fonts & FontAwesome Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    /* ==========================================
   VARIABLES & THEME SYSTEM
   ========================================== */
:root {
  --bg-dark: #071325;
  --bg-card: rgba(13, 30, 56, 0.75);

  --text-primary: #f0f6ff;
  --text-secondary: #94a3b8;

  --accent-blue: #0284c7;
  --accent-cyan: #38bdf8;
  --accent-gold: #f59e0b;

  --gradient-accent: linear-gradient(135deg, #0284c7 0%, #38bdf8 100%);

  --custom-mesh-bg: 
    radial-gradient(circle at 50% 35%, rgba(56, 189, 248, 0.25) 0%, transparent 50%),
    radial-gradient(circle at 80% 80%, rgba(129, 140, 248, 0.15) 0%, transparent 40%),
    radial-gradient(circle at 20% 20%, rgba(34, 211, 238, 0.15) 0%, transparent 40%),
    linear-gradient(180deg, #090d16 0%, #0f172a 100%);

  --border-glass: 1px solid rgba(255, 255, 255, 0.08);
  --border-active: 1px solid rgba(56, 189, 248, 0.4);
  --glass-backdrop: blur(16px) saturate(180%);
  --radius-lg: 24px;
  --radius-md: 16px;
  --radius-sm: 12px;
  --transition-smooth: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

[data-theme="light"] {
  --bg-dark: #b8c5d6;
  --bg-card: rgba(226, 232, 240, 0.85);
  --glass-backdrop: blur(16px);
  --border-glass: 1px solid rgba(100, 116, 139, 0.25);

  --text-primary: #1e293b;
  --text-secondary: #212122;

  --accent-blue: #1e40af;
  --accent-cyan: #0284c7;
  --accent-gold: #b45309;

  --gradient-accent: linear-gradient(135deg, #1e40af 0%, #0369a1 100%);

  --custom-mesh-bg: 
    radial-gradient(circle at 50% 35%, rgba(9, 148, 207, 0.57) 0%, transparent 50%),
    radial-gradient(circle at 80% 80%, rgba(245, 159, 11, 0.24) 0%, transparent 40%),
    radial-gradient(circle at 20% 20%, rgba(14, 164, 233, 0.3) 0%, transparent 40%),
    linear-gradient(180deg, #f0f4f9 0%, #e2e8f0 100%);
}

/* ==========================================
   RESET & LAYOUT BASE
   ========================================== */
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
  font-family: 'Plus Jakarta Sans', sans-serif;
}

body {
  background: var(--custom-mesh-bg);
  background-attachment: fixed;
  color: var(--text-primary);
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: flex-start;
  padding: 40px 20px;
  transition: var(--transition-smooth);
}

.dashboard-card {
  background: var(--bg-card);
  backdrop-filter: var(--glass-backdrop);
  -webkit-backdrop-filter: var(--glass-backdrop);
  border: var(--border-glass);
  border-radius: var(--radius-lg);
  max-width: 1100px;
  width: 100%;
  padding: 36px;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
  transition: var(--transition-smooth);
}

/* Header & Nav */
.top-nav {
  margin-bottom: 24px;
}

.header-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 32px;
  gap: 20px;
  flex-wrap: wrap;
}

.page-title {
  font-size: 2rem;
  font-weight: 800;
  color: var(--text-primary);
  margin-bottom: 4px;
}

.page-subtitle {
  color: var(--text-secondary);
  font-size: 0.9rem;
}

/* ==========================================
   PHOTO GALLERY GRID
   ========================================== */
.photo-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 24px;
}

.photo-card {
  background: rgba(0, 0, 0, 0.15);
  border: var(--border-glass);
  border-radius: var(--radius-md);
  overflow: hidden;
  display: flex;
  flex-direction: column;
  transition: var(--transition-smooth);
}

[data-theme="light"] .photo-card {
  background: rgba(255, 255, 255, 0.5);
}

.photo-card:hover {
  transform: translateY(-6px);
  border-color: var(--accent-cyan);
  box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
}

/* Image Wrapper with fixed Aspect Ratio */
.photo-wrapper {
  width: 100%;
  height: 200px;
  overflow: hidden;
  position: relative;
  background: rgba(0, 0, 0, 0.2);
}

.photo-wrapper img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: var(--transition-smooth);
}

.photo-card:hover .photo-wrapper img {
  transform: scale(1.05);
}

/* Photo Details */
.photo-content {
  padding: 16px 20px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  flex-grow: 1;
}

.photo-title {
  font-size: 0.98rem;
  font-weight: 700;
  color: var(--text-primary);
  line-height: 1.4;
  margin-bottom: 12px;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.photo-meta {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 0.82rem;
  color: var(--text-secondary);
}

.photo-meta i {
  color: var(--accent-cyan);
}

/* ==========================================
   EMPTY STATE
   ========================================== */
.empty-state {
  text-align: center;
  padding: 60px 20px;
  color: var(--text-secondary);
}

.empty-icon {
  font-size: 3rem;
  margin-bottom: 12px;
  display: block;
  opacity: 0.6;
}

/* ==========================================
   BUTTONS & NAVIGATION
   ========================================== */
.btn-back {
  color: var(--text-secondary);
  text-decoration: none;
  font-size: 0.9rem;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: var(--transition-smooth);
}

.btn-back:hover {
  color: var(--accent-cyan);
}

.btn-add {
  background: var(--gradient-accent);
  color: #ffffff;
  text-decoration: none;
  padding: 10px 20px;
  border-radius: var(--radius-sm);
  font-weight: 600;
  font-size: 0.9rem;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: var(--transition-smooth);
  box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
}

.btn-add:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 18px rgba(2, 132, 199, 0.4);
}

/* Floating Theme Toggle */
.btn-theme-toggle {
  position: fixed;
  top: 20px;
  right: 20px;
  width: 44px;
  height: 44px;
  border-radius: 50%;
  background: var(--bg-card);
  backdrop-filter: var(--glass-backdrop);
  border: var(--border-glass);
  color: var(--text-primary);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.1rem;
  cursor: pointer;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
  transition: var(--transition-smooth);
  z-index: 100;
}

.btn-theme-toggle:hover {
  transform: scale(1.1);
  color: var(--accent-cyan);
}

/* ========================= */
/* PHOTO SECTION */
/* ========================= */

.photo-section {
  margin-top: 32px;
}

.section-heading {
  margin-bottom: 20px;
}

.section-heading h2 {
  margin: 0 0 6px;
  font-size: 24px;
  font-weight: 800;
  color: var(--text-primary);
}

.section-heading p {
  margin: 0;
  font-size: 14px;
  color: var(--text-secondary);
}


/* ========================= */
/* ACTIVITY GROUP */
/* ========================= */

.activity-group {
  margin-top: 30px;
}

.activity-group-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  margin-bottom: 16px;
  padding-bottom: 12px;
  border-bottom: 1px solid var(--border-color);
}

.activity-group-header h3 {
  margin: 0;
  font-size: 19px;
  font-weight: 800;
  color: var(--text-primary);
}

.activity-group-header span {
  padding: 6px 10px;
  border-radius: 8px;
  background: var(--surface-secondary);
  color: var(--text-secondary);
  font-size: 12px;
  font-weight: 700;
  white-space: nowrap;
}


/* ========================= */
/* CATEGORY LABEL */
/* ========================= */

.photo-category {
  display: inline-block;
  margin-bottom: 8px;
  padding: 5px 9px;
  border-radius: 7px;
  background: var(--surface-secondary);
  color: var(--text-secondary);
  font-size: 11px;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.4px;
}
  </style>
</head>

<body>

    <button id="themeToggle" class="btn-theme-toggle" type="button" aria-label="Toggle Theme">
    <i id="themeIcon" class="fa-solid fa-moon"></i>
  </button>

    <main class="dashboard-card">
    <!-- Top Nav Back Link -->
    <div class="top-nav">
      <a href="../dashboard.php" class="btn-back">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
      </a>
    </div>

    <!-- Header Section -->
    <div class="header-actions">
      <div class="header-text">
        <h1 class="page-title">Kelola Foto Galeri</h1>
        <p class="page-subtitle">
          Kumpulan foto media yang digunakan pada berita dan prestasi Mahatar AMNI.
        </p>
      </div>
      <a href="../berita/tambah.php" class="btn-add">
        <i class="fa-solid fa-plus"></i> Tambah Berita Baru

      </a>
      <a href="../kegiatan/tambah.php" class="btn-add">
        <i class="fa-solid fa-plus"></i> Tambah Kegiatan Baru
      </a>
    </div>

    <!-- Photo Gallery Grid -->
<section class="photo-section">

  <div class="section-heading">
    <h2>Berita & Prestasi</h2>
    <p>Foto yang digunakan pada berita dan prestasi Mahatar AMNI.</p>
  </div>

  <?php if (mysqli_num_rows($result_berita) > 0) : ?>

    <div class="photo-grid">

      <?php while ($foto = mysqli_fetch_assoc($result_berita)) : ?>

        <article class="photo-card">

          <div class="photo-wrapper">
            <img
              src="../../uploads/<?= htmlspecialchars($foto['gambar']); ?>"
              alt="<?= htmlspecialchars($foto['judul']); ?>"
              loading="lazy"
            />
          </div>

          <div class="photo-content">

            <span class="photo-category">
              <?= htmlspecialchars($foto['nama_kategori'] ?? 'Berita'); ?>
            </span>

            <h3 class="photo-title">
              <?= htmlspecialchars($foto['judul']); ?>
            </h3>

            <div class="photo-meta">
              <i class="fa-regular fa-calendar-alt"></i>
              <span><?= htmlspecialchars($foto['tanggal']); ?></span>
            </div>

          </div>

        </article>

      <?php endwhile; ?>

    </div>

  <?php else : ?>

    <div class="empty-state">
      <i class="fa-regular fa-images empty-icon"></i>
      <p>Belum ada foto berita atau prestasi.</p>
    </div>

  <?php endif; ?>

</section>


<!-- ========================= -->
<!-- GALERI KEGIATAN MAHASISWA -->
<!-- ========================= -->

<section class="photo-section">

  <div class="section-heading">
    <h2>Kegiatan Mahasiswa</h2>
    <p>Foto kegiatan Mahatar AMNI yang dikelompokkan berdasarkan jenis kegiatan.</p>
  </div>

  <?php
  // Kelompokkan foto berdasarkan nama kegiatan
  $kelompok_kegiatan = [];

  while ($foto = mysqli_fetch_assoc($result_kegiatan)) {

      $nama_kegiatan = !empty($foto['nama_kegiatan'])
          ? $foto['nama_kegiatan']
          : 'Kegiatan Lainnya';

      $kelompok_kegiatan[$nama_kegiatan][] = $foto;
  }
  ?>

  <?php if (!empty($kelompok_kegiatan)) : ?>

    <?php foreach ($kelompok_kegiatan as $nama_kegiatan => $foto_kegiatan) : ?>

      <div class="activity-group">

        <div class="activity-group-header">
          <h3><?= htmlspecialchars($nama_kegiatan); ?></h3>
          <span><?= count($foto_kegiatan); ?> foto</span>
        </div>

        <div class="photo-grid">

          <?php foreach ($foto_kegiatan as $foto) : ?>

            <article class="photo-card">

              <div class="photo-wrapper">
                <img
                  src="../../uploads/<?= htmlspecialchars($foto['gambar']); ?>"
                  alt="<?= htmlspecialchars($foto['judul']); ?>"
                  loading="lazy"
                />
              </div>

              <div class="photo-content">

                <h3 class="photo-title">
                  <?= htmlspecialchars($foto['judul']); ?>
                </h3>

                <div class="photo-meta">
                  <i class="fa-regular fa-calendar-alt"></i>
                  <span><?= htmlspecialchars($foto['tanggal']); ?></span>
                </div>

              </div>

            </article>

          <?php endforeach; ?>

        </div>

      </div>

    <?php endforeach; ?>

  <?php else : ?>

    <div class="empty-state">
      <i class="fa-regular fa-images empty-icon"></i>
      <p>Belum ada foto kegiatan mahasiswa.</p>
    </div>

  <?php endif; ?>

</section>
  </main>

  <script>
    // Theme Switcher Logic
    const themeToggleBtn = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');

    themeToggleBtn.addEventListener('click', () => {
      const currentTheme = document.documentElement.getAttribute('data-theme');
      if (currentTheme === 'light') {
        document.documentElement.setAttribute('data-theme', 'dark');
        localStorage.setItem('theme', 'dark');
        themeIcon.className = 'fa-solid fa-moon';
      } else {
        document.documentElement.setAttribute('data-theme', 'light');
        localStorage.setItem('theme', 'light');
        themeIcon.className = 'fa-solid fa-sun';
      }
    });

    // Load Saved Theme
    if (localStorage.getItem('theme') === 'light') {
      document.documentElement.setAttribute('data-theme', 'light');
      themeIcon.className = 'fa-solid fa-sun';
    }
  </script>
  </body>
</html>