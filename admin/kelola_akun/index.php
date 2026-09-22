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
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
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

/* Tombol Mode (Gigi Roda/Bulan/Matahari di Kanan Atas) */
.theme-toggle-btn {
  position: fixed;
  top: 1.5rem;
  right: 1.5rem;
  width: 42px;
  height: 42px;
  border-radius: 50%;
  border: 1px solid var(--card-border);
  background: var(--toggle-bg);
  color: var(--toggle-color);
  font-size: 1.2rem;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  z-index: 100;
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

/* Kembali ke Dashboard */
.back-link {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  color: var(--text-muted);
  text-decoration: none;
  font-size: 0.875rem;
  font-weight: 500;
  margin-bottom: 1.25rem;
}

.back-link:hover {
  color: var(--text-title);
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

  <!-- Tombol Toggle Dark / Light Mode (Pojok Kanan Atas) -->
  <button id="theme-toggle" class="theme-toggle-btn" title="Ganti Tema">
    <i id="theme-icon" class="ri-moon-line"></i>
  </button>

  <!-- Kartu Utama Layout -->
  <div class="main-card">
    
    <!-- Header Navigasi -->
    <a href="../dashboard.php" class="back-link">
      <i class="ri-arrow-left-line"></i> Kembali ke Dashboard
    </a>

    <!-- Header Judul & Tombol Aksi -->
    <div class="header-section">
      <div class="title-container">
        <h1>Kelola Akun Admin</h1>
        <p class="subtitle">Selamat datang di halaman pengelolaan akun admin Mahatar AMNI.</p>
      </div>
      <a href="tambah.php" class="btn-add">
        <i class="ri-add-line"></i> Tambah Akun Baru
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
    const themeToggleBtn = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-icon');
    
    // Cek preferensi tema sebelumnya
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);
    updateIcon(savedTheme);

    themeToggleBtn.addEventListener('click', () => {
      const currentTheme = document.documentElement.getAttribute('data-theme');
      const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
      
      document.documentElement.setAttribute('data-theme', newTheme);
      localStorage.setItem('theme', newTheme);
      updateIcon(newTheme);
    });

    function updateIcon(theme) {
      if (theme === 'dark') {
        themeIcon.className = 'ri-sun-line';
      } else {
        themeIcon.className = 'ri-moon-line';
      }
    }
  </script>
</body>
</html>