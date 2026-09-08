<?php

session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: ../../login.php");
    exit;
}

include "../../config/koneksi.php";

$query = " SELECT berita.*, kategori_berita.nama_kategori
    FROM berita
    JOIN kategori_berita
    ON berita.id_kategori = kategori_berita.id_kategori
    ORDER BY berita.tanggal ASC 
";


$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kelola Berita - Mahatar AMNI</title>

  <!-- Google Fonts & FontAwesome Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    :root {
  /* Background Utama: Biru Laut Dalam */
  --bg-dark: #071325;
  --bg-card: rgba(13, 30, 56, 0.75);

  /* Tipografi */
  --text-primary: #f0f6ff;
  --text-secondary: #94a3b8;

  /* Aksen Warna */
  --accent-blue: #0284c7;
  --accent-cyan: #38bdf8;
  --accent-gold: #f59e0b;

  /* Gradasi Tombol */
  --gradient-accent: linear-gradient(135deg, #0284c7 0%, #38bdf8 100%);

  /* Background Mesh Dynamic */
  --custom-mesh-bg: 
    radial-gradient(circle at 50% 35%, rgba(56, 189, 248, 0.25) 0%, transparent 50%),
    radial-gradient(circle at 80% 80%, rgba(129, 140, 248, 0.15) 0%, transparent 40%),
    radial-gradient(circle at 20% 20%, rgba(34, 211, 238, 0.15) 0%, transparent 40%),
    linear-gradient(180deg, #090d16 0%, #0f172a 100%);

  /* UI Tokens */
  --border-glass: 1px solid rgba(255, 255, 255, 0.08);
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

/* Nav & Header */
.top-nav {
  margin-bottom: 24px;
}

.header-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 28px;
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
   TABLE STYLING
   ========================================== */
.table-responsive {
  width: 100%;
  overflow-x: auto;
  border-radius: var(--radius-md);
  border: var(--border-glass);
}

.custom-table {
  width: 100%;
  border-collapse: collapse;
  text-align: left;
  font-size: 0.92rem;
}

.custom-table th {
  background: rgba(0, 0, 0, 0.2);
  color: var(--text-primary);
  font-weight: 700;
  padding: 16px 20px;
  border-bottom: var(--border-glass);
  white-space: nowrap;
}

[data-theme="light"] .custom-table th {
  background: rgba(0, 0, 0, 0.05);
}

.custom-table td {
  padding: 16px 20px;
  border-bottom: var(--border-glass);
  color: var(--text-primary);
  vertical-align: middle;
}

.custom-table tbody tr {
  transition: var(--transition-smooth);
}

.custom-table tbody tr:hover {
  background: rgba(255, 255, 255, 0.03);
}

[data-theme="light"] .custom-table tbody tr:hover {
  background: rgba(0, 0, 0, 0.03);
}

/* Cells formatting */
.row-no {
  font-weight: 600;
  color: var(--text-secondary);
}

.news-title-cell {
  font-weight: 600;
  line-height: 1.4;
}

.category-badge {
  display: inline-block;
  padding: 4px 12px;
  background: rgba(2, 132, 199, 0.2);
  border: 1px solid var(--accent-cyan);
  color: var(--accent-cyan);
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
  white-space: nowrap;
}

[data-theme="light"] .category-badge {
  background: rgba(30, 64, 175, 0.1);
  color: var(--accent-blue);
  border-color: var(--accent-blue);
}

/* Excerpt Truncation (Maksimal 2 baris) */
.news-excerpt-cell p {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  color: var(--text-secondary);
  line-height: 1.5;
  margin: 0;
}

/* Empty State */
.empty-state {
  text-align: center;
  padding: 48px 20px !important;
  color: var(--text-secondary);
}

.empty-icon {
  font-size: 2.5rem;
  margin-bottom: 12px;
  display: block;
  opacity: 0.7;
}

/* ==========================================
   BUTTONS & ACTIONS
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

/* Action Buttons Grid */
.action-btns {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

.btn-action {
  width: 34px;
  height: 34px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  font-size: 0.85rem;
  transition: var(--transition-smooth);
}

.btn-edit {
  background: rgba(2, 132, 199, 0.15);
  color: var(--accent-cyan);
  border: 1px solid rgba(56, 189, 248, 0.3);
}

.btn-edit:hover {
  background: var(--accent-blue);
  color: #ffffff;
  transform: translateY(-2px);
}

.btn-delete {
  background: rgba(239, 68, 68, 0.15);
  color: #f87171;
  border: 1px solid rgba(239, 68, 68, 0.3);
}

.btn-delete:hover {
  background: #ef4444;
  color: #ffffff;
  transform: translateY(-2px);
}

/* Floating Theme Toggle Button */
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
  </style>
</head>

  <body>

  <button id="themeToggle" class="btn-theme-toggle" type="button" aria-label="Toggle Theme">
    <i id="themeIcon" class="fa-solid fa-moon"></i>
  </button>

    <main class="dashboard-card">
      <!-- Top Nav & Header -->
      <div style="margin-bottom: 24px">
        <a href="../dashboard.php" class="btn-back">
          <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
        </a>
      </div>

      <div class="header-actions">
        <div>
          <h1
            style="
              font-size: 2rem;
              font-weight: 800;
              color: var(--text-primary);
            "
          >
            Kelola Berita
          </h1>
          <p style="color: var(--text-secondary); font-size: 0.9rem">
            Selamat datang di halaman pengelolaan berita Mahatar AMNI.
          </p>
        </div>
        <a href="tambah.php" class="btn-add">
          <i class="fa-solid fa-plus"></i> Tambah Berita
        </a>
      </div>

      <!-- Table Berita -->
      <div class="table-responsive">
        <table class="custom-table">
          <thead>
            <tr>
              <th style="width: 50px">No</th>
              <th>Judul Berita</th>
              <th>Kategori</th>
              <th>Isi Berita</th>
              <th style="width: 100px; text-align: center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1; if (mysqli_num_rows($result) > 0) : while ($berita =
            mysqli_fetch_assoc($result)) : ?>
            <tr>
              <td><?= $no++; ?></td>
              <td class="news-title-cell">
                <?= htmlspecialchars($berita['judul']); ?>
              </td>
              <td>
              <?= htmlspecialchars($berita['nama_kategori']); ?>
              </td>
              <td class="news-excerpt-cell">
                <?= htmlspecialchars(strip_tags($berita['isi'])); ?>
              </td>
              <td>
                  <div class="action-btns" style="justify-content: center">

                      <!-- EDIT -->
                      <a
                          href="edit.php?id=<?= $berita['id_berita']; ?>"
                          class="btn-action btn-edit"
                          title="Edit"
                      >
                          <i class="fa-solid fa-pen"></i>
                      </a>

                      <!-- HAPUS -->
                      <a
                          href="hapus.php?id=<?= $berita['id_berita']; ?>"
                          class="btn-action btn-delete"
                          title="Hapus"
                          onclick="return confirm('Apakah Anda yakin ingin menghapus berita ini?');"
                      >
                          <i class="fa-solid fa-trash"></i>
                      </a>

                  </div>
              </td>
            </tr>
            <?php endwhile; else : ?>
            <tr>
              <td colspan="5" class="empty-state">
                <i
                  class="fa-regular fa-folder-open"
                  style="font-size: 2rem; margin-bottom: 8px; display: block"
                ></i>
                Belum ada berita yang ditambahkan.
              </td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
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

