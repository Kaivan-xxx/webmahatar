<?php
require '../middleware/cek_akses.php';
cekRole(['Super_Admin']); // cuma Super_Admin yang boleh buka halaman ini

require '../../config/koneksi.php'; 
// sesuaikan path ini sama lokasi koneksi.php kamu yang asli

$result = mysqli_query($conn, "SELECT users.*, kategori_kegiatan.nama_kegiatan 
    FROM users 
    LEFT JOIN kategori_kegiatan 
        ON users.id_kategori_kegiatan = kategori_kegiatan.id_kategori_kegiatan
    ORDER BY users.role, users.nama
");
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Akun Admin</title>
</head>
<body>
  <h1>Kelola Akun Admin</h1>
  <a href="../dashboard.php">← Kembali ke Dashboard</a>
  <br><br>
  <a href="tambah.php">+ Tambah Akun Baru</a>
  <br><br>

  <table border="1" cellpadding="8">
    <tr>
      <th>Nama</th>
      <th>Username</th>
      <th>Role</th>
      <th>Kegiatan</th>
      <th>Aksi</th>
    </tr>
    <?php while ($user = mysqli_fetch_assoc($result)): ?>
    <tr>
      <td><?= htmlspecialchars($user['nama']) ?></td>
      <td><?= htmlspecialchars($user['username']) ?></td>
      <td><?= htmlspecialchars($user['role']) ?></td>
      <td><?= $user['nama_kegiatan'] ?? '-' ?></td>
      <td>
         <a href="hapus.php?id=<?= $user['id_user'] ?>" 
     onclick="return confirm('Yakin mau hapus akun <?= htmlspecialchars($user['nama']) ?>?');">
    Hapus
  </a>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>

</body>
</html>