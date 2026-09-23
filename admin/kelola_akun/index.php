<?php
require '../middleware/cek_akses.php';
cekRole(['Super_Admin']); 

require '../../config/koneksi.php'; 

$result = mysqli_query($conn, "SELECT users.*, kategori_kegiatan.nama_kegiatan 
    FROM users 
    LEFT JOIN kategori_kegiatan 
        ON users.id_kategori_kegiatan = kategori_kegiatan.id_kategori_kegiatan
    ORDER BY users.role, users.nama
");

$total_data = mysqli_num_rows($result);
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Akun Admin</title>
  <link rel="stylesheet" href="style.css">
  <!-- Font Inter & Remixicon untuk Ikon Mode & Folder -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">>
  <style>
    /* ==========================================================================
   VARIABEL & SETUP TEMA
   ========================================================================== */
:root {
  /* Mode Light (Bawaan Tampilan Referensi Gambar) */
  --bg-main: var(--custom-mesh-bg);
  --card-bg: rgba(235, 242, 250, 0.85);
  --card-border: rgba(255, 255, 255, 0.6);
  --table-head-bg: #dce4ed;
  --table-border: #cbd5e1;
  
  --text-title: #0f172a;
  --text-muted: #64748b;
  --text-body: #334155;

  --btn-primary-bg: #0052cc;
  --btn-primary-hover: #0043a8;
  --btn-primary-text: #ffffff;

  --badge-bg: #e2e8f0;
  --badge-text: #1e293b;

  --toggle-bg: #ffffff;
  --toggle-color: #0f172a;

  --shadow-card: 0 20px 40px rgba(0, 0, 0, 0.06);

  --custom-mesh-bg:
    radial-gradient(circle at 50% 35%, rgba(9, 148, 207, 0.25) 0%, transparent 50%),
    radial-gradient(circle at 80% 80%, rgba(245, 159, 11, 0.15) 0%, transparent 40%),
    radial-gradient(circle at 20% 20%, rgba(14, 164, 233, 0.2) 0%, transparent 40%),
    linear-gradient(180deg, #eef4fb 0%, #e2e8f0 100%);
}

[data-theme="dark"] {
  /* Mode Dark */
  --card-bg: rgba(13, 30, 56, 0.85);
  --card-border: rgba(255, 255, 255, 0.08);
  --table-head-bg: rgba(255, 255, 255, 0.05);
  --table-border: rgba(255, 255, 255, 0.08);

  --text-title: #f0f6ff;
  --text-muted: #94a3b8;
  --text-body: #cbd5e1;

  --btn-primary-bg: #0284c7;
  --btn-primary-hover: #0369a1;
  --btn-primary-text: #ffffff;

  --badge-bg: rgba(56, 189, 248, 0.15);
  --badge-text: #38bdf8;

  --toggle-bg: #1e293b;
  --toggle-color: #f8fafc;

  --shadow-card: 0 20px 40px rgba(0, 0, 0, 0.3);

  --custom-mesh-bg:
    radial-gradient(circle at 50% 35%, rgba(56, 189, 248, 0.25) 0%, transparent 50%),
    radial-gradient(circle at 80% 80%, rgba(129, 140, 248, 0.15) 0%, transparent 40%),
    radial-gradient(circle at 20% 20%, rgba(34, 211, 238, 0.15) 0%, transparent 40%),
    linear-gradient(180deg, #090d16 0%, #0f172a 100%);
}

/* ==========================================================================
   RESET & LAYOUT DASAR
   ========================================================================== */
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
  transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
}

body {
  font-family: 'Inter', sans-serif;
  background: var(--custom-mesh-bg);
  background-attachment: fixed;
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 2rem 1rem;
  color: var(--text-body);
}

/* --- Theme Toggle Button --- */
.btn-theme-toggle {
  position: fixed;
  top: 20px;
  right: 20px;
  width: 44px;
  height: 44px;
  border-radius: 50%;
  background: var(--bg-card);
  backdrop-filter: var(--glass-backdrop);
  -webkit-backdrop-filter: var(--glass-backdrop);
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

/* Kartu Utama Container */
.main-card {
  width: 100%;
  max-width: 960px;
  background: var(--card-bg);
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  border: 1px solid var(--card-border);
  border-radius: 20px;
  padding: 2.5rem;
  box-shadow: var(--shadow-card);
}

.card-header {
  margin-bottom: 1.25rem;
}

.btn-back {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  color: var(--text-muted);
  text-decoration: none;
  font-size: 0.875rem;
  font-weight: 600;
  transition: color 0.2s ease, transform 0.2s ease;
}

.btn-back:hover {
  color: var(--btn-primary-bg);
  transform: translateX(-3px);
}

/* Header Section */
.header-section {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 1rem;
  margin-bottom: 2rem;
}

.title-container h1 {
  font-size: 1.75rem;
  font-weight: 700;
  color: var(--text-title);
  letter-spacing: -0.02em;
}

.title-container .subtitle {
  font-size: 0.875rem;
  color: var(--text-muted);
  margin-top: 0.25rem;
}

/* Tombol Tambah */
.btn-add {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  background-color: var(--btn-primary-bg);
  color: var(--btn-primary-text);
  padding: 0.625rem 1.25rem;
  border-radius: 12px;
  text-decoration: none;
  font-size: 0.875rem;
  font-weight: 600;
  box-shadow: 0 4px 12px rgba(0, 82, 204, 0.2);
  white-space: nowrap;
}

.btn-add:hover {
  background-color: var(--btn-primary-hover);
}

/* ==========================================================================
   TABEL & EMPTY STATE (SAMA PERSIS DENGAN DESAIN GAMBAR)
   ========================================================================== */
.table-container {
  width: 100%;
  border-radius: 12px;
  overflow: hidden;
  border: 1px solid var(--table-border);
}

/* Tampilan Kosong jika tidak ada data */
.empty-state {
  background: rgba(255, 255, 255, 0.02);
  padding: 4rem 2rem;
  text-align: center;
  color: var(--text-muted);
}

.empty-icon {
  font-size: 3rem;
  display: block;
  margin-bottom: 0.5rem;
  opacity: 0.7;
}

.empty-state p {
  font-size: 0.9rem;
}

/* Data Table Style */
.data-table {
  width: 100%;
  border-collapse: collapse;
  text-align: left;
}

.data-table th {
  background-color: var(--table-head-bg);
  color: var(--text-title);
  font-size: 0.85rem;
  font-weight: 600;
  padding: 1rem;
  border-bottom: 1px solid var(--table-border);
}

.data-table td {
  padding: 1rem;
  font-size: 0.875rem;
  color: var(--text-body);
  border-bottom: 1px solid var(--table-border);
}

.data-table tbody tr:last-child td {
  border-bottom: none;
}

.badge {
  display: inline-block;
  padding: 0.25rem 0.625rem;
  border-radius: 6px;
  font-size: 0.75rem;
  font-weight: 600;
  background-color: var(--badge-bg);
  color: var(--badge-text);
}

.btn-delete {
  color: #ef4444;
  text-decoration: none;
  font-size: 0.85rem;
  font-weight: 500;
}

.btn-delete:hover {
  text-decoration: underline;
}

/* Responsive Handling */
@media (max-width: 640px) {
  .main-card {
    padding: 1.5rem;
  }

  .header-section {
    flex-direction: column;
    align-items: flex-start;
  }

  .btn-add {
    width: 100%;
    justify-content: center;
  }

  .table-container {
    overflow-x: auto;
  }
}
  </style>
</head>
<body>

  <button
    id="themeToggle"
    class="btn-theme-toggle"
    type="button"
    aria-label="Toggle Theme"
  >
    <i id="themeIcon" class="fa-solid fa-moon"></i>
  </button>

  <!-- Kartu Utama Layout -->
  <div class="main-card">
    
   <div class="card-header">
        <a href="../dashboard.php" class="btn-back">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
          Kembali ke Daftar Akun
        </a>
      </div>

    <!-- Header Judul & Tombol Aksi -->
    <div class="header-section">
      <div class="title-container">
        <h1>Kelola Akun Admin</h1>
        <p class="subtitle">Selamat datang di halaman pengelolaan akun admin Mahatar AMNI.</p>
      </div> 
      <a href="tambah.php" class="btn-add">
          <i class="fa-solid fa-plus"></i> Tambah Akun Baru
        </a>
    </div>

    <!-- Container Tabel / State Kosong -->
    <div class="table-container">
      <?php if ($total_data > 0): ?>
        <table class="data-table">
          <thead>
            <tr>
              <th style="width: 50px;">No</th>
              <th>Nama</th>
              <th>Username</th>
              <th>Role</th>
              <th>Kegiatan</th>
              <th style="text-align: center; width: 100px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $no = 1;
            while ($user = mysqli_fetch_assoc($result)): 
            ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><strong><?= htmlspecialchars($user['nama']) ?></strong></td>
              <td><?= htmlspecialchars($user['username']) ?></td>
              <td><span class="badge badge-role"><?= htmlspecialchars($user['role']) ?></span></td>
              <td><?= htmlspecialchars($user['nama_kegiatan'] ?? '-') ?></td>
              <td style="text-align: center;">
                <a href="hapus.php?id=<?= $user['id_user'] ?>" 
                   class="btn-delete"
                   onclick="return confirm('Yakin mau hapus akun <?= htmlspecialchars($user['nama']) ?>?');">
                  <i class="ri-delete-bin-line"></i> Hapus
                </a>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      <?php else: ?>
        <!-- Tampilan Kosong Sesuai Referensi Gambar -->
        <div class="empty-state">
          <i class="ri-folder-open-line empty-icon"></i>
          <p>Belum ada akun admin yang ditambahkan.</p>
        </div>
      <?php endif; ?>
    </div>

  </div>

  <!-- Script Penanganan Dark / Light Mode -->
  <script>
   // Theme Switcher Logic
      const themeToggleBtn = document.getElementById("themeToggle");
      const themeIcon = document.getElementById("themeIcon");

      themeToggleBtn.addEventListener("click", () => {
        const currentTheme =
          document.documentElement.getAttribute("data-theme");
        if (currentTheme === "light") {
          document.documentElement.setAttribute("data-theme", "dark");
          localStorage.setItem("theme", "dark");
          themeIcon.className = "fa-solid fa-moon";
        } else {
          document.documentElement.setAttribute("data-theme", "light");
          localStorage.setItem("theme", "light");
          themeIcon.className = "fa-solid fa-sun";
        }
      });

      // Load Saved Theme
      if (localStorage.getItem("theme") === "light") {
        document.documentElement.setAttribute("data-theme", "light");
        themeIcon.className = "fa-solid fa-sun";
      }
  </script>
</body>
</html>