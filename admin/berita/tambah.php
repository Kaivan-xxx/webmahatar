<?php

session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: ../../login.php");
    exit;
}

include "../../config/koneksi.php";

$query_kategori = "SELECT * FROM kategori_berita ORDER BY nama_kategori ASC";

$result_kategori = mysqli_query($conn, $query_kategori);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // =========================
    // AMBIL DATA DARI FORM
    // =========================

    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $id_kategori = $_POST['id_kategori'];


    // =========================
    // AMBIL DATA GAMBAR
    // =========================

    $nama_asli = $_FILES['gambar']['name'];
    $tmp_gambar = $_FILES['gambar']['tmp_name'];
    $error_gambar = $_FILES['gambar']['error'];

    $ekstensi = strtolower(
        pathinfo($nama_asli, PATHINFO_EXTENSION)
    );

    $ukuran_gambar = $_FILES['gambar']['size'];


    // =========================
    // BUAT NAMA GAMBAR BARU
    // =========================

    $nama_gambar = uniqid() . "." . $ekstensi;


    // =========================
    // TENTUKAN LOKASI GAMBAR
    // =========================

    $tujuan = "../../uploads/" . $nama_gambar;


    // =========================
    // SIMPAN GAMBAR
    // =========================

    if (!move_uploaded_file($tmp_gambar, $tujuan)) {

        die("Foto gagal disimpan.");

    }


    // =========================
    // SIMPAN DATA BERITA
    // =========================

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO berita
        (judul, isi, id_kategori, gambar, tanggal)
        VALUES (?, ?, ?, ?, NOW())"
    );


    mysqli_stmt_bind_param(
        $stmt,
        "ssis",
        $judul,
        $isi,
        $id_kategori,
        $nama_gambar
    );


    // =========================
    // JALANKAN QUERY
    // =========================

    if (mysqli_stmt_execute($stmt)) {

        echo "Berita berhasil ditambahkan!";

    } else {

        echo "Berita gagal ditambahkan: "
             . mysqli_error($conn);

    }

}

?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tambah Berita - Mahatar AMNI</title>

    <!-- Google Fonts & FontAwesome Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <style>
      /* ==========================================
   1. VARIABLE & RESET DASAR
========================================== */
:root {
  /* Background Utama: Biru Laut Dalam (Ocean Deep Blue) */
  --bg-dark: #071325;
  --bg-card: rgba(13, 30, 56, 0.75);

  /* Tipografi */
  --text-primary: #f0f6ff; /* Catatan: dihapus akhiran '28' agar teks tidak transparan */
  --text-secondary: #94a3b8;

  /* Aksen Bahari & Emas Logo UNIMAR AMNI */
  --accent-blue: #0284c7; /* Biru Laut */
  --accent-cyan: #38bdf8; /* Biru Cerah / Cyan Logo */
  --accent-gold: #f59e0b; /* Kuning Emas Logo */

  /* Gradasi Khas */
  --gradient-accent: linear-gradient(135deg, #e7eaf1 0%, #e7eaf1 100%);

  /* Glowing Effect & Custom Mesh Background (DARK MODE) */
  --bg-radial-1: rgba(2, 132, 199, 0.25);
  --bg-radial-2: rgba(245, 158, 11, 0.12);
  --custom-mesh-bg: 
    radial-gradient(circle at 50% 35%, rgba(56, 189, 248, 0.25) 0%, transparent 50%),
    radial-gradient(circle at 80% 80%, rgba(129, 140, 248, 0.15) 0%, transparent 40%),
    radial-gradient(circle at 20% 20%, rgba(34, 211, 238, 0.15) 0%, transparent 40%),
    linear-gradient(180deg, #090d16 0%, #0f172a 100%);

  /* UI Tokens */
  --border-glass: 1px solid rgba(255, 255, 255, 0.08);
  --border-active: 1px solid rgba(56, 189, 248, 0.4);
  --glass-backdrop: blur(16px) saturate(180%);
  --radius-lg: 24px;
  --radius-md: 16px;
  --radius-sm: 12px;
  --transition-smooth: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

/* ==========================================
   TEMA UNIMAR AMNI SEMARANG (LIGHT MODE)
========================================== */
[data-theme="light"] {
  /* Background Utama: Muted Grayish Blue (Kalem & Tidak Silau) */
  --bg-dark: #b8c5d6;

  /* Kartu & Container: Off-White Gelap (Tidak Putih Ngejreng) */
  --bg-card: rgba(226, 232, 240, 0.85);
  --glass-backdrop: blur(16px);
  --border-glass: 1px solid rgba(100, 116, 139, 0.25);

  /* Tipografi */
  --text-primary: #1e293b; /* Dark Charcoal */
  --text-secondary: #212122; /* Slate Gray */

  /* Aksen Warna Kemaritiman yang Soft */
  --accent-blue: #1e40af; /* Deep Navy (Kalem) */
  --accent-cyan: #0284c7; /* Muted Cyan */
  --accent-gold: #b45309; /* Muted Amber/Gold */

  /* Gradasi Tombol & Header (Lebih Gelap & Teduh) */
  --gradient-accent: linear-gradient(135deg, #1e40af 0%, #0369a1 100%);

  /* Glowing Effect & Custom Mesh Background (LIGHT MODE) */
  --bg-radial-1: rgba(30, 64, 175, 0.05);
  --bg-radial-2: rgba(180, 83, 9, 0.03);
  --custom-mesh-bg: 
    radial-gradient(circle at 50% 35%, rgba(9, 148, 207, 0.57) 0%, transparent 50%),
    radial-gradient(circle at 80% 80%, rgba(245, 159, 11, 0.24) 0%, transparent 40%),
    radial-gradient(circle at 20% 20%, rgba(14, 164, 233, 0.3) 0%, transparent 40%),
    linear-gradient(180deg, #f0f4f9 0%, #e2e8f0 100%);
}

/* ==========================================
   PENERAPAN PADA BODY & TAMPILAN
========================================== */

/* ==========================================
   2. RESET & GLOBAL STYLES
   ========================================== */
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
  font-family:
    "Plus Jakarta Sans",
    -apple-system,
    BlinkMacSystemFont,
    "Segoe UI",
    Roboto,
    sans-serif;
}
body {
  background: var(--custom-mesh-bg);
  background-attachment: fixed; /* Supaya gradasi mesh tetap diam saat halaman di-scroll */
  color: var(--text-primary);
  min-height: 100vh;
  transition: 0.4s ease, color 0.4s ease;
  display: flex;
  justify-content: center; /* Simetris Kanan - Kiri */
  align-items: center;     /* Simetris Atas - Bawah */
  min-height: 100vh;
  margin: 0;
  padding: 1.5rem;
}

a {
  text-decoration: none;
  color: inherit;
}
.form-header {
  display: block;
  justify-content: space-between;
  align-items: flex-start;
  gap: 1rem;
  margin-bottom: 2rem;
}

.form-header-content {
  text-align: left; /* Diubah ke kiri agar rapi bersandingan dengan tombol */
}

.form-container {
  width: 100%;
  max-width: 650px;
  background: var(--bg-card);
  backdrop-filter: var(--glass-backdrop);
  -webkit-backdrop-filter: var(--glass-backdrop);
  border: var(--border-glass);
  border-radius: var(--radius-lg);
  padding: 2.5rem;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
  transition: var(--transition-smooth);
}

.form-header {
  margin-bottom: 2rem;
  text-align: center;
}

.form-title {
  color: var(--text-primary);
  font-size: 1.75rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
  letter-spacing: -0.02em;
}

/* ==========================================
   FORM ELEMENTS
========================================== */
.form-group {
  margin-bottom: 1.5rem;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.form-label {
  color: var(--text-primary);
  font-size: 0.95rem;
  font-weight: 600;
}

.form-input {
  width: 100%;
  padding: 0.85rem 1.15rem;
  background: rgba(255, 255, 255, 0.05);
  border: var(--border-glass);
  border-radius: var(--radius-sm);
  color: var(--text-primary);
  font-size: 0.95rem;
  outline: none;
  transition: var(--transition-smooth);
}

[data-theme="light"] .form-input {
  background: rgba(255, 255, 255, 0.6);
}

.form-input::placeholder {
  color: var(--text-secondary);
  opacity: 0.7;
}

.form-input:focus {
  border: var(--border-active);
  box-shadow: 0 0 0 4px var(--bg-radial-1);
  background: rgba(255, 255, 255, 0.08);
}

textarea.form-input {
  min-height: 140px;
  resize: vertical;
}

/* Select Styling */
select.form-input {
  appearance: none;
  cursor: pointer;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2338bdf8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 1rem center;
  background-size: 1.2rem;
  padding-right: 2.5rem;
}

select.form-input option {
  background-color: var(--bg-dark);
  color: var(--text-primary);
}

/* File Input Styling */
.file-input-wrapper input[type="file"] {
  padding: 0.6rem 0.8rem;
  cursor: pointer;
}

.file-input-wrapper input[type="file"]::file-selector-button {
  background: var(--accent-blue);
  color: #ffffff;
  border: none;
  padding: 0.4rem 0.8rem;
  border-radius: calc(var(--radius-sm) - 4px);
  margin-right: 1rem;
  cursor: pointer;
  transition: var(--transition-smooth);
}

.file-input-wrapper input[type="file"]::file-selector-button:hover {
  background: var(--accent-cyan);
}

/* ==========================================
   BUTTONS & ACTIONS
========================================== */
.form-actions {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  margin-top: 2rem;
}

.btn-back,
.btn-submit {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  padding: 0.85rem 1.5rem;
  border-radius: var(--radius-sm);
  font-size: 0.95rem;
  font-weight: 600;
  cursor: pointer;
  transition: var(--transition-smooth);
}

.btn-back {
  background: rgba(255, 255, 255, 0.05);
  color: var(--text-secondary);
  border: var(--border-glass);
}

.btn-back:hover {
  background: rgba(255, 255, 255, 0.12);
  color: var(--text-primary);
}

.btn-submit {
  background: var(--accent-blue);
  color: #ffffff;
  border: none;
  box-shadow: 0 4px 12px var(--bg-radial-1);
}

.btn-submit:hover {
  background: var(--accent-cyan);
  transform: translateY(-2px);
  box-shadow: 0 6px 20px var(--bg-radial-1);
}
.alert-success {
  background: rgba(34, 197, 94, 0.15);
  border: 1px solid rgba(34, 197, 94, 0.4);
  color: #4ade80;
  padding: 1rem;
  border-radius: var(--radius-sm);
  margin-bottom: 1.5rem;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  font-size: 0.95rem;
  animation: fadeIn 0.3s ease-in-out;
}

[data-theme="light"] .alert-success {
  background: rgba(34, 197, 94, 0.2);
  color: #15803d;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-8px); }
  to { opacity: 1; transform: translateY(0); }
}

/* Responsive Layout */
@media (max-width: 480px) {
  .form-container {
    padding: 1.5rem;
  }

  .form-actions {
    flex-direction: column-reverse;
  }

  .btn-back,
  .btn-submit {
    width: 100%;
  }
}
    </style>
  </head>
  <body>
    <main class="form-container">
      <div class="form-header">
        <h1 class="form-title">Tambah Berita Baru</h1>
        <p style="color: var(--text-secondary); font-size: 0.9rem">
          Isi formulir di bawah ini untuk menambahkan berita terbaru.
        </p>
      </div>

      <form id="formBerita" method="POST" enctype="multipart/form-data">
        <div id="alertContainer"></div>
        <div class="form-group">
        <label for="judul" class="form-label">Judul Berita</label>
        <input type="text" id="judul" name="judul" class="form-input" placeholder="Masukkan judul berita..." required />
      </div>

        <div class="form-group">
          <label for="id_kategori" class="form-label">Kategori</label>
          <select
            id="id_kategori"
            name="id_kategori"
            class="form-input"
            required
          >
            <?php while ($kategori = mysqli_fetch_assoc($result_kategori)) { ?>

            <option value="<?php echo $kategori['id_kategori']; ?>">
              <?php echo $kategori['nama_kategori']; ?>
            </option>

            <?php } ?>
          </select>
        </div>

        <div class="form-group">
          <label for="isi" class="form-label">Isi Berita</label>
          <textarea
            id="isi"
            name="isi"
            class="form-input"
            placeholder="Tuliskan detail isi berita di sini..."
            required
          ></textarea>
        </div>

        <div class="form-group">
          <label for="gambar" class="form-label">Tambah Gambar</label>
          <div class="file-input-wrapper">
            <input
              type="file"
              id="gambar"
              name="gambar"
              accept="image/*"
              class="form-input"
            />
          </div>
        </div>

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
    document.getElementById('formBerita').addEventListener('submit', function (e) {
      e.preventDefault(); // Mencegah reload halaman langsung

      const alertContainer = document.getElementById('alertContainer');
      const submitBtn = this.querySelector('.btn-submit');

      // Ubah tampilan tombol saat proses simpan
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';

      // Simpan data & tampilkan notifikasi sukses
      setTimeout(() => {
        alertContainer.innerHTML = `
          <div class="alert-success">
            <i class="fa-solid fa-circle-check" style="font-size: 1.2rem;"></i>
            <span>Berita berhasil diunggah!</span>
          </div>
        `;

        this.reset(); // Kosongkan form kembali

        // Kembalikan tombol ke keadaan semula
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Simpan Berita';

        // Hilangkan pesan notifikasi setelah 4 detik
        setTimeout(() => {
          alertContainer.innerHTML = '';
        }, 4000);
      }, 1000);
    });

    const themeToggleBtn = document.getElementById('themeToggle');
const icon = themeToggleBtn.querySelector('i');

themeToggleBtn.addEventListener('click', () => {
  const currentTheme = document.documentElement.getAttribute('data-theme');
  
  if (currentTheme === 'light') {
    document.documentElement.removeAttribute('data-theme');
    icon.className = 'fa-solid fa-moon'; // Ikon bulan untuk Dark Mode
  } else {
    document.documentElement.setAttribute('data-theme', 'light');
    icon.className = 'fa-solid fa-sun'; // Ikon matahari untuk Light Mode
  }
});
  </script>
  </body>
</html>
