<?php

session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: ../../login.php");
    exit;
}

include "../../config/koneksi.php";


// =========================
// AMBIL ID BERITA
// =========================

$id_berita = $_GET['id'] ?? null;

if (!$id_berita) {
    die("ID berita tidak ditemukan.");
}


// =========================
// AMBIL DATA BERITA
// =========================

$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM berita WHERE id_berita = ?"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id_berita
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$berita = mysqli_fetch_assoc($result);


if (!$berita) {
    die("Berita tidak ditemukan.");
}


// =========================
// AMBIL DATA KATEGORI
// =========================

$query_kategori = "
    SELECT *
    FROM kategori_berita
    ORDER BY nama_kategori ASC
";

$result_kategori = mysqli_query(
    $conn,
    $query_kategori
);


// =========================
// PROSES UPDATE
// =========================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $id_kategori = $_POST['id_kategori'];


    // =========================
    // CEK APAKAH ADA GAMBAR BARU
    // =========================

    if (
        isset($_FILES['gambar']) &&
        $_FILES['gambar']['error'] === UPLOAD_ERR_OK
    ) {

        $nama_asli = $_FILES['gambar']['name'];
        $tmp_gambar = $_FILES['gambar']['tmp_name'];

        $ekstensi = strtolower(
            pathinfo($nama_asli, PATHINFO_EXTENSION)
        );

        $nama_gambar = uniqid() . "." . $ekstensi;

        // Lokasi gambar baru
        $tujuan = __DIR__ . "/../../uploads/" . $nama_gambar;


        // =========================
        // UPLOAD GAMBAR BARU
        // =========================

        if (!move_uploaded_file($tmp_gambar, $tujuan)) {

            die("Gambar baru gagal disimpan.");

        }


        // =========================
        // HAPUS GAMBAR LAMA
        // =========================

        if (!empty($berita['gambar'])) {

            $gambar_lama = __DIR__ . "/../../uploads/" . $berita['gambar'];

            if (file_exists($gambar_lama)) {
                unlink($gambar_lama);
            }

        }


        // =========================
        // UPDATE DATA + GAMBAR
        // =========================

        $stmt_update = mysqli_prepare(
            $conn,
            "UPDATE berita
             SET judul = ?,
                 isi = ?,
                 id_kategori = ?,
                 gambar = ?
             WHERE id_berita = ?"
        );

        mysqli_stmt_bind_param(
            $stmt_update,
            "ssisi",
            $judul,
            $isi,
            $id_kategori,
            $nama_gambar,
            $id_berita
        );


    } else {

        // =========================
        // UPDATE TANPA GANTI GAMBAR
        // =========================

        $stmt_update = mysqli_prepare(
            $conn,
            "UPDATE berita
             SET judul = ?,
                 isi = ?,
                 id_kategori = ?
             WHERE id_berita = ?"
        );

        mysqli_stmt_bind_param(
            $stmt_update,
            "ssii",
            $judul,
            $isi,
            $id_kategori,
            $id_berita
        );

    }


    // =========================
    // JALANKAN UPDATE
    // =========================

    if (mysqli_stmt_execute($stmt_update)) {

        header("Location: index.php");
        exit;

    } else {

        die(
            "Gagal update database: "
            . mysqli_stmt_error($stmt_update)
        );

    }

}


?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Berita - UNIMAR AMNI</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* ==========================================
   GLOBAL THEME VARIABLES
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

  --bg-radial-1: rgba(2, 132, 199, 0.25);
  --bg-radial-2: rgba(245, 158, 11, 0.12);
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
  --text-secondary: #475569;

  --accent-blue: #1e40af;
  --accent-cyan: #0284c7;
  --accent-gold: #b45309;

  --gradient-accent: linear-gradient(135deg, #1e40af 0%, #0369a1 100%);

  --bg-radial-1: rgba(30, 64, 175, 0.05);
  --bg-radial-2: rgba(180, 83, 9, 0.03);
  --custom-mesh-bg: 
    radial-gradient(circle at 50% 35%, rgba(9, 148, 207, 0.57) 0%, transparent 50%),
    radial-gradient(circle at 80% 80%, rgba(245, 159, 11, 0.24) 0%, transparent 40%),
    radial-gradient(circle at 20% 20%, rgba(14, 164, 233, 0.3) 0%, transparent 40%),
    linear-gradient(180deg, #f0f4f9 0%, #e2e8f0 100%);
}

/* ==========================================
   LAYOUT & FORM STYLING
   ========================================== */
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
  font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
}

