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
    <link rel="stylesheet" href="assets/style/WebMahatarAMNI (STYLE).css" />
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
