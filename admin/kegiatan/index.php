<?php

session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: ../../login.php");
    exit;
}

include "../../config/koneksi.php";


// =========================
// AMBIL DATA KEGIATAN
// =========================

$query = " SELECT
        kegiatan.id_kegiatan,
        kegiatan.judul,
        kegiatan.gambar,
        kegiatan.tanggal,
        kategori_kegiatan.nama_kegiatan

    FROM kegiatan

    JOIN kategori_kegiatan
        ON kegiatan.id_kategori_kegiatan =
           kategori_kegiatan.id_kategori_kegiatan

    ORDER BY kegiatan.tanggal ASC
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

    <title>Kelola Kegiatan</title>

</head>

<body>

    <h1>Kelola Kegiatan</h1>

    <p>
        Kelola informasi kegiatan mahasiswa.
    </p>


    <!-- =========================
         TOMBOL TAMBAH
    ========================== -->

    <a href="tambah.php">
        + Tambah Kegiatan
    </a>


    <br><br>


    <!-- =========================
         TABEL KEGIATAN
    ========================== -->

    <table border="1" cellpadding="10">

        <thead>

            <tr>

                <th>No</th>

                <th>Foto</th>

                <th>Judul</th>

                <th>Kegiatan</th>

                <th>Tanggal</th>

                <th>Aksi</th>

            </tr>

        </thead>


        <tbody>

            <?php

            $no = 1;

            if (mysqli_num_rows($result) > 0):

                while ($kegiatan = mysqli_fetch_assoc($result)):

            ?>

                <tr>

                    <!-- NO -->

                    <td>
                        <?= $no++; ?>
                    </td>


                    <!-- FOTO -->

                    <td>

                        <?php if (!empty($kegiatan['gambar'])): ?>

                            <img
                                src="../../uploads/<?= htmlspecialchars($kegiatan['gambar']); ?>"
                                alt="<?= htmlspecialchars($kegiatan['judul']); ?>"
                                width="120"
                            >

                        <?php else: ?>

                            Tidak ada foto

                        <?php endif; ?>

                    </td>


                    <!-- JUDUL -->

                    <td>

                        <?= htmlspecialchars($kegiatan['judul']); ?>

                    </td>


                    <!-- KATEGORI KEGIATAN -->

                    <td>

                        <?= htmlspecialchars($kegiatan['nama_kegiatan']); ?>

                    </td>


                    <!-- TANGGAL -->

                    <td>

                        <?= htmlspecialchars($kegiatan['tanggal']); ?>

                    </td>


                    <!-- AKSI -->

                    <td>

                        <a
                            href="edit.php?id=<?= $kegiatan['id_kegiatan']; ?>"
                        >
                            Edit
                        </a>

                        |

                        <a
                            href="hapus.php?id=<?= $kegiatan['id_kegiatan']; ?>"
                            onclick="return confirm(
                                'Apakah Anda yakin ingin menghapus kegiatan ini?'
                            );"
                        >
                            Hapus
                        </a>

                    </td>

                </tr>


            <?php

                endwhile;

            else:

            ?>

                <tr>

                    <td colspan="6">

                        Belum ada kegiatan yang ditambahkan.

                    </td>

                </tr>

            <?php endif; ?>

        </tbody>

    </table>

</body>

</html>