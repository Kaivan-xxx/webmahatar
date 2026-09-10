<?php

session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: ../../login.php");
    exit;
}

include "../../config/koneksi.php";


// =========================
// AMBIL ID KEGIATAN
// =========================

$id_kegiatan = $_GET['id'] ?? null;

if (!$id_kegiatan) {
    die("ID kegiatan tidak ditemukan.");
}


// =========================
// AMBIL DATA KEGIATAN
// =========================

$stmt = mysqli_prepare(
    $conn,
    "SELECT *
     FROM kegiatan
     WHERE id_kegiatan = ?"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id_kegiatan
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$kegiatan = mysqli_fetch_assoc($result);


if (!$kegiatan) {
    die("Kegiatan tidak ditemukan.");
}


// =========================
// AMBIL KATEGORI
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
// PROSES UPDATE
// =========================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $id_kategori_kegiatan =
        $_POST['id_kategori_kegiatan'];


    // =========================
    // CEK APAKAH ADA FOTO BARU
    // =========================

    if (
        isset($_FILES['gambar']) &&
        $_FILES['gambar']['error'] === UPLOAD_ERR_OK
    ) {

        // =========================
        // DATA FOTO BARU
        // =========================

        $nama_asli =
            $_FILES['gambar']['name'];

        $tmp_gambar =
            $_FILES['gambar']['tmp_name'];


        // =========================
        // EKSTENSI
        // =========================

        $ekstensi = strtolower(
            pathinfo(
                $nama_asli,
                PATHINFO_EXTENSION
            )
        );


        // =========================
        // NAMA FOTO BARU
        // =========================

        $nama_gambar =
            uniqid() . "." . $ekstensi;


        // =========================
        // LOKASI FOTO
        // =========================

        $tujuan =
            "../../uploads/" . $nama_gambar;


        // =========================
        // SIMPAN FOTO BARU
        // =========================

        if (!move_uploaded_file(
            $tmp_gambar,
            $tujuan
        )) {

            die("Gambar baru gagal disimpan.");

        }


        // =========================
        // HAPUS FOTO LAMA
        // =========================

        if (!empty($kegiatan['gambar'])) {

            $gambar_lama =
                "../../uploads/" .
                $kegiatan['gambar'];

            if (file_exists($gambar_lama)) {
                unlink($gambar_lama);
            }

        }


        // =========================
        // UPDATE DENGAN FOTO BARU
        // =========================

        $stmt_update = mysqli_prepare(
            $conn,
            "UPDATE kegiatan
             SET judul = ?,
                 isi = ?,
                 id_kategori_kegiatan = ?,
                 gambar = ?
             WHERE id_kegiatan = ?"
        );

        mysqli_stmt_bind_param(
            $stmt_update,
            "ssisi",
            $judul,
            $isi,
            $id_kategori_kegiatan,
            $nama_gambar,
            $id_kegiatan
        );

    } else {

        // =========================
        // UPDATE TANPA FOTO BARU
        // =========================

        $stmt_update = mysqli_prepare(
            $conn,
            "UPDATE kegiatan
             SET judul = ?,
                 isi = ?,
                 id_kategori_kegiatan = ?
             WHERE id_kegiatan = ?"
        );

        mysqli_stmt_bind_param(
            $stmt_update,
            "ssii",
            $judul,
            $isi,
            $id_kategori_kegiatan,
            $id_kegiatan
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
            "Gagal update kegiatan: "
            . mysqli_stmt_error($stmt_update)
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

    <title>Edit Kegiatan</title>

</head>

<body>

    <h1>Edit Kegiatan</h1>


    <form
        method="POST"
        enctype="multipart/form-data"
    >


        <!-- JUDUL -->

        <label>
            Judul Kegiatan
        </label>

        <br>

        <input
            type="text"
            name="judul"
            value="<?= htmlspecialchars(
                $kegiatan['judul']
            ); ?>"
            required
        >

        <br><br>


        <!-- KATEGORI -->

        <label>
            Kategori Kegiatan
        </label>

        <br>

        <select
            name="id_kategori_kegiatan"
            required
        >

            <?php while (
                $kategori =
                mysqli_fetch_assoc(
                    $result_kategori
                )
            ) { ?>

                <option
                    value="<?= $kategori['id_kategori_kegiatan']; ?>"
                    <?= (
                        $kategori['id_kategori_kegiatan']
                        ==
                        $kegiatan['id_kategori_kegiatan']
                    )
                    ? 'selected'
                    : ''
                    ?>
                >

                    <?= htmlspecialchars(
                        $kategori['nama_kegiatan']
                    ); ?>

                </option>

            <?php } ?>

        </select>

        <br><br>


        <!-- ISI -->

        <label>
            Isi Kegiatan
        </label>

        <br>

        <textarea
            name="isi"
            rows="10"
            cols="60"
            required
        ><?= htmlspecialchars(
            $kegiatan['isi']
        ); ?></textarea>

        <br><br>


        <!-- FOTO LAMA -->

        <label>
            Foto Saat Ini
        </label>

        <br>

        <?php if (!empty($kegiatan['gambar'])) { ?>

            <img
                src="../../uploads/<?= htmlspecialchars(
                    $kegiatan['gambar']
                ); ?>"
                alt="Foto kegiatan"
                width="250"
            >

        <?php } else { ?>

            <p>
                Belum ada foto.
            </p>

        <?php } ?>

        <br><br>


        <!-- FOTO BARU -->

        <label>
            Ganti Foto
        </label>

        <br>

        <input
            type="file"
            name="gambar"
            accept="image/*"
        >

        <p>
            Kosongkan jika tidak ingin mengganti foto.
        </p>

        <br>


        <button type="submit">
            Simpan Perubahan
        </button>

    </form>


    <br>

    <a href="index.php">
        Kembali ke Kelola Kegiatan
    </a>

</body>

</html>