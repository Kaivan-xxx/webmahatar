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
// HAPUS DATA
// =========================

$stmt = mysqli_prepare(
    $conn,
    "DELETE FROM berita WHERE id_berita = ?"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id_berita
);


if (mysqli_stmt_execute($stmt)) {

    header("Location: index.php");
    exit;

} else {

    echo "Berita gagal dihapus: "
         . mysqli_stmt_error($stmt);

}

?>
