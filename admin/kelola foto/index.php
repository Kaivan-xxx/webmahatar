<?php

session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: ../../login.php");
    exit;
}

include "../../config/koneksi.php";


// =========================
// AMBIL DATA FOTO
// =========================

$query = "
    SELECT id_berita, judul, gambar, tanggal
    FROM berita
    WHERE gambar IS NOT NULL
    AND gambar != ''
    ORDER BY tanggal DESC
";

$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Kelola Foto</title>

</head>

<body>

    <h1>Kelola Foto</h1>

    <p>
        Foto yang digunakan pada berita dan prestasi.
    </p>


    <div>

        <?php if (mysqli_num_rows($result) > 0) : ?>

            <?php while ($foto = mysqli_fetch_assoc($result)) : ?>

                <div>

                    <!-- FOTO -->

                    <img
                        src="../../uploads/<?= htmlspecialchars($foto['gambar']); ?>"
                        alt="<?= htmlspecialchars($foto['judul']); ?>"
                        width="300"
                    >


                    <!-- JUDUL BERITA -->

                    <h3>
                        <?= htmlspecialchars($foto['judul']); ?>
                    </h3>


                    <!-- TANGGAL -->

                    <p>
                        <?= htmlspecialchars($foto['tanggal']); ?>
                    </p>

                </div>

                <hr>

            <?php endwhile; ?>

        <?php else : ?>

            <p>
                Belum ada foto yang digunakan.
            </p>

        <?php endif; ?>

    </div>


    <br>

    <a href="../berita/index.php">
        Kembali ke Kelola Berita
    </a>

</body>

</html>