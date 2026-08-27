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
    <title>Kelola Berita - Mahatar AMNI</title>
    
    <!-- Google Fonts & FontAwesome Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/style/WebMahatarAMNI (STYLE).css">
</head>

<body>

    <main class="dashboard-card">
        <!-- Top Nav & Header -->
        <div style="margin-bottom: 24px;">
            <a href="../dashboard.php" class="btn-back">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>

        <div class="header-actions">
            <div>
                <h1 style="font-size: 2rem; font-weight: 800; color: var(--text-primary);">Kelola Berita</h1>
                <p style="color: var(--text-secondary); font-size: 0.9rem;">Selamat datang di halaman pengelolaan berita Mahatar AMNI.</p>
            </div>
            <a href="tambah.php" class="btn-add">
                <i class="fa-solid fa-plus"></i> Tambah Berita
            </a>
        </div>

        <!-- Table Berita -->
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Judul Berita</th>
                        <th>Isi Berita</th>
                        <th style="width: 100px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                   <?php 
                    $no = 1;
                    if (mysqli_num_rows($result) > 0) :
                        while ($berita = mysqli_fetch_assoc($result)) : 
                    ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td class="news-title-cell"><?= htmlspecialchars($berita['judul']); ?></td>
                            <td class="news-excerpt-cell"><?= htmlspecialchars(strip_tags($berita['isi'])); ?></td>
                            <td>
                                <div class="action-btns" style="justify-content: center;">
                                    <a href="edit.php?id=<?= $berita['id']; ?>" class="btn-action btn-edit" title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <a href="hapus.php?id=<?= $berita['id']; ?>" class="btn-action btn-delete" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus berita ini?');">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php 
                        endwhile;
                    else : 
                    ?>
                        <tr>
                            <td colspan="4" class="empty-state">
                                <i class="fa-regular fa-folder-open" style="font-size: 2rem; margin-bottom: 8px; display: block;"></i>
                                Belum ada berita yang ditambahkan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

</body>

</html>