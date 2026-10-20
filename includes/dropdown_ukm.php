<?php
// Daftar UKM untuk dropdown "Kegiatan Mahasiswa" di navbar.
// Dipakai di semua halaman publik, jadi UKM baru otomatis muncul di semuanya.

require_once __DIR__ . '/../config/koneksi.php';

$daftar_ukm = mysqli_query(
    $conn,
    "SELECT id_kategori_kegiatan, nama_kegiatan
     FROM kategori_kegiatan
     ORDER BY id_kategori_kegiatan ASC"
);

while ($item_ukm = mysqli_fetch_assoc($daftar_ukm)) :
?>
              <li>
                <a
                  class="dropdown-item"
                  href="ukm.php?id=<?= $item_ukm['id_kategori_kegiatan']; ?>"
                  ><?= htmlspecialchars($item_ukm['nama_kegiatan']); ?></a
                >
              </li>
<?php endwhile; ?>