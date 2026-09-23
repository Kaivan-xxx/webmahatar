<?php
require '../middleware/cek_akses.php';
cekRole(['Super_Admin']);

require '../../config/koneksi.php';

$error = "";

// Ambil daftar kegiatan buat dropdown
$kegiatanResult = mysqli_query($conn, "SELECT * FROM kategori_kegiatan ORDER BY nama_kegiatan");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $id_kategori_kegiatan = ($role === 'Admin_Kegiatan') ? $_POST['id_kategori_kegiatan'] : null;

    $cekUsername = mysqli_prepare($conn, "SELECT id_user FROM users WHERE username = ?");
    mysqli_stmt_bind_param($cekUsername, "s", $username);
    mysqli_stmt_execute($cekUsername);
    $hasilCek = mysqli_stmt_get_result($cekUsername);

    if (mysqli_num_rows($hasilCek) > 0) {
        $error = "Username sudah dipakai, pilih yang lain.";
    } else {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = mysqli_prepare($conn, 
            "INSERT INTO users (nama, username, password, role, id_kategori_kegiatan) 
             VALUES (?, ?, ?, ?, ?)"
        );
        mysqli_stmt_bind_param($stmt, "ssssi", $nama, $username, $passwordHash, $role, $id_kategori_kegiatan);
        mysqli_stmt_execute($stmt);

        header("Location: index.php");
        exit;
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Akun Admin — Webmahatar</title>
  
  <!-- Font Google & FontAwesome CDN -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* --- Variable Theme Definition --- */
:root {
  /* Mode Light */
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

  --shadow-card: 0 20px 40px rgba(0, 0, 0, 0.06);

  --custom-mesh-bg:
    radial-gradient(circle at 50% 35%, rgba(9, 148, 207, 0.25) 0%, transparent 50%),
    radial-gradient(circle at 80% 80%, rgba(245, 159, 11, 0.15) 0%, transparent 40%),
    radial-gradient(circle at 20% 20%, rgba(14, 164, 233, 0.2) 0%, transparent 40%),
    linear-gradient(180deg, #eef4fb 0%, #e2e8f0 100%);

  --input-bg: rgba(255, 255, 255, 0.7);
  --input-border: #cbd5e1;
  --input-focus: #0052cc;
  --danger-bg: rgba(239, 68, 68, 0.1);
  --danger-text: #dc2626;
  --danger-border: rgba(239, 68, 68, 0.2);

  /* Variabel Tambahan untuk Button Toggle */
  --bg-card: rgba(235, 242, 250, 0.85);
  --glass-backdrop: blur(16px);
  --border-glass: 1px solid rgba(255, 255, 255, 0.6);
  --text-primary: #0f172a;
  --accent-cyan: #0994cf;
  --transition-smooth: all 0.3s ease;
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

  --shadow-card: 0 20px 40px rgba(0, 0, 0, 0.3);

  --custom-mesh-bg:
    radial-gradient(circle at 50% 35%, rgba(56, 189, 248, 0.25) 0%, transparent 50%),
    radial-gradient(circle at 80% 80%, rgba(129, 140, 248, 0.15) 0%, transparent 40%),
    radial-gradient(circle at 20% 20%, rgba(34, 211, 238, 0.15) 0%, transparent 40%),
    linear-gradient(180deg, #090d16 0%, #0f172a 100%);

  --input-bg: rgba(15, 23, 42, 0.5);
  --input-border: rgba(255, 255, 255, 0.12);
  --input-focus: #38bdf8;
  --danger-bg: rgba(239, 68, 68, 0.2);
  --danger-text: #fca5a5;
  --danger-border: rgba(239, 68, 68, 0.3);

  /* Variabel Tambahan untuk Button Toggle (Mode Dark) */
  --bg-card: rgba(13, 30, 56, 0.85);
  --border-glass: 1px solid rgba(255, 255, 255, 0.08);
  --text-primary: #f0f6ff;
  --accent-cyan: #38bdf8;
}

/* --- Base & Reset Styles --- */
*, *::before, *::after {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

body {
  font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
  background: var(--bg-main);
  background-attachment: fixed;
  color: var(--text-body);
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem 1rem;
  transition: background 0.3s ease, color 0.3s ease;
  position: relative;
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

/* --- Layout Containers --- */
.page-container {
  width: 100%;
  max-width: 520px;
}

.card {
  background: var(--card-bg);
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  border: 1px solid var(--card-border);
  border-radius: 20px;
  padding: 2.25rem;
  box-shadow: var(--shadow-card);
  transition: background 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
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

/* --- Typography --- */
.card-title-group {
  margin-bottom: 1.75rem;
}

.card-title-group h1 {
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--text-title);
  letter-spacing: -0.02em;
  margin-bottom: 0.35rem;
}

.card-title-group .subtitle {
  font-size: 0.875rem;
  color: var(--text-muted);
}

/* --- Alerts --- */
.alert-error {
  display: flex;
  align-items: center;
  gap: 0.625rem;
  background: var(--danger-bg);
  color: var(--danger-text);
  border: 1px solid var(--danger-border);
  padding: 0.75rem 1rem;
  border-radius: 10px;
  font-size: 0.875rem;
  font-weight: 500;
  margin-bottom: 1.5rem;
}

/* --- Form Fields --- */
.admin-form {
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.form-group label {
  font-size: 0.875rem;
  font-weight: 600;
  color: var(--text-title);
}

.form-group input,
.form-group select {
  width: 100%;
  padding: 0.75rem 1rem;
  font-size: 0.925rem;
  font-family: inherit;
  color: var(--text-title);
  background: var(--input-bg);
  border: 1px solid var(--input-border);
  border-radius: 10px;
  outline: none;
  transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.3s ease;
}

.form-group input::placeholder {
  color: var(--text-muted);
  opacity: 0.7;
}

.form-group input:focus,
.form-group select:focus {
  border-color: var(--input-focus);
  box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
}

.form-group select {
  appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 1rem center;
  padding-right: 2.5rem;
}

.form-group select option {
  background-color: var(--card-bg);
  color: var(--text-title);
}

/* --- Primary Button --- */
.btn-submit {
  width: 100%;
  padding: 0.85rem;
  margin-top: 0.5rem;
  font-size: 0.95rem;
  font-weight: 600;
  font-family: inherit;
  color: var(--btn-primary-text);
  background: var(--btn-primary-bg);
  border: none;
  border-radius: 10px;
  cursor: pointer;
  box-shadow: 0 4px 12px rgba(0, 82, 204, 0.25);
  transition: background 0.2s ease, transform 0.1s ease, box-shadow 0.2s ease;
}

.btn-submit:hover {
  background: var(--btn-primary-hover);
  box-shadow: 0 6px 16px rgba(0, 82, 204, 0.35);
}

.btn-submit:active {
  transform: scale(0.98);
}

/* --- Responsiveness --- */
@media (max-width: 480px) {
  .btn-theme-toggle {
    top: 12px;
    right: 12px;
    width: 40px;
    height: 40px;
  }

  .card {
    padding: 1.5rem;
    border-radius: 16px;
  }
}
  </style>
</head>
<body>

  <!-- Theme Switcher Button -->
  <button
    id="themeToggle"
    class="btn-theme-toggle"
    type="button"
    aria-label="Toggle Theme"
  >
    <i id="themeIcon" class="fa-solid fa-moon"></i>
  </button>

  <main class="page-container">
    <div class="card">
      
      <!-- Top Navigation -->
      <div class="card-header">
        <a href="index.php" class="btn-back">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
          Kembali ke Daftar Akun
        </a>
      </div>

      <!-- Title -->
      <div class="card-title-group">
        <h1>Tambah Akun Admin</h1>
        <p class="subtitle">Buat kredensial dan hak akses admin baru</p>
      </div>

      <!-- Error Alert -->
      <?php if (!empty($error)): ?>
        <div class="alert alert-error">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
          <span><?= htmlspecialchars($error) ?></span>
        </div>
      <?php endif; ?>

      <!-- Form -->
      <form method="POST" class="admin-form">
        <div class="form-group">
          <label for="nama">Nama Lengkap</label>
          <input type="text" id="nama" name="nama" placeholder="Masukkan nama lengkap..." required autocomplete="off">
        </div>

        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" placeholder="Masukkan username..." required autocomplete="off">
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="••••••••" required>
        </div>

        <div class="form-group">
          <label for="roleSelect">Role Admin</label>
          <select name="role" id="roleSelect" required>
            <option value="" disabled selected>-- Pilih Role --</option>
            <option value="Admin">Support Admin</option>
            <option value="Admin_Kegiatan">Admin Kegiatan</option>
          </select>
        </div>

        <div class="form-group" id="kegiatanWrapper" style="display: none;">
          <label for="id_kategori_kegiatan">Kegiatan yang Dipegang</label>
          <select name="id_kategori_kegiatan" id="id_kategori_kegiatan">
            <?php while ($k = mysqli_fetch_assoc($kegiatanResult)): ?>
              <option value="<?= $k['id_kategori_kegiatan'] ?>">
                <?= htmlspecialchars($k['nama_kegiatan']) ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>

        <button type="submit" class="btn-submit">Simpan Akun</button>
      </form>
    </div>
  </main>

  <script>
    // Toggle dropdown Kegiatan
    const roleSelect = document.getElementById('roleSelect');
    const kegiatanWrapper = document.getElementById('kegiatanWrapper');

    roleSelect.addEventListener('change', () => {
      kegiatanWrapper.style.display = (roleSelect.value === 'Admin_Kegiatan') ? 'block' : 'none';
    });

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