body {
  background: var(--custom-mesh-bg);
  background-attachment: fixed;
  color: var(--text-primary);
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 40px 20px;
  transition: var(--transition-smooth);
}

/* Dynamic Glassmorphism Card Container */
.form-container {
  background: var(--bg-card);
  backdrop-filter: var(--glass-backdrop);
  -webkit-backdrop-filter: var(--glass-backdrop);
  border: var(--border-glass);
  border-radius: var(--radius-lg);
  max-width: 680px;
  width: 100%;
  padding: 40px;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
  transition: var(--transition-smooth);
}

.form-header {
  margin-bottom: 28px;
  border-bottom: var(--border-glass);
  padding-bottom: 16px;
}

.form-header h2 {
  font-size: 1.6rem;
  color: var(--text-primary);
  margin-bottom: 6px;
}

.form-header p {
  color: var(--text-secondary);
  font-size: 0.92rem;
}

/* Form Groups */
.form-group {
  margin-bottom: 22px;
  display: flex;
  flex-direction: column;
}

.form-group label {
  font-size: 0.9rem;
  font-weight: 600;
  color: var(--text-primary);
  margin-bottom: 8px;
}

/* Inputs, Select, Textarea */
.form-group input[type="text"],
.form-group select,
.form-group textarea {
  width: 100%;
  background: rgba(0, 0, 0, 0.1);
  border: var(--border-glass);
  border-radius: var(--radius-sm);
  padding: 12px 16px;
  color: var(--text-primary);
  font-size: 0.95rem;
  outline: none;
  transition: var(--transition-smooth);
}

[data-theme="light"] .form-group input[type="text"],
[data-theme="light"] .form-group select,
[data-theme="light"] .form-group textarea {
  background: rgba(255, 255, 255, 0.6);
}

.form-group input[type="text"]:focus,
.form-group select:focus,
.form-group textarea:focus {
  border-color: var(--accent-cyan);
  box-shadow: 0 0 12px rgba(56, 189, 248, 0.25);
}

.form-group select option {
  background: var(--bg-dark);
  color: #ffffff;
}

[data-theme="light"] .form-group select option {
  background: #ffffff;
  color: #1e293b;
}

.form-group textarea {
  resize: vertical;
  line-height: 1.6;
}

/* Image Preview Section */
.image-preview-wrapper {
  background: rgba(0, 0, 0, 0.15);
  border: var(--border-glass);
  border-radius: var(--radius-md);
  padding: 12px;
  display: flex;
  justify-content: center;
  align-items: center;
}

.current-image {
  max-width: 100%;
  max-height: 220px;
  border-radius: var(--radius-sm);
  object-fit: cover;
}

.no-image {
  text-align: center;
  padding: 20px;
  color: var(--text-secondary);
}

.no-image i {
  font-size: 2rem;
  margin-bottom: 6px;
}

/* File Input */
.file-input {
  color: var(--text-secondary);
  font-size: 0.88rem;
}

/* Buttons Action */
.form-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 16px;
  margin-top: 32px;
}

.btn-submit {
  background: var(--gradient-accent);
  color: #ffffff;
  border: none;
  padding: 12px 24px;
  border-radius: var(--radius-sm);
  font-weight: 600;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: var(--transition-smooth);
}

.btn-submit:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(2, 132, 199, 0.3);
}

.btn-cancel {
  color: var(--text-secondary);
  text-decoration: none;
  font-size: 0.9rem;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: var(--transition-smooth);
}

.btn-cancel:hover {
  color: var(--accent-cyan);
}

/* Floating Theme Toggle */
.btn-theme-toggle {
  position: fixed;
  top: 20px;
  right: 20px;
  width: 42px;
  height: 42px;
  border-radius: 50%;
  background: var(--bg-card);
  border: var(--border-glass);
  color: var(--text-primary);
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  transition: var(--transition-smooth);
  z-index: 100;
}

.btn-theme-toggle:hover {
  transform: scale(1.1);
  color: var(--accent-cyan);
}

/* ==========================================
   CUSTOM FILE INPUT STYLING
   ========================================== */

/* Container Input File Utama */
.form-group input[type="file"] {
  width: 100%;
  background: rgba(0, 0, 0, 0.15);
  border: var(--border-glass);
  border-radius: var(--radius-sm);
  padding: 8px 12px;
  color: var(--text-secondary);
  font-size: 0.9rem;
  outline: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  transition: var(--transition-smooth);
}

[data-theme="light"] .form-group input[type="file"] {
  background: rgba(255, 255, 255, 0.6);
  border: 1px solid rgba(100, 116, 139, 0.25);
  color: var(--text-primary);
}

/* Kustomisasi Tombol "Choose File" / "Pilih File" */
.form-group input[type="file"]::file-selector-button {
  background: var(--accent-blue);
  color: #ffffff;
  border: none;
  padding: 8px 18px;
  border-radius: 8px; /* Sudut tombol lebih melengkung */
  font-weight: 500;
  font-size: 0.88rem;
  margin-right: 14px;
  cursor: pointer;
  transition: var(--transition-smooth);
}

/* Dukungan untuk Browser Webkit (Safari/Chrome Lama) */
.form-group input[type="file"]::-webkit-file-upload-button {
  background: var(--accent-blue);
  color: #ffffff;
  border: none;
  padding: 8px 18px;
  border-radius: 8px;
  font-weight: 500;
  font-size: 0.88rem;
  margin-right: 14px;
  cursor: pointer;
  transition: var(--transition-smooth);
}

/* Efek Hover pada Tombol Choose File */
.form-group input[type="file"]::file-selector-button:hover,
.form-group input[type="file"]::-webkit-file-upload-button:hover {
  background: var(--accent-cyan);
  transform: translateY(-1px);
}
  </style>
</head>
<body>

  <!-- Floating Theme Toggle -->
  <button id="theme-toggle" class="btn-theme-toggle" aria-label="Toggle Theme">
    <i id="theme-icon" class="fa-solid fa-moon"></i>
  </button>

  <main class="form-container">
    <div class="form-header">
      <h2>Edit Berita & Pengumuman</h2>
      <p>Perbarui informasi publikasi mahasiswa UNIMAR AMNI Semarang</p>
    </div>

    <form method="POST" enctype="multipart/form-data" class="edit-form">
      
      <!-- JUDUL -->
      <div class="form-group">
        <label for="judul">Judul Berita</label>
        <input 
          type="text" 
          id="judul" 
          name="judul" 
          value="<?= htmlspecialchars($berita['judul']); ?>" 
          placeholder="Masukkan judul berita..." 
          required
        >
      </div>

      <!-- KATEGORI -->
      <div class="form-group">
        <label for="id_kategori">Kategori Berita</label>
        <select id="id_kategori" name="id_kategori" required>
          <?php while ($kategori = mysqli_fetch_assoc($result_kategori)) { ?>
            <option 
              value="<?= $kategori['id_kategori']; ?>" 
              <?= ($kategori['id_kategori'] == $berita['id_kategori']) ? 'selected' : ''; ?>
            >
              <?= htmlspecialchars($kategori['nama_kategori']); ?>
            </option>
          <?php } ?>
        </select>
      </div>

      <!-- ISI BERITA -->
      <div class="form-group">
        <label for="isi">Isi Berita</label>
        <textarea 
          id="isi" 
          name="isi" 
          rows="8" 
          placeholder="Tuliskan detail berita di sini..." 
          required
        ><?= htmlspecialchars($berita['isi']); ?></textarea>
      </div>

      <!-- PREVIEW GAMBAR LAMA -->
      <div class="form-group">
        <label>Gambar Saat Ini</label>
        <div class="image-preview-wrapper">
          <?php if (!empty($berita['gambar'])) { ?>
            <img 
              src="../../uploads/<?= htmlspecialchars($berita['gambar']); ?>" 
              alt="Gambar Berita Saat Ini" 
              class="current-image"
            >
          <?php } else { ?>
            <div class="no-image">
              <i class="fa-solid fa-image"></i>
              <p>Tidak ada gambar terlampir.</p>
            </div>
          <?php } ?>
        </div>
      </div>

      <!-- UPLOAD GAMBAR BARU -->
      <div class="form-group">
        <label for="gambar">Ganti Gambar (Opsional)</label>
        <input 
          type="file" 
          id="gambar" 
          name="gambar" 
          accept="image/*"
          class="file-input"
        >
      </div>

      <!-- ACTION BUTTONS -->
      <div class="form-actions">
        <a href="index.php" class="btn-cancel">
          <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
        <button type="submit" class="btn-submit">
          <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
        </button>
      </div>

    </form>
  </main>

  <script>
    // Theme Toggle Handler
    const themeToggleBtn = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-icon');

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

    // Restore Saved Theme
    if (localStorage.getItem('theme') === 'light') {
      document.documentElement.setAttribute('data-theme', 'light');
      themeIcon.className = 'fa-solid fa-sun';
    }
  </script>
</body>
</html>
