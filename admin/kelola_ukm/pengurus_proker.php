<?php

require '../middleware/cek_akses.php';
cekRole(['Super_Admin']);

include "../../config/koneksi.php";


// =========================
// AMBIL DATA UKM
// =========================

$id_ukm = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt_ukm = mysqli_prepare(
    $conn,
    "SELECT id_kategori_kegiatan, nama_kegiatan
     FROM kategori_kegiatan
     WHERE id_kategori_kegiatan = ?"
);
mysqli_stmt_bind_param($stmt_ukm, "i", $id_ukm);
mysqli_stmt_execute($stmt_ukm);
$ukm = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_ukm));

if (!$ukm) {
    die("UKM tidak ditemukan.");
}


$error_pk = "";
$error_pg = "";

// Kalau tombol pensil diklik, id baris yang diedit ada di URL
$edit_pk_id = isset($_GET['edit_pk']) ? (int)$_GET['edit_pk'] : 0;
$edit_pg_id = isset($_GET['edit_pg']) ? (int)$_GET['edit_pg'] : 0;

// Isi awal form (kosong = mode tambah)
$form_pk = ['judul' => '', 'deskripsi' => ''];
$form_pg = ['jabatan' => '', 'nama' => '', 'npt' => ''];


function bersih($key) {
    return trim($_POST[$key] ?? '');
}

function kosongJadiNull($nilai) {
    return $nilai === '' ? null : $nilai;
}


// =========================
// PROSES FORM (simpan / hapus)
// =========================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $aksi    = $_POST['aksi'] ?? '';
    $id_item = (int)($_POST['id_item'] ?? 0);   // 0 = data baru, >0 = ubah data lama


    // ---------- PROGRAM KERJA: tambah / ubah ----------

    if ($aksi === 'simpan_pk') {

        $form_pk = [
            'judul'     => bersih('judul'),
            'deskripsi' => bersih('deskripsi'),
        ];

        if ($form_pk['judul'] === '') {

            $error_pk = "Judul program kerja wajib diisi.";

        } elseif (mb_strlen($form_pk['judul']) > 150) {

            $error_pk = "Judul program kerja maksimal 150 karakter.";

        } else {

            $deskripsi = kosongJadiNull($form_pk['deskripsi']);

            if ($id_item > 0) {

                $stmt = mysqli_prepare(
                    $conn,
                    "UPDATE program_kerja
                     SET judul = ?, deskripsi = ?
                     WHERE id_program = ? AND id_kategori_kegiatan = ?"
                );
                mysqli_stmt_bind_param($stmt, "ssii", $form_pk['judul'], $deskripsi, $id_item, $id_ukm);

            } else {

                $stmt = mysqli_prepare(
                    $conn,
                    "INSERT INTO program_kerja (id_kategori_kegiatan, judul, deskripsi)
                     VALUES (?, ?, ?)"
                );
                mysqli_stmt_bind_param($stmt, "iss", $id_ukm, $form_pk['judul'], $deskripsi);
            }

            mysqli_stmt_execute($stmt);

            header("Location: pengurus_proker.php?id=" . $id_ukm . "#program-kerja");
            exit;
        }

        // Gagal validasi: tetap di mode yang sama (tambah / ubah)
        $edit_pk_id = $id_item;
    }


    // ---------- PROGRAM KERJA: hapus ----------

    elseif ($aksi === 'hapus_pk') {

        $stmt = mysqli_prepare(
            $conn,
            "DELETE FROM program_kerja
             WHERE id_program = ? AND id_kategori_kegiatan = ?"
        );
        mysqli_stmt_bind_param($stmt, "ii", $id_item, $id_ukm);
        mysqli_stmt_execute($stmt);

        header("Location: pengurus_proker.php?id=" . $id_ukm . "#program-kerja");
        exit;
    }


    // ---------- PENGURUS: tambah / ubah ----------

    elseif ($aksi === 'simpan_pg') {

        $form_pg = [
            'jabatan' => bersih('jabatan'),
            'nama'    => bersih('nama'),
            'npt'     => bersih('npt'),
        ];

        if ($form_pg['jabatan'] === '' || $form_pg['nama'] === '') {

            $error_pg = "Jabatan dan nama pengurus wajib diisi.";

        } elseif (
            mb_strlen($form_pg['jabatan']) > 100 ||
            mb_strlen($form_pg['nama']) > 100 ||
            mb_strlen($form_pg['npt']) > 50
        ) {

            $error_pg = "Isian terlalu panjang (jabatan & nama maks. 100 karakter, NPT maks. 50).";

        } else {

            $npt = kosongJadiNull($form_pg['npt']);

            if ($id_item > 0) {

                $stmt = mysqli_prepare(
                    $conn,
                    "UPDATE pengurus
                     SET jabatan = ?, nama = ?, npt = ?
                     WHERE id_pengurus = ? AND id_kategori_kegiatan = ?"
                );
                mysqli_stmt_bind_param($stmt, "sssii", $form_pg['jabatan'], $form_pg['nama'], $npt, $id_item, $id_ukm);

            } else {

                $stmt = mysqli_prepare(
                    $conn,
                    "INSERT INTO pengurus (id_kategori_kegiatan, jabatan, nama, npt)
                     VALUES (?, ?, ?, ?)"
                );
                mysqli_stmt_bind_param($stmt, "isss", $id_ukm, $form_pg['jabatan'], $form_pg['nama'], $npt);
            }

            mysqli_stmt_execute($stmt);

            header("Location: pengurus_proker.php?id=" . $id_ukm . "#pengurus");
            exit;
        }

        $edit_pg_id = $id_item;
    }


    // ---------- PENGURUS: hapus ----------

    elseif ($aksi === 'hapus_pg') {

        $stmt = mysqli_prepare(
            $conn,
            "DELETE FROM pengurus
             WHERE id_pengurus = ? AND id_kategori_kegiatan = ?"
        );
        mysqli_stmt_bind_param($stmt, "ii", $id_item, $id_ukm);
        mysqli_stmt_execute($stmt);

        header("Location: pengurus_proker.php?id=" . $id_ukm . "#pengurus");
        exit;
    }
}


// =========================
// MODE EDIT: isi form dengan data lama
// (dilewati kalau barusan gagal validasi, supaya isian user tidak hilang)
// =========================

if ($edit_pk_id > 0 && $error_pk === "") {

    $s = mysqli_prepare(
        $conn,
        "SELECT judul, deskripsi FROM program_kerja
         WHERE id_program = ? AND id_kategori_kegiatan = ?"
    );
    mysqli_stmt_bind_param($s, "ii", $edit_pk_id, $id_ukm);
    mysqli_stmt_execute($s);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($s));

    if ($row) {
        $form_pk = ['judul' => $row['judul'], 'deskripsi' => $row['deskripsi'] ?? ''];
    } else {
        $edit_pk_id = 0;   // id tidak ada / bukan milik UKM ini
    }
}

if ($edit_pg_id > 0 && $error_pg === "") {

    $s = mysqli_prepare(
        $conn,
        "SELECT jabatan, nama, npt FROM pengurus
         WHERE id_pengurus = ? AND id_kategori_kegiatan = ?"
    );
    mysqli_stmt_bind_param($s, "ii", $edit_pg_id, $id_ukm);
    mysqli_stmt_execute($s);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($s));

    if ($row) {
        $form_pg = ['jabatan' => $row['jabatan'], 'nama' => $row['nama'], 'npt' => $row['npt'] ?? ''];
    } else {
        $edit_pg_id = 0;
    }
}


// =========================
// AMBIL DAFTAR UNTUK TABEL
// =========================

$s = mysqli_prepare(
    $conn,
    "SELECT * FROM program_kerja WHERE id_kategori_kegiatan = ? ORDER BY id_program ASC"
);
mysqli_stmt_bind_param($s, "i", $id_ukm);
mysqli_stmt_execute($s);
$list_pk = mysqli_stmt_get_result($s);

$s = mysqli_prepare(
    $conn,
    "SELECT * FROM pengurus WHERE id_kategori_kegiatan = ? ORDER BY id_pengurus ASC"
);
mysqli_stmt_bind_param($s, "i", $id_ukm);
mysqli_stmt_execute($s);
$list_pg = mysqli_stmt_get_result($s);

?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Program Kerja &amp; Pengurus - <?= htmlspecialchars($ukm['nama_kegiatan']); ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    :root {
  /* Background Utama: Biru Laut Dalam */
  --bg-dark: #071325;
  --bg-card: rgba(13, 30, 56, 0.75);

  /* Tipografi */
  --text-primary: #f0f6ff;
  --text-secondary: #94a3b8;

  /* Aksen Warna */
  --accent-blue: #0284c7;
  --accent-cyan: #38bdf8;
  --accent-gold: #f59e0b;

  /* Gradasi Tombol */
  --gradient-accent: linear-gradient(135deg, #0284c7 0%, #38bdf8 100%);

  /* Background Mesh Dynamic */
  --custom-mesh-bg: 
    radial-gradient(circle at 50% 35%, rgba(56, 189, 248, 0.25) 0%, transparent 50%),
    radial-gradient(circle at 80% 80%, rgba(129, 140, 248, 0.15) 0%, transparent 40%),
    radial-gradient(circle at 20% 20%, rgba(34, 211, 238, 0.15) 0%, transparent 40%),
    linear-gradient(180deg, #090d16 0%, #0f172a 100%);

  /* UI Tokens */
  --border-glass: 1px solid rgba(255, 255, 255, 0.08);
  --glass-backdrop: blur(16px) saturate(180%);
  --radius-lg: 24px;
  --radius-md: 16px;
  --radius-sm: 12px;
  --transition-smooth: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

[data-theme="light"] {
  --bg-dark: #b8c5d6;
  --bg-card: rgba(226, 232, 240, 0.85);
  --glass-backdrop: blur(16px);
  --border-glass: 1px solid rgba(100, 116, 139, 0.25);

  --text-primary: #1e293b;
  --text-secondary: #212122;

  --accent-blue: #1e40af;
  --accent-cyan: #0284c7;
  --accent-gold: #b45309;

  --gradient-accent: linear-gradient(135deg, #1e40af 0%, #0369a1 100%);

  --custom-mesh-bg: 
    radial-gradient(circle at 50% 35%, rgba(9, 148, 207, 0.57) 0%, transparent 50%),
    radial-gradient(circle at 80% 80%, rgba(245, 159, 11, 0.24) 0%, transparent 40%),
    radial-gradient(circle at 20% 20%, rgba(14, 164, 233, 0.3) 0%, transparent 40%),
    linear-gradient(180deg, #f0f4f9 0%, #e2e8f0 100%);
}

/* ==========================================
   RESET & LAYOUT BASE
   ========================================== */
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
  font-family: 'Plus Jakarta Sans', sans-serif;
}

body {
  background: var(--custom-mesh-bg);
  background-attachment: fixed;
  color: var(--text-primary);
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: flex-start;
  padding: 40px 20px;
  transition: var(--transition-smooth);
}

.dashboard-card {
  background: var(--bg-card);
  backdrop-filter: var(--glass-backdrop);
  -webkit-backdrop-filter: var(--glass-backdrop);
  border: var(--border-glass);
  border-radius: var(--radius-lg);
  max-width: 1100px;
  width: 100%;
  padding: 36px;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
  transition: var(--transition-smooth);
}

/* Nav & Header */
.top-nav {
  margin-bottom: 24px;
}

.header-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 28px;
  gap: 20px;
  flex-wrap: wrap;
}

.page-title {
  font-size: 2rem;
  font-weight: 800;
  color: var(--text-primary);
  margin-bottom: 4px;
}

.page-subtitle {
  color: var(--text-secondary);
  font-size: 0.9rem;
}

/* ==========================================
   TABLE STYLING
   ========================================== */
.table-responsive {
  width: 100%;
  overflow-x: auto;
  border-radius: var(--radius-md);
  border: var(--border-glass);
}

.custom-table {
  width: 100%;
  border-collapse: collapse;
  text-align: left;
  font-size: 0.92rem;
}

.custom-table th {
  background: rgba(0, 0, 0, 0.2);
  color: var(--text-primary);
  font-weight: 700;
  padding: 16px 20px;
  border-bottom: var(--border-glass);
  white-space: nowrap;
}

[data-theme="light"] .custom-table th {
  background: rgba(0, 0, 0, 0.05);
}

.custom-table td {
  padding: 16px 20px;
  border-bottom: var(--border-glass);
  color: var(--text-primary);
  vertical-align: middle;
}

.custom-table tbody tr {
  transition: var(--transition-smooth);
}

.custom-table tbody tr:hover {
  background: rgba(255, 255, 255, 0.03);
}

[data-theme="light"] .custom-table tbody tr:hover {
  background: rgba(0, 0, 0, 0.03);
}

/* Cells formatting */
.row-no {
  font-weight: 600;
  color: var(--text-secondary);
}

.news-title-cell {
  font-weight: 600;
  line-height: 1.4;
}

.category-badge {
  display: inline-block;
  padding: 4px 12px;
  background: rgba(2, 132, 199, 0.2);
  border: 1px solid var(--accent-cyan);
  color: var(--accent-cyan);
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
  white-space: nowrap;
}

[data-theme="light"] .category-badge {
  background: rgba(30, 64, 175, 0.1);
  color: var(--accent-blue);
  border-color: var(--accent-blue);
}

/* Excerpt Truncation (Maksimal 2 baris) */
.news-excerpt-cell p {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  color: var(--text-secondary);
  line-height: 1.5;
  margin: 0;
}

/* Empty State */
.empty-state {
  text-align: center;
  padding: 48px 20px !important;
  color: var(--text-secondary);
}

.empty-icon {
  font-size: 2.5rem;
  margin-bottom: 12px;
  display: block;
  opacity: 0.7;
}

/* ==========================================
   BUTTONS & ACTIONS
   ========================================== */
.btn-back {
  color: var(--text-secondary);
  text-decoration: none;
  font-size: 0.9rem;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: var(--transition-smooth);
}

.btn-back:hover {
  color: var(--accent-cyan);
}

.btn-add {
  background: var(--gradient-accent);
  color: #ffffff;
  text-decoration: none;
  padding: 10px 20px;
  border-radius: var(--radius-sm);
  font-weight: 600;
  font-size: 0.9rem;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: var(--transition-smooth);
  box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
}

.btn-add:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 18px rgba(2, 132, 199, 0.4);
}

/* Action Buttons Grid */
.action-btns {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

.btn-action {
  width: 34px;
  height: 34px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  font-size: 0.85rem;
  transition: var(--transition-smooth);
}

.btn-edit {
  background: rgba(2, 132, 199, 0.15);
  color: var(--accent-cyan);
  border: 1px solid rgba(56, 189, 248, 0.3);
}

.btn-edit:hover {
  background: var(--accent-blue);
  color: #ffffff;
  transform: translateY(-2px);
}

.btn-delete {
  background: rgba(239, 68, 68, 0.15);
  color: #f87171;
  border: 1px solid rgba(239, 68, 68, 0.3);
}

.btn-delete:hover {
  background: #ef4444;
  color: #ffffff;
  transform: translateY(-2px);
}

/* Floating Theme Toggle Button */
.btn-theme-toggle {
  position: fixed;
  top: 20px;
  right: 20px;
  width: 44px;
  height: 44px;
  border-radius: 50%;
  background: var(--bg-card);
  backdrop-filter: var(--glass-backdrop);
  border: var(--border-glass);
  color: var(--text-primary);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.1rem;
  cursor: pointer;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
  transition: var(--transition-smooth);
  z-index: 100;
}

.btn-theme-toggle:hover {
  transform: scale(1.1);
  color: var(--accent-cyan);
}

/* ==========================================
   HALAMAN PROGRAM KERJA & PENGURUS
   ========================================== */
.section-title {
  font-size: 1.4rem;
  font-weight: 700;
  color: var(--text-primary);
  margin: 40px 0 6px;
}

.section-desc {
  color: var(--text-secondary);
  font-size: 0.88rem;
  margin-bottom: 16px;
}

.form-card {
  margin-top: 20px;
  padding: 24px;
  border: var(--border-glass);
  border-radius: var(--radius-md);
  background: rgba(0, 0, 0, 0.08);
}

.form-card h3 {
  font-size: 1.05rem;
  color: var(--text-primary);
  margin-bottom: 16px;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 0 16px;
}

.form-group {
  display: flex;
  flex-direction: column;
  margin-bottom: 16px;
}

.form-label {
  font-size: 0.88rem;
  font-weight: 600;
  color: var(--text-primary);
  margin-bottom: 8px;
}

.form-input {
  width: 100%;
  background: rgba(0, 0, 0, 0.15);
  border: var(--border-glass);
  border-radius: var(--radius-sm);
  padding: 11px 14px;
  color: var(--text-primary);
  font-size: 0.93rem;
  outline: none;
  transition: var(--transition-smooth);
}

[data-theme="light"] .form-input {
  background: rgba(255, 255, 255, 0.6);
}

.form-input:focus {
  border: 1px solid rgba(56, 189, 248, 0.4);
  box-shadow: 0 0 12px rgba(56, 189, 248, 0.25);
}

textarea.form-input {
  resize: vertical;
  line-height: 1.6;
}

.form-buttons {
  display: flex;
  align-items: center;
  gap: 16px;
}

.btn-submit {
  background: var(--gradient-accent);
  color: #ffffff;
  border: none;
  padding: 11px 22px;
  border-radius: var(--radius-sm);
  font-weight: 600;
  font-size: 0.93rem;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: var(--transition-smooth);
}

.btn-submit:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(2, 132, 199, 0.35);
}

.btn-cancel {
  color: var(--text-secondary);
  text-decoration: none;
  font-size: 0.9rem;
  font-weight: 600;
}

.btn-cancel:hover {
  color: var(--accent-cyan);
}

.alert-error {
  background: rgba(239, 68, 68, 0.12);
  border: 1px solid rgba(239, 68, 68, 0.35);
  color: #f87171;
  padding: 12px 16px;
  border-radius: var(--radius-sm);
  font-size: 0.9rem;
  font-weight: 600;
  margin-bottom: 16px;
}

[data-theme="light"] .alert-error {
  color: #dc2626;
}

button.btn-action {
  cursor: pointer;
  font-family: inherit;
}
</style>
</head>
<body>

  <button id="themeToggle" class="btn-theme-toggle" type="button" aria-label="Toggle Theme">
    <i id="themeIcon" class="fa-solid fa-moon"></i>
  </button>

  <main class="dashboard-card">

    <div style="margin-bottom: 24px">
      <a href="index.php" class="btn-back">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar UKM
      </a>
    </div>

    <h1 style="font-size: 2rem; font-weight: 800; color: var(--text-primary)">
      Program Kerja &amp; Pengurus
    </h1>
    <p style="color: var(--text-secondary); font-size: 0.9rem">
      <?= htmlspecialchars($ukm['nama_kegiatan']); ?>
    </p>


    <!-- ==================== PROGRAM KERJA ==================== -->

    <h2 class="section-title" id="program-kerja">Program Kerja</h2>
    <p class="section-desc">Tampil di halaman UKM sebagai kotak bernomor sesuai urutan input.</p>

    <div class="table-responsive">
      <table class="custom-table">
        <thead>
          <tr>
            <th style="width: 50px">No</th>
            <th>Judul</th>
            <th>Deskripsi</th>
            <th style="width: 110px; text-align: center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; if (mysqli_num_rows($list_pk) > 0) : while ($pk = mysqli_fetch_assoc($list_pk)) : ?>
          <tr>
            <td><?= $no++; ?></td>
            <td class="news-title-cell"><?= htmlspecialchars($pk['judul']); ?></td>
            <td><?= htmlspecialchars(mb_strimwidth($pk['deskripsi'] ?? '-', 0, 70, "...")); ?></td>
            <td>
              <div class="action-btns">
                <a
                  href="pengurus_proker.php?id=<?= $id_ukm; ?>&edit_pk=<?= $pk['id_program']; ?>#form-pk"
                  class="btn-action btn-edit"
                  title="Edit"
                >
                  <i class="fa-solid fa-pen"></i>
                </a>
                <form
                  method="POST"
                  action="pengurus_proker.php?id=<?= $id_ukm; ?>"
                  style="display: inline"
                  onsubmit="return confirm('Hapus program kerja ini?');"
                >
                  <input type="hidden" name="aksi" value="hapus_pk" />
                  <input type="hidden" name="id_item" value="<?= $pk['id_program']; ?>" />
                  <button type="submit" class="btn-action btn-delete" title="Hapus">
                    <i class="fa-solid fa-trash"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          <?php endwhile; else : ?>
          <tr>
            <td colspan="4" class="empty-state">Belum ada program kerja.</td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div class="form-card" id="form-pk">
      <h3><?= $edit_pk_id > 0 ? 'Ubah Program Kerja' : 'Tambah Program Kerja'; ?></h3>

      <?php if ($error_pk !== "") : ?>
        <div class="alert-error"><?= htmlspecialchars($error_pk); ?></div>
      <?php endif; ?>

      <form method="POST" action="pengurus_proker.php?id=<?= $id_ukm; ?>">
        <input type="hidden" name="aksi" value="simpan_pk" />
        <input type="hidden" name="id_item" value="<?= $edit_pk_id; ?>" />

        <div class="form-group">
          <label for="pk_judul" class="form-label">Judul *</label>
          <input
            type="text"
            id="pk_judul"
            name="judul"
            class="form-input"
            maxlength="150"
            placeholder="Contoh: Latihan Kepemimpinan Taruna"
            value="<?= htmlspecialchars($form_pk['judul']); ?>"
            required
          />
        </div>

        <div class="form-group">
          <label for="pk_deskripsi" class="form-label">Deskripsi</label>
          <textarea
            id="pk_deskripsi"
            name="deskripsi"
            class="form-input"
            rows="3"
          ><?= htmlspecialchars($form_pk['deskripsi']); ?></textarea>
        </div>

        <div class="form-buttons">
          <button type="submit" class="btn-submit">
            <i class="fa-solid fa-floppy-disk"></i>
            <?= $edit_pk_id > 0 ? 'Simpan Perubahan' : 'Tambah'; ?>
          </button>
          <?php if ($edit_pk_id > 0) : ?>
            <a href="pengurus_proker.php?id=<?= $id_ukm; ?>#program-kerja" class="btn-cancel">Batal</a>
          <?php endif; ?>
        </div>
      </form>
    </div>


    <!-- ==================== PENGURUS ==================== -->

    <h2 class="section-title" id="pengurus">Pengurus</h2>
    <p class="section-desc">Tampil di halaman UKM sesuai urutan input. Mau ganti urutan atau orangnya, cukup edit barisnya.</p>

    <div class="table-responsive">
      <table class="custom-table">
        <thead>
          <tr>
            <th style="width: 50px">No</th>
            <th>Jabatan</th>
            <th>Nama</th>
            <th>NPT</th>
            <th style="width: 110px; text-align: center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; if (mysqli_num_rows($list_pg) > 0) : while ($pg = mysqli_fetch_assoc($list_pg)) : ?>
          <tr>
            <td><?= $no++; ?></td>
            <td class="news-title-cell"><?= htmlspecialchars($pg['jabatan']); ?></td>
            <td><?= htmlspecialchars($pg['nama']); ?></td>
            <td><?= htmlspecialchars($pg['npt'] ?? '-'); ?></td>
            <td>
              <div class="action-btns">
                <a
                  href="pengurus_proker.php?id=<?= $id_ukm; ?>&edit_pg=<?= $pg['id_pengurus']; ?>#form-pg"
                  class="btn-action btn-edit"
                  title="Edit"
                >
                  <i class="fa-solid fa-pen"></i>
                </a>
                <form
                  method="POST"
                  action="pengurus_proker.php?id=<?= $id_ukm; ?>"
                  style="display: inline"
                  onsubmit="return confirm('Hapus pengurus ini?');"
                >
                  <input type="hidden" name="aksi" value="hapus_pg" />
                  <input type="hidden" name="id_item" value="<?= $pg['id_pengurus']; ?>" />
                  <button type="submit" class="btn-action btn-delete" title="Hapus">
                    <i class="fa-solid fa-trash"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          <?php endwhile; else : ?>
          <tr>
            <td colspan="5" class="empty-state">Belum ada pengurus.</td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div class="form-card" id="form-pg">
      <h3><?= $edit_pg_id > 0 ? 'Ubah Pengurus' : 'Tambah Pengurus'; ?></h3>

      <?php if ($error_pg !== "") : ?>
        <div class="alert-error"><?= htmlspecialchars($error_pg); ?></div>
      <?php endif; ?>

      <form method="POST" action="pengurus_proker.php?id=<?= $id_ukm; ?>">
        <input type="hidden" name="aksi" value="simpan_pg" />
        <input type="hidden" name="id_item" value="<?= $edit_pg_id; ?>" />

        <div class="form-grid">
          <div class="form-group">
            <label for="pg_jabatan" class="form-label">Jabatan *</label>
            <input
              type="text"
              id="pg_jabatan"
              name="jabatan"
              class="form-input"
              maxlength="100"
              placeholder="Contoh: Ketua"
              value="<?= htmlspecialchars($form_pg['jabatan']); ?>"
              required
            />
          </div>

          <div class="form-group">
            <label for="pg_nama" class="form-label">Nama *</label>
            <input
              type="text"
              id="pg_nama"
              name="nama"
              class="form-input"
              maxlength="100"
              value="<?= htmlspecialchars($form_pg['nama']); ?>"
              required
            />
          </div>

          <div class="form-group">
            <label for="pg_npt" class="form-label">NPT</label>
            <input
              type="text"
              id="pg_npt"
              name="npt"
              class="form-input"
              maxlength="50"
              value="<?= htmlspecialchars($form_pg['npt']); ?>"
            />
          </div>
        </div>

        <div class="form-buttons">
          <button type="submit" class="btn-submit">
            <i class="fa-solid fa-floppy-disk"></i>
            <?= $edit_pg_id > 0 ? 'Simpan Perubahan' : 'Tambah'; ?>
          </button>
          <?php if ($edit_pg_id > 0) : ?>
            <a href="pengurus_proker.php?id=<?= $id_ukm; ?>#pengurus" class="btn-cancel">Batal</a>
          <?php endif; ?>
        </div>
      </form>
    </div>

  </main>

  <script>
      // Theme Switcher Logic
      const themeToggleBtn = document.getElementById("themeToggle");
      const themeIcon = document.getElementById("themeIcon");

      themeToggleBtn.addEventListener("click", () => {
        const currentTheme =
          document.documentElement.getAttribute("data-theme");
        if (currentTheme === "light") {
          document.documentElement.setAttribute("data-theme", "dark");
          localStorage.setItem("theme", "dark");
          themeIcon.className = "fa-solid fa-moon";
        } else {
          document.documentElement.setAttribute("data-theme", "light");
          localStorage.setItem("theme", "light");
          themeIcon.className = "fa-solid fa-sun";
        }
      });

      // Load Saved Theme
      if (localStorage.getItem("theme") === "light") {
        document.documentElement.setAttribute("data-theme", "light");
        themeIcon.className = "fa-solid fa-sun";
      }
    </script>
</body>
</html>