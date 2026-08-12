<?php
    session_start();

    if (!isset($_SESSION['id_user'])) {
        header("Location: ../login.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin Mahatar</title>

</head>

<body>

    <h1>Dashboard Admin Mahatar</h1>

    <p>
        Selamat datang,
        Di Dashboard Admin Mahatar, Anda dapat mengelola konten dan data di sini.<br><br>
        INGAT, SELALU LOGOUT SETELAH MENGGUNAKAN DASHBOARD ADMIN UNTUK MENGHINDARI AKSES YANG TIDAK DIINGINKAN.
    </p>
    <button><a href="logout.php">Logout</a></button>
</body>

</html>