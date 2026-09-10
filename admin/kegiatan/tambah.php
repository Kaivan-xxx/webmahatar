<?php

session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: ../../login.php");
    exit;
}

include "../../config/koneksi.php";


// =========================
// AMBIL KATEGORI KEGIATAN
// =========================

$query_kategori = "
    SELECT *
    FROM kategori_kegiatan
    ORDER BY nama_kegiatan ASC
";

$result_kategori = mysqli_query(
    $conn,
    $query_kategori
);


// =========================
// PROSES FORM
// =========================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // =========================
    // AMBIL DATA FORM
    // =========================

    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $id_kategori_kegiatan = $_POST['id_kategori_kegiatan'];


    // =========================
    // AMBIL DATA GAMBAR
    // =========================

    $nama_asli = $_FILES['gambar']['name'];
    $tmp_gambar = $_FILES['gambar']['tmp_name'];
    $error_gambar = $_FILES['gambar']['error'];


    // =========================
    // CEK GAMBAR
    // =========================

    if ($error_gambar !== UPLOAD_ERR_OK) {
        die("Gambar gagal diupload.");
    }


    // =========================
    // AMBIL EKSTENSI
    // =========================

    $ekstensi = strtolower(
        pathinfo(
            $nama_asli,
            PATHINFO_EXTENSION
        )
    );


    // =========================
    // BUAT NAMA GAMBAR BARU
    // =========================

    $nama_gambar = uniqid() . "." . $ekstensi;


    // =========================
    // TENTUKAN LOKASI
    // =========================

    $tujuan = "../../uploads/" . $nama_gambar;


    // =========================
    // SIMPAN GAMBAR
    // =========================

    if (!move_uploaded_file(
        $tmp_gambar,
        $tujuan
    )) {

        die("Foto gagal disimpan.");

    }


    // =========================
    // SIMPAN KE DATABASE
    // =========================

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO kegiatan
        (
            id_kategori_kegiatan,
            judul,
            isi,
            gambar,
            tanggal
        )
        VALUES (?, ?, ?, ?, NOW())"
    );


    mysqli_stmt_bind_param(
        $stmt,
        "isss",
        $id_kategori_kegiatan,
        $judul,
        $isi,
        $nama_gambar
    );


    // =========================
    // JALANKAN QUERY
    // =========================

    if (mysqli_stmt_execute($stmt)) {

        header("Location: index.php");
        exit;

    } else {

        die(
            "Gagal menambahkan kegiatan: "
            . mysqli_stmt_error($stmt)
        );

    }

}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Tambah Kegiatan - Mahatar AMNI</title>

      <!-- Google Fonts & FontAwesome Icons -->
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
  </style>

</head>

<body>

  <button id="themeToggle" class="btn-theme-toggle" type="button" aria-label="Toggle Theme">
    <i id="themeIcon" class="fa-solid fa-moon"></i>
  </button>

  <main class="form-container">
    <div class="form-header">
      <h1 class="form-title">Tambah Kegiatan Baru</h1>
      <p class="form-subtitle">
        Isi formulir di bawah ini untuk menambahkan kegiatan terbaru.
      </p>
    </div>

    <form
        method="POST"
        enctype="multipart/form-data"
        id="formTambahKegiatan"
    >

        <!-- JUDUL -->
      <div class="form-group">
        <label for="judul" class="form-label">Judul Kegiatan</label>
        <input 
          type="text" 
          id="judul" 
          name="judul" 
          class="form-input" 
          placeholder="Masukkan judul kegiatan..." 
          required 
        />
      </div>


        <!-- KATEGORI -->
        <div class="form-group">
        <label for="id_kategori_kegiatan" class="form-label">Kegiatan Mahasiswa</label>

        <select
            id="id_kategori_kegiatan" name="id_kategori_kegiatan" class="form-input" required
        >

            <option value="">
                -- Pilih Kegiatan Mahasiswa --
            </option>

            <?php while (
                $kategori =
                mysqli_fetch_assoc($result_kategori)
            ) { ?>

                <option
                    value="<?= $kategori['id_kategori_kegiatan']; ?>"
                >

                    <?= htmlspecialchars(
                        $kategori['nama_kegiatan']
                    ); ?>

                </option>

            <?php } ?>

        </select>
    </div>


        <!-- ISI -->
<div class="form-group">
        <label for="isi" class="form-label">Isi Kegiatan</label>

        <textarea
            id="isi"
            class="form-input"
            name="isi"
            rows="8"
            cols="50"
            placeholder="Masukkan isi kegiatan..."
            required
        ></textarea>

    
</div>
        <!-- GAMBAR -->
      <div class="form-group">
        <label for="gambar" class="form-label">Tambah Gambar</label>
        <div class="file-input-wrapper">
          <input 
            type="file" 
            id="gambar" 
            name="gambar" 
            accept="image/*" 
            class="form-input file-input" 
          />
        </div>
      </div>
        <!-- TOMBOL -->
      <div class="form-actions">
        <a href="index.php" class="btn-back">
          <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
        </a>
        <button type="submit" class="btn-submit">
          <i class="fa-solid fa-paper-plane"></i> Simpan Berita
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