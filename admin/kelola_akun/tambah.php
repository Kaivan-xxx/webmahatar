<?php
require '../middleware/cek_akses.php';
cekRole(['Super_Admin']);

require '../../config/koneksi.php';

$error = "";

// Ambil daftar kegiatan buat dropdown
$kegiatanResult = mysqli_query($conn, "SELECT * FROM kategori_kegiatan ORDER BY nama_kegiatan");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    // Kalau role-nya bukan Admin_Kegiatan, paksa NULL (biar gak salah kesimpen)
    $id_kategori_kegiatan = ($role === 'Admin_Kegiatan') ? $_POST['id_kategori_kegiatan'] : null;

    // Cek dulu username udah dipakai belum
    $cekUsername = mysqli_prepare($conn, "SELECT id_user FROM users WHERE username = ?");
    mysqli_stmt_bind_param($cekUsername, "s", $username);
    mysqli_stmt_execute($cekUsername);
    $hasilCek = mysqli_stmt_get_result($cekUsername);

    if (mysqli_num_rows($hasilCek) > 0) {
        $error = "Username sudah dipakai, pilih yang lain.";
    } else {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = mysqli_prepare($conn, 
            "INSERT INTO users (nama, username, password, role, id_kategori_kegiatan) 
             VALUES (?, ?, ?, ?, ?)"
        );
        mysqli_stmt_bind_param($stmt, "ssssi", $nama, $username, $passwordHash, $role, $id_kategori_kegiatan);
        mysqli_stmt_execute($stmt);

        header("Location: index.php");
        exit;
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Akun Admin</title>
</head>
<body>
  <h1>Tambah Akun Admin</h1>
  <a href="index.php">← Kembali ke Daftar Akun</a>
  <br><br>

  <?php if (!empty($error)): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>

  <form method="POST">
    <label>Nama:</label><br>
    <input type="text" name="nama" required><br><br>

    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <label>Role:</label><br>
    <select name="role" id="roleSelect" required>
      <option value="">-- Pilih Role --</option>
      <option value="Admin">Support Admin</option>
      <option value="Admin_Kegiatan">Admin Kegiatan</option>
    </select>
    <br><br>

    <div id="kegiatanWrapper" style="display:none;">
      <label>Kegiatan yang Dipegang:</label><br>
      <select name="id_kategori_kegiatan">
        <?php while ($k = mysqli_fetch_assoc($kegiatanResult)): ?>
          <option value="<?= $k['id_kategori_kegiatan'] ?>">
            <?= htmlspecialchars($k['nama_kegiatan']) ?>
          </option>
        <?php endwhile; ?>
      </select>
      <br><br>
    </div>

    <button type="submit">Simpan</button>
  </form>

  <script>
    // Dropdown "Kegiatan yang Dipegang" cuma muncul kalau role = Admin_Kegiatan
    const roleSelect = document.getElementById('roleSelect');
    const kegiatanWrapper = document.getElementById('kegiatanWrapper');

    roleSelect.addEventListener('change', () => {
      kegiatanWrapper.style.display = (roleSelect.value === 'Admin_Kegiatan') ? 'block' : 'none';
    });
  </script>
</body>
</html>