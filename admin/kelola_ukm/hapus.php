<?php

require '../middleware/cek_akses.php';
cekRole(['Super_Admin']);

include "../../config/koneksi.php";


// Simpan pesan lalu balik ke daftar UKM
function kembali($tipe, $teks) {
    $_SESSION['flash'] = ['tipe' => $tipe, 'teks' => $teks];
    header("Location: index.php");
    exit;
}


// Hapus cuma boleh lewat tombol (POST), bukan lewat ketik URL
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    kembali('error', 'Permintaan tidak valid.');
}

$id_ukm = (int)($_POST['id'] ?? 0);


// =========================
// AMBIL DATA UKM
// =========================

$stmt = mysqli_prepare(
    $conn,
    "SELECT nama_kegiatan, foto FROM kategori_kegiatan WHERE id_kategori_kegiatan = ?"
);
mysqli_stmt_bind_param($stmt, "i", $id_ukm);
mysqli_stmt_execute($stmt);
$ukm = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$ukm) {
    kembali('error', 'UKM tidak ditemukan.');
}


// =========================
// CEK: MASIH ADA DATA YANG BERGANTUNG?
// =========================

// Jumlah kegiatan milik UKM ini
$stmt = mysqli_prepare(
    $conn,
    "SELECT COUNT(*) AS total FROM kegiatan WHERE id_kategori_kegiatan = ?"
);
mysqli_stmt_bind_param($stmt, "i", $id_ukm);
mysqli_stmt_execute($stmt);
$jumlah_kegiatan = (int)mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['total'];

// Jumlah akun Admin_Kegiatan yang memegang UKM ini
$stmt = mysqli_prepare(
    $conn,
    "SELECT COUNT(*) AS total FROM users WHERE id_kategori_kegiatan = ?"
);
mysqli_stmt_bind_param($stmt, "i", $id_ukm);
mysqli_stmt_execute($stmt);
$jumlah_akun = (int)mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['total'];

$penghalang = [];

if ($jumlah_kegiatan > 0) {
    $penghalang[] = $jumlah_kegiatan . " kegiatan";
}

if ($jumlah_akun > 0) {
    $penghalang[] = $jumlah_akun . " akun admin";
}

if (!empty($penghalang)) {
    kembali(
        'error',
        'UKM "' . $ukm['nama_kegiatan'] . '" belum bisa dihapus karena masih punya '
        . implode(' dan ', $penghalang)
        . '. Hapus dulu data tersebut.'
    );
}


// =========================
// HAPUS UKM
// (program kerja & pengurusnya ikut terhapus otomatis lewat ON DELETE CASCADE)
// =========================

try {

    $stmt = mysqli_prepare(
        $conn,
        "DELETE FROM kategori_kegiatan WHERE id_kategori_kegiatan = ?"
    );
    mysqli_stmt_bind_param($stmt, "i", $id_ukm);
    mysqli_stmt_execute($stmt);

} catch (mysqli_sql_exception $e) {

    kembali('error', 'Gagal menghapus UKM: ' . $e->getMessage());

}


// Hapus file fotonya supaya tidak jadi sampah di folder uploads
if (!empty($ukm['foto'])) {

    $lokasi_foto = __DIR__ . "/../../uploads/" . $ukm['foto'];

    if (file_exists($lokasi_foto)) {
        unlink($lokasi_foto);
    }
}

kembali('sukses', 'UKM "' . $ukm['nama_kegiatan'] . '" berhasil dihapus.');