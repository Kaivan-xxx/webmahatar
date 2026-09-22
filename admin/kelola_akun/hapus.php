<?php
require '../middleware/cek_akses.php';
cekRole(['Super_Admin']);

require '../../config/koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Proteksi: jangan sampai Super_Admin gak sengaja hapus akunnya sendiri
    if ($id == $_SESSION['id_user']) {
        die("Kamu tidak bisa menghapus akunmu sendiri yang sedang login.");
    }

    $stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id_user = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
}

header("Location: index.php");
exit;