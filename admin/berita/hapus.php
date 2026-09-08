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
    "SELECT gambar FROM berita WHERE id_berita = ?"
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
// HAPUS GAMBAR
// =========================

if (!empty($berita['gambar'])) {

    $lokasi_gambar = __DIR__ . "/../../uploads/" . $berita['gambar'];

    if (file_exists($lokasi_gambar)) {
        unlink($lokasi_gambar);
    }
}


// =========================
// HAPUS DATA BERITA
// =========================

$stmt_delete = mysqli_prepare(
    $conn,
    "DELETE FROM berita WHERE id_berita = ?"
);

mysqli_stmt_bind_param(
    $stmt_delete,
    "i",
    $id_berita
);


if (mysqli_stmt_execute($stmt_delete)) {

    header("Location: index.php");
    exit;

} else {

    die(
        "Gagal menghapus berita: "
        . mysqli_stmt_error($stmt_delete)
    );

}

?>