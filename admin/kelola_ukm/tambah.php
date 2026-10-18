<?php

require '../middleware/cek_akses.php';
cekRole(['Super_Admin']);

include "../../config/koneksi.php";

$error = "";


// Ambil isi form. Kalau kosong, simpan NULL (bukan teks kosong)
function ambil($key) {
    $nilai = trim($_POST[$key] ?? '');
    return $nilai === '' ? null : $nilai;
}


// =========================
// PROSES FORM
// =========================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nama_kegiatan = trim($_POST['nama_kegiatan'] ?? '');
    $judul_halaman = ambil('judul_halaman');
    $subjudul      = ambil('subjudul');
    $deskripsi     = ambil('deskripsi');
    $pembina       = ambil('pembina');
    $jadwal_rutin  = ambil('jadwal_rutin');
    $lokasi        = ambil('lokasi');
    $nama_foto     = null;


    // ---- 1. Nama UKM wajib diisi & tidak boleh kembar ----

    if ($nama_kegiatan === '') {

        $error = "Nama UKM wajib diisi.";

    } else {

        $cek = mysqli_prepare(
            $conn,
            "SELECT id_kategori_kegiatan FROM kategori_kegiatan WHERE nama_kegiatan = ?"
        );
        mysqli_stmt_bind_param($cek, "s", $nama_kegiatan);
        mysqli_stmt_execute($cek);
        $hasil_cek = mysqli_stmt_get_result($cek);

        if (mysqli_num_rows($hasil_cek) > 0) {
            $error = "Nama UKM sudah ada, pakai nama lain.";
        }
    }


    // ---- 2. Foto (opsional) ----

    if (
        $error === "" &&
        isset($_FILES['foto']) &&
        $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE
    ) {

        if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {

            $error = "Foto gagal diupload.";

        } else {

            $ekstensi = strtolower(
                pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION)
            );

            // Cuma format gambar yang boleh
            if (!in_array($ekstensi, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {

                $error = "Format foto harus JPG, PNG, WEBP, atau GIF.";

            } else {

                $nama_foto = uniqid() . "." . $ekstensi;
                $tujuan = __DIR__ . "/../../uploads/" . $nama_foto;

                if (!move_uploaded_file($_FILES['foto']['tmp_name'], $tujuan)) {
                    $error = "Foto gagal disimpan.";
                    $nama_foto = null;
                }
            }
        }
    }


    // ---- 3. Simpan ke database ----

    if ($error === "") {

        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO kategori_kegiatan
            (nama_kegiatan, judul_halaman, subjudul, deskripsi, foto, pembina, jadwal_rutin, lokasi)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssssssss",
            $nama_kegiatan,
            $judul_halaman,
            $subjudul,
            $deskripsi,
            $nama_foto,
            $pembina,
            $jadwal_rutin,
            $lokasi
        );

        if (mysqli_stmt_execute($stmt)) {

            header("Location: index.php");
            exit;

        } else {

            $error = "Gagal menyimpan UKM: " . mysqli_stmt_error($stmt);

            // Foto sudah terlanjur tersimpan, hapus lagi biar tidak jadi sampah
            if ($nama_foto && file_exists(__DIR__ . "/../../uploads/" . $nama_foto)) {
                unlink(__DIR__ . "/../../uploads/" . $nama_foto);
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tambah UKM - Mahatar AMNI</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style> 
    /* ==========================================
   VARIABLES & THEMES
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
   RESET & BASE STYLING
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
  align-items: center;
  justify-content: center;
  padding: 40px 20px;
  transition: var(--transition-smooth);
}

/* ==========================================
   FORM CONTAINER
   ========================================== */
.form-container {
  background: var(--bg-card);
  backdrop-filter: var(--glass-backdrop);
  -webkit-backdrop-filter: var(--glass-backdrop);
  border: var(--border-glass);
  border-radius: var(--radius-lg);
  max-width: 680px;
  width: 100%;
  padding: 40px;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
  transition: var(--transition-smooth);
}

.form-header {
  margin-bottom: 28px;
  border-bottom: var(--border-glass);
  padding-bottom: 16px;
}

.form-title {
  font-size: 1.75rem;
  font-weight: 700;
  color: var(--text-primary);
  margin-bottom: 6px;
}

.form-subtitle {
  color: var(--text-secondary);
  font-size: 0.9rem;
}

/* ==========================================
   FORM ELEMENTS
   ========================================== */
.form-group {
  margin-bottom: 22px;
  display: flex;
  flex-direction: column;
}

.form-label {
  font-size: 0.9rem;
  font-weight: 600;
  color: var(--text-primary);
  margin-bottom: 8px;
}

/* Input Text, Select, & Textarea */
.form-input {
  width: 100%;
  background: rgba(0, 0, 0, 0.15);
  border: var(--border-glass);
  border-radius: var(--radius-sm);
  padding: 12px 16px;
  color: var(--text-primary);
  font-size: 0.95rem;
  outline: none;
  transition: var(--transition-smooth);
}

[data-theme="light"] .form-input {
  background: rgba(255, 255, 255, 0.6);
}

.form-input:focus {
  border: var(--border-active);
  box-shadow: 0 0 12px rgba(56, 189, 248, 0.25);
}

select.form-input option {
  background: var(--bg-dark);
  color: #ffffff;
}

[data-theme="light"] select.form-input option {
  background: #ffffff;
  color: #1e293b;
}

textarea.form-input {
  resize: vertical;
  line-height: 1.6;
}

/* ==========================================
   CUSTOM CHOOSE FILE INPUT
   ========================================== */
.file-input-wrapper {
  position: relative;
  width: 100%;
}

input[type="file"].file-input {
  padding: 8px 12px;
  cursor: pointer;
  color: var(--text-secondary);
}

/* Chrome, Edge, Safari */
input[type="file"].file-input::-webkit-file-upload-button {
  background: var(--accent-blue);
  color: #ffffff;
  border: none;
  padding: 8px 18px;
  border-radius: 8px;
  font-weight: 600;
  font-size: 0.88rem;
  margin-right: 14px;
  cursor: pointer;
  transition: var(--transition-smooth);
}

/* Firefox */
input[type="file"].file-input::file-selector-button {
  background: var(--accent-blue);
  color: #ffffff;
  border: none;
  padding: 8px 18px;
  border-radius: 8px;
  font-weight: 600;
  font-size: 0.88rem;
  margin-right: 14px;
  cursor: pointer;
  transition: var(--transition-smooth);
}

input[type="file"].file-input::-webkit-file-upload-button:hover,
input[type="file"].file-input::file-selector-button:hover {
  background: var(--accent-cyan);
  transform: translateY(-1px);
}

/* ==========================================
   BUTTONS & ACTIONS
   ========================================== */
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
  font-size: 0.95rem;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: var(--transition-smooth);
}

.btn-submit:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(2, 132, 199, 0.35);
}

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
  
.alert-error {
  background: rgba(239, 68, 68, 0.12);
  border: 1px solid rgba(239, 68, 68, 0.35);
  color: #f87171;
  padding: 12px 16px;
  border-radius: var(--radius-sm);
  font-size: 0.9rem;
  font-weight: 600;
  margin-bottom: 22px;
}

[data-theme="light"] .alert-error {
  color: #dc2626;
}

.form-hint {
  font-size: 0.8rem;
  color: var(--text-secondary);
  margin-top: 6px;
}
  </style>
</head>
<body>

  <button id="themeToggle" class="btn-theme-toggle" type="button" aria-label="Toggle Theme">
    <i id="themeIcon" class="fa-solid fa-moon"></i>
  </button>

  <main class="form-container">
    <div class="form-header">
      <h1 class="form-title">Tambah UKM Baru</h1>
      <p class="form-subtitle">
        Isi data UKM.
      </p>
    </div>

    <?php if ($error !== "") : ?>
      <div class="alert-error"><?= htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

      <!-- NAMA UKM -->
      <div class="form-group">
        <label for="nama_kegiatan" class="form-label">Nama UKM *</label>
        <input
          type="text"
          id="nama_kegiatan"
          name="nama_kegiatan"
          class="form-input"
          value="<?= htmlspecialchars($_POST['nama_kegiatan'] ?? ''); ?>"
          required
        />
        <span class="form-hint">Nama ini yang akan muncul di pemilihan UKM di website utama.</span>
      </div>

      <!-- JUDUL HALAMAN -->
      <div class="form-group">
        <label for="judul_halaman" class="form-label">Judul Halaman</label>
        <input
          type="text"
          id="judul_halaman"
          name="judul_halaman"
          class="form-input"
          placeholder="Contoh: Unit Kegiatan Mahasiswa Paduan Suara Mahatar"
          value="<?= htmlspecialchars($_POST['judul_halaman'] ?? ''); ?>"
        />
        <span class="form-hint">Jika dikosongkan, Otomatis menggunakan nama UKM.</span>
      </div>

      <!-- SUBJUDUL -->
      <div class="form-group">
        <label for="subjudul" class="form-label">Subjudul</label>
        <input
          type="text"
          id="subjudul"
          name="subjudul"
          class="form-input"
          placeholder="Kalimat singkat di bawah judul..."
          value="<?= htmlspecialchars($_POST['subjudul'] ?? ''); ?>"
        />
      </div>

      <!-- DESKRIPSI -->
      <div class="form-group">
        <label for="deskripsi" class="form-label">Deskripsi (Tentang UKM)</label>
        <textarea
          id="deskripsi"
          name="deskripsi"
          class="form-input"
          rows="6"
          placeholder="Ceritakan tentang UKM ini..."
        ><?= htmlspecialchars($_POST['deskripsi'] ?? ''); ?></textarea>
      </div>

      <!-- PEMBINA -->
      <div class="form-group">
        <label for="pembina" class="form-label">Pembina</label>
        <input
          type="text"
          id="pembina"
          name="pembina"
          class="form-input"
          value="<?= htmlspecialchars($_POST['pembina'] ?? ''); ?>"
        />
      </div>

      <!-- JADWAL RUTIN -->
      <div class="form-group">
        <label for="jadwal_rutin" class="form-label">Jadwal Rutin</label>
        <input
          type="text"
          id="jadwal_rutin"
          name="jadwal_rutin"
          class="form-input"
          placeholder="Contoh: Setiap Sabtu, 16.00 WIB"
          value="<?= htmlspecialchars($_POST['jadwal_rutin'] ?? ''); ?>"
        />
      </div>

      <!-- LOKASI -->
      <div class="form-group">
        <label for="lokasi" class="form-label">Lokasi Pelaksanaan UKM</label>
        <input
          type="text"
          id="lokasi"
          name="lokasi"
          class="form-input"
          value="<?= htmlspecialchars($_POST['lokasi'] ?? ''); ?>"
        />
      </div>

      <!-- FOTO -->
      <div class="form-group">
        <label for="foto" class="form-label">Foto UKM</label>
        <div class="file-input-wrapper">
          <input
            type="file"
            id="foto"
            name="foto"
            accept="image/*"
            class="form-input file-input"
          />
        </div>
        <span class="form-hint">Wajib diisi dengan format PNG atau JPG</span>
      </div>

      <!-- TOMBOL -->
      <div class="form-actions">
        <a href="index.php" class="btn-back">
          <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
        </a>
        <button type="submit" class="btn-submit">
          <i class="fa-solid fa-paper-plane"></i> Simpan UKM
        </button>
      </div>
    </form>
  </main>

  <script>
    // Theme Switcher Handler
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

    // Check LocalStorage on Load
    if (localStorage.getItem('theme') === 'light') {
      document.documentElement.setAttribute('data-theme', 'light');
      themeIcon.className = 'fa-solid fa-sun';
    }
  </script>
</body>
</html>