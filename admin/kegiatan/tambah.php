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

    <title>Tambah Kegiatan</title>

</head>

<body>

    <h1>Tambah Kegiatan</h1>

    <form
        method="POST"
        enctype="multipart/form-data"
    >

        <!-- JUDUL -->

        <label>Judul Kegiatan</label>

        <br>

        <input
            type="text"
            name="judul"
            required
        >

        <br><br>


        <!-- KATEGORI -->

        <label>Kegiatan</label>

        <br>

        <select
            name="id_kategori_kegiatan"
            required
        >

            <option value="">
                -- Pilih Kegiatan --
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

        <br><br>


        <!-- ISI -->

        <label>Isi Kegiatan</label>

        <br>

        <textarea
            name="isi"
            rows="10"
            cols="50"
            required
        ></textarea>

        <br><br>


        <!-- GAMBAR -->

        <label>Foto Kegiatan</label>

        <br>

        <input
            type="file"
            name="gambar"
            accept="image/*"
            required
        >

        <br><br>


        <!-- TOMBOL -->

        <button type="submit">
            Simpan Kegiatan
        </button>

    </form>

    <br>

    <a href="index.php">
        Kembali ke Kelola Kegiatan
    </a>

</body>

</html>