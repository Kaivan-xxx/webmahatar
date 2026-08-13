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

    <h1>DASHBOARD ADMIN MAHATAR</h1>
    <h2>INGAT, SELALU LOGOUT SETELAH MENGGUNAKAN DASHBOARD ADMIN,<br> UNTUK MENGHINDARI AKSES YANG TIDAK DIINGINKAN.</h2>
<hr>

    <h2>Menu Admin</h2>

    <ul>

        <li>
            <a href="berita/index.php">
                Kelola Berita
            </a>
        </li>

        <li>
            <a href="galeri/index.php">
                Kelola Galeri
            </a>
        </li>

        <li>
            <a href="prestasi/index.php">
                Kelola Prestasi
            </a>
        </li>

    </ul>

    <br>
    <button><a href="logout.php">Logout</a></button>
</body>

</html>