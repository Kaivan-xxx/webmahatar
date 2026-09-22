<?php

session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: ../../login.php");
    exit;
}

include "../../config/koneksi.php";

$role = $_SESSION['role'];
$id_kategori_login = $_SESSION['id_kategori_kegiatan'];


// =========================
// AMBIL ID
// =========================

$id_kegiatan = $_GET['id'] ?? null;

if (!$id_kegiatan) {
    die("ID kegiatan tidak ditemukan.");
}


// =========================
// AMBIL NAMA GAMBAR + KATEGORI
// =========================

$stmt = mysqli_prepare(
    $conn,
    "SELECT gambar, id_kategori_kegiatan
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
// CEK KEPEMILIKAN (khusus Admin_Kegiatan)
// =========================

if ($role === 'Admin_Kegiatan' && $kegiatan['id_kategori_kegiatan'] != $id_kategori_login) {
    die("Kamu tidak berhak menghapus kegiatan ini.");
}


// =========================
// HAPUS FOTO
// =========================

if (!empty($kegiatan['gambar'])) {

    $lokasi_gambar =
        __DIR__ .
        "/../../uploads/" .
        $kegiatan['gambar'];

    if (file_exists($lokasi_gambar)) {

        unlink($lokasi_gambar);

    }

}


// =========================
// HAPUS DATA DATABASE
// =========================

$stmt_delete = mysqli_prepare(
    $conn,
    "DELETE FROM kegiatan
     WHERE id_kegiatan = ?"
);

mysqli_stmt_bind_param(
    $stmt_delete,
    "i",
    $id_kegiatan
);


if (mysqli_stmt_execute($stmt_delete)) {

    header("Location: index.php");
    exit;

} else {

    die(
        "Gagal menghapus kegiatan: "
        . mysqli_stmt_error($stmt_delete)
    );

}

?>