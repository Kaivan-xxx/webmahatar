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
    // JIKA ADMIN MENGUPLOAD GAMBAR BARU
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

        $tujuan = "../../uploads/" . $nama_gambar;


        if (!move_uploaded_file($tmp_gambar, $tujuan)) {
            die("Gambar baru gagal disimpan.");
        }


        // UPDATE termasuk gambar
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

        // UPDATE tanpa mengganti gambar
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

        echo "Berita gagal diperbarui: "
             . mysqli_stmt_error($stmt_update);

    }
}

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

    $tujuan = "../../uploads/" . $nama_gambar;


    // =========================
    // SIMPAN GAMBAR BARU
    // =========================

    if (!move_uploaded_file($tmp_gambar, $tujuan)) {
        die("Gambar baru gagal disimpan.");
    }


    // =========================
    // HAPUS GAMBAR LAMA
    // =========================

    if (!empty($berita['gambar'])) {

        $gambar_lama = "../../uploads/" . $berita['gambar'];

        if (file_exists($gambar_lama)) {
            unlink($gambar_lama);
        }

    }


    // =========================
    // UPDATE DATABASE
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

    <title>Edit Berita</title>

</head>

<body>

    <h1>Edit Berita</h1>


    <form
        method="POST"
        enctype="multipart/form-data"
    >

        <!-- JUDUL -->

        <label>Judul Berita</label>
        <br>

        <input
            type="text"
            name="judul"
            value="<?= htmlspecialchars($berita['judul']); ?>"
            required
        >

        <br><br>


        <!-- KATEGORI -->

        <label>Kategori</label>
        <br>

        <select
            name="id_kategori"
            required
        >

            <?php while ($kategori = mysqli_fetch_assoc($result_kategori)) { ?>

                <option
                    value="<?= $kategori['id_kategori']; ?>"
                    <?= ($kategori['id_kategori'] == $berita['id_kategori']) ? 'selected' : ''; ?>
                >

                    <?= htmlspecialchars($kategori['nama_kategori']); ?>

                </option>

            <?php } ?>

        </select>

        <br><br>


        <!-- ISI -->

        <label>Isi Berita</label>
        <br>

        <textarea
            name="isi"
            rows="10"
            cols="60"
            required
        ><?= htmlspecialchars($berita['isi']); ?></textarea>

        <br><br>


        <!-- GAMBAR LAMA -->

        <label>Gambar Saat Ini</label>

        <br>

        <?php if (!empty($berita['gambar'])) { ?>

            <img
                src="../../uploads/<?= htmlspecialchars($berita['gambar']); ?>"
                width="250"
            >

        <?php } else { ?>

            <p>Tidak ada gambar.</p>

        <?php } ?>

        <br><br>


        <!-- GAMBAR BARU -->

        <label>Ganti Gambar</label>
        <br>

        <input
            type="file"
            name="gambar"
            accept="image/*"
        >

        <br><br>


        <button type="submit">
            Simpan Perubahan
        </button>

    </form>


    <br>

    <a href="index.php">
        Kembali
    </a>

</body>

</html>
