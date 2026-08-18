<?php

session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: ../../login.php");
    exit;
}

include "../../config/koneksi.php";

$query = "SELECT * FROM berita ORDER BY tanggal DESC";

$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kelola Berita</title>

</head>

<body>

    <h1>Kelola Berita</h1>

    <p>
        Selamat datang, ke dalam dashboard Bertia
    </p>

   <a href="tambah.php">
        Tambah Berita
    </a>

    <br><br>

    <a href="../dashboard.php">
        Kembali ke Dashboard
    </a>

    <hr>

    <h2>Daftar Berita</h2>

    <?php

    while ($berita = mysqli_fetch_assoc($result)) {

    ?>

        <h3>
            <?php echo $berita['judul']; ?>
        </h3>

        <p>
            <?php echo $berita['isi']; ?>
        </p>

        <hr>

    <?php

    }

    ?>
</body>

</html>