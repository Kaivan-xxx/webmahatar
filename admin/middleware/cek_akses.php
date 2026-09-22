<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Wajib login dulu
if (!isset($_SESSION['id_user'])) {
    header("Location: /login.php"); 
    // sesuaikan path ini kalau website kamu bukan di folder root
    exit;
}

// 2. Fungsi buat ngecek role tertentu doang yang boleh masuk
function cekRole(array $roleYangBoleh) {
    if (!in_array($_SESSION['role'], $roleYangBoleh)) {
        http_response_code(403);
        die("<h2 style='font-family:sans-serif;text-align:center;margin-top:50px;'>
                🚫 Akses ditolak. Kamu tidak punya izin ke halaman ini.
             </h2>");
    }
}
?>