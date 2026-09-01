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
  --bg-dark: #090d16;
  --bg-card: rgba(15, 23, 42, 0.65);
  --glass-backdrop: blur(16px);
  --border-glass: 1px solid rgba(255, 255, 255, 0.1);
  --accent-blue: #38bdf8;
  --gradient-accent: linear-gradient(135deg, #38bdf8, #818cf8);
  --text-primary: #f8fafc;
  --text-secondary: #94a3b8;
  --radius-lg: 24px;
  --radius-md: 12px;
  --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Plus Jakarta Sans', sans-serif;
  background-color: var(--bg-dark);
  background-image: 
    radial-gradient(circle at 15% 15%, rgba(56, 189, 248, 0.12) 0%, transparent 40%),
    radial-gradient(circle at 85% 85%, rgba(129, 140, 248, 0.12) 0%, transparent 40%);
  background-attachment: fixed;
  color: var(--text-primary);
  min-height: 100vh;
  padding: 40px 16px;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* ==========================================
   2. CONTAINER FORM
========================================== */
.form-container {
  width: 100%;
  max-width: 680px;
  padding: 40px;
  background: var(--bg-card);
  backdrop-filter: var(--glass-backdrop);
  -webkit-backdrop-filter: var(--glass-backdrop);
  border: var(--border-glass);
  border-radius: var(--radius-lg);
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
}

.form-header {
  text-align: center;
  margin-bottom: 32px;
}

.form-title {
  font-size: 2rem;
  font-weight: 800;
  background: var(--gradient-accent);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  margin-bottom: 8px;
  line-height: 1.25;
}

/* ==========================================
   3. ELEMEN FORM & INPUT
========================================== */
.form-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
  margin-bottom: 24px;
  width: 100%;
}

.form-label {
  font-size: 0.9rem;
  font-weight: 600;
  color: var(--text-primary);
  letter-spacing: 0.3px;
}

.form-input {
  width: 100%;
  padding: 14px 18px;
  background: rgba(255, 255, 255, 0.03);
  border: 1px solid rgba(255, 255, 255, 0.12);
  border-radius: var(--radius-md);
  color: var(--text-primary);
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 0.95rem;
  outline: none;
  transition: var(--transition);
}

.form-input:focus {
  border-color: var(--accent-blue);
  background: rgba(56, 189, 248, 0.05);
  box-shadow: 0 0 18px rgba(56, 189, 248, 0.2);
}

.form-input::placeholder {
  color: rgba(148, 163, 184, 0.5);
}

/* Custom Dropdown (Select) */
select.form-input {
  appearance: none;
  background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2338bdf8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right 18px center;
  background-size: 16px;
  cursor: pointer;
}

select.form-input option {
  background: #0f172a;
  color: var(--text-primary);
  padding: 10px;
}

/* Textarea */
textarea.form-input {
  resize: vertical;
  min-height: 140px;
  line-height: 1.6;
}

/* Custom File Input */
.file-input-wrapper {
  position: relative;
  width: 100%;
}

input[type="file"].form-input {
  padding: 10px 14px;
}

input[type="file"]::file-selector-button {
  background: rgba(56, 189, 248, 0.12);
  border: 1px solid rgba(56, 189, 248, 0.3);
  color: var(--accent-blue);
  padding: 8px 16px;
  border-radius: 8px;
  cursor: pointer;
  margin-right: 14px;
  font-family: inherit;
  font-weight: 600;
  font-size: 0.85rem;
  transition: var(--transition);
}

input[type="file"]::file-selector-button:hover {
  background: var(--accent-blue);
  color: #0f172a;
}

/* ==========================================
   4. TOMBOL & AKSI
========================================== */
.form-actions {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-top: 36px;
  gap: 16px;
}

.btn-back {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  color: var(--text-secondary);
  font-weight: 600;
  font-size: 0.9rem;
  text-decoration: none;
  transition: var(--transition);
}

.btn-back:hover {
  color: var(--accent-blue);
  transform: translateX(-4px);
}

.btn-submit {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  padding: 12px 28px;
  background: var(--gradient-accent);
  color: #ffffff;
  border: none;
  border-radius: 100px;
  font-size: 0.95rem;
  font-weight: 700;
  cursor: pointer;
  transition: var(--transition);
  box-shadow: 0 8px 25px rgba(56, 189, 248, 0.3);
}

.btn-submit:hover {
  transform: translateY(-2px);
  box-shadow: 0 12px 30px rgba(56, 189, 248, 0.45);
}

/* ==========================================
   5. PENYESUAIAN RESPONSIF
========================================== */

/* Tablet (<= 768px) */
@media (max-width: 768px) {
  body {
    padding: 24px 12px;
  }
  
  .form-container {
    padding: 30px 24px;
  }

  .form-title {
    font-size: 1.75rem;
  }
}

/* Perangkat Seluler / HP (<= 576px) */
@media (max-width: 576px) {
  .form-container {
    padding: 24px 18px;
    border-radius: 18px;
  }

  .form-header {
    margin-bottom: 24px;
  }

  .form-title {
    font-size: 1.45rem;
  }

  .form-input {
    padding: 12px 14px;
    font-size: 0.9rem;
  }

  /* Mengubah posisi tombol menjadi vertikal bertumpuk pada layar smartphone */
  .form-actions {
    flex-direction: column-reverse;
    gap: 14px;
    margin-top: 28px;
  }

  .btn-submit,
  .btn-back {
    width: 100%;
    justify-content: center;
  }

  .btn-submit {
    padding: 14px;
  }

  .btn-back {
    padding: 10px 0;
  }

  input[type="file"]::file-selector-button {
    margin-right: 8px;
    padding: 6px 12px;
    font-size: 0.8rem;
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

      <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
          <label for="judul" class="form-label">Judul Berita</label>
          <input
            type="text"
            id="judul"
            name="judul"
            class="form-input"
            placeholder="Masukkan judul berita..."
            required
          />
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
          <label for="gambar" class="form-label">Foto Berita</label>
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
  </body>
</html>
