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

<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kelola Berita - Mahatar AMNI</title>

    <!-- Google Fonts & FontAwesome Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <style>
      :root {
        /* Dynamic Palette */
        --bg-main: #090d16;
        --bg-surface: rgba(255, 255, 255, 0.03);
        --bg-card: rgba(255, 255, 255, 0.04);
        --bg-card-hover: rgba(255, 255, 255, 0.08);

        --text-primary: #f8fafc;
        --text-secondary: #94a3b8;
        --text-muted: #64748b;

        /* Accent Gradients */
        --accent-blue: #38bdf8;
        --accent-cyan: #22d3ee;
        --gradient-accent: linear-gradient(135deg, #38bdf8 0%, #818cf8 100%);

        /* Background Gradient Utama */
        --custom-mesh-bg:
          radial-gradient(
            circle at 50% 35%,
            rgba(56, 189, 248, 0.25) 0%,
            transparent 50%
          ),
          radial-gradient(
            circle at 80% 80%,
            rgba(129, 140, 248, 0.15) 0%,
            transparent 40%
          ),
          radial-gradient(
            circle at 20% 20%,
            rgba(34, 211, 238, 0.15) 0%,
            transparent 40%
          ),
          linear-gradient(180deg, #090d16 0%, #0f172a 100%);

        /* UI Tokens */
        --border-glass: 1px solid rgba(255, 255, 255, 0.08);
        --border-active: 1px solid rgba(56, 189, 248, 0.4);
        --glass-backdrop: blur(16px) saturate(180%);
        --radius-lg: 24px;
        --radius-md: 16px;
        --radius-sm: 12px;
        --transition-smooth: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
      }

      /* ==========================================
         2. RESET & GLOBAL STYLES
         ========================================== */
      * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family:
          "Plus Jakarta Sans",
          -apple-system,
          BlinkMacSystemFont,
          "Segoe UI",
          Roboto,
          sans-serif;
      }

      body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        background: var(--custom-mesh-bg);
        background-attachment: fixed;
        color: var(--text-primary);
        line-height: 1.6;
        overflow-x: hidden;
      }

      a {
        text-decoration: none;
        color: inherit;
      }
      .dashboard-card {
        max-width: 1100px;
        margin: 120px auto 60px;
        padding: 40px;
        background: var(--bg-card);
        backdrop-filter: var(--glass-backdrop);
        -webkit-backdrop-filter: var(--glass-backdrop);
        border: var(--border-glass);
        border-radius: var(--radius-lg);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
      }

      .header-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 32px;
      }

      .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: var(--gradient-accent);
        color: #fff;
        border-radius: 100px;
        font-weight: 700;
        font-size: 0.9rem;
        box-shadow: 0 8px 20px rgba(56, 189, 248, 0.25);
        transition: var(--transition-smooth);
      }

      .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(56, 189, 248, 0.4);
      }

      .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--text-secondary);
        font-weight: 600;
        font-size: 0.9rem;
        transition: var(--transition-smooth);
      }

      .btn-back:hover {
        color: var(--accent-blue);
        transform: translateX(-4px);
      }

      /* Custom Table Styling */
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
        background: rgba(15, 23, 42, 0.4);
      }

      .custom-table th,
      .custom-table td {
        padding: 16px 20px;
        border-bottom: var(--border-glass);
      }

      .custom-table th {
        background: rgba(255, 255, 255, 0.05);
        color: var(--text-primary);
        font-weight: 700;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
      }

      .custom-table tr:last-child td {
        border-bottom: none;
      }

      .custom-table tr:hover td {
        background: rgba(255, 255, 255, 0.02);
      }

      .news-title-cell {
        font-weight: 600;
        color: var(--text-primary);
      }

      .news-excerpt-cell {
        color: var(--text-secondary);
        font-size: 0.88rem;
        max-width: 400px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
      }

      .action-btns {
        display: flex;
        gap: 10px;
      }

      .btn-action {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        font-size: 0.85rem;
        transition: var(--transition-smooth);
      }

      .btn-edit {
        background: rgba(56, 189, 248, 0.15);
        color: var(--accent-blue);
        border: 1px solid rgba(56, 189, 248, 0.3);
      }

      .btn-edit:hover {
        background: var(--accent-blue);
        color: #fff;
      }

      .btn-delete {
        background: rgba(239, 68, 68, 0.15);
        color: #fca5a5;
        border: 1px solid rgba(239, 68, 68, 0.3);
      }

      .btn-delete:hover {
        background: #ef4444;
        color: #fff;
      }

      .empty-state {
        text-align: center;
        padding: 40px;
        color: var(--text-muted);
      }

      .form-container {
        max-width: 680px;
        margin: 80px auto 40px;
        padding: 40px;
        background: var(--bg-card, rgba(15, 23, 42, 0.65));
        backdrop-filter: var(--glass-backdrop, blur(16px));
        -webkit-backdrop-filter: var(--glass-backdrop, blur(16px));
        border: var(--border-glass, 1px solid rgba(255, 255, 255, 0.1));
        border-radius: var(--radius-lg, 24px);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
      }

      .form-header {
        text-align: center;
        margin-bottom: 32px;
      }

      .form-title {
        font-size: 2rem;
        font-weight: 800;
        background: var(
          --gradient-accent,
          linear-gradient(135deg, #38bdf8, #818cf8)
        );
        background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 8px;
      }

      /* ==========================================
         FORM ELEMENTS & INPUTS
      ========================================== */
      .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 24px;
      }

      .form-label {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-primary, #f8fafc);
        letter-spacing: 0.3px;
      }

      .form-input {
        width: 100%;
        padding: 14px 18px;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 12px;
        color: var(--text-primary, #f8fafc);
        font-family: "Plus Jakarta Sans", sans-serif;
        font-size: 0.95rem;
        outline: none;
        transition: all 0.3s ease;
      }

      .form-input:focus {
        border-color: #38bdf8;
        background: rgba(56, 189, 248, 0.05);
        box-shadow: 0 0 15px rgba(56, 189, 248, 0.2);
      }

      .form-input::placeholder {
        color: rgba(148, 163, 184, 0.6);
      }

      /* Custom Select Arrow */
      select.form-input {
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2338bdf8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 18px center;
        background-size: 16px;
        cursor: pointer;
      }

      select.form-input option {
        background: #0f172a;
        color: #f8fafc;
      }

      textarea.form-input {
        resize: vertical;
        min-height: 140px;
        line-height: 1.6;
      }

      /* Custom File Upload */
      .file-input-wrapper {
        position: relative;
      }

      input[type="file"]::file-selector-button {
        background: rgba(56, 189, 248, 0.12);
        border: 1px solid rgba(56, 189, 248, 0.3);
        color: #38bdf8;
        padding: 8px 16px;
        border-radius: 8px;
        cursor: pointer;
        margin-right: 14px;
        font-family: inherit;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.3s ease;
      }

      input[type="file"]::file-selector-button:hover {
        background: #38bdf8;
        color: #0f172a;
      }

      /* ==========================================
         BUTTONS & ACTIONS
      ========================================== */
      .form-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 36px;
        gap: 16px;
      }

      .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--text-secondary, #94a3b8);
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.3s ease;
      }

      .btn-back:hover {
        color: #38bdf8;
        transform: translateX(-4px);
      }

      .btn-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 12px 28px;
        background: var(
          --gradient-accent,
          linear-gradient(135deg, #38bdf8, #818cf8)
        );
        color: #ffffff;
        border: none;
        border-radius: 100px;
        font-size: 0.95rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(56, 189, 248, 0.3);
      }

      .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(56, 189, 248, 0.45);
      }
      @media (max-width: 576px) {
        .form-container {
          padding: 24px;
        }

        .form-header {
          margin-bottom: 24px;
        }

        .form-title {
          font-size: 1.45rem;
        }

        .form-input {
          padding: 12px 14px;
          font-size: 0.9rem;
        }

        /* Tombol tumpuk vertikal di layar HP */
        .form-actions {
          flex-direction: column-reverse;
          gap: 14px;
          margin-top: 28px;
        }

        .btn-submit {
          width: 100%;
          justify-content: center;
        }

        .btn-submit {
          padding: 14px;
        }

        .btn-back {
          width: 100%;
          justify-content: left;
          padding: 10px 0;
        }

        /* File Input khusus layar kecil */
        input[type="file"]::file-selector-button {
          margin-right: 8px;
          padding: 6px 12px;
          font-size: 0.8rem;
        }
      }
    </style>
  </head>

  <body>
    <main class="dashboard-card">
      <!-- Top Nav & Header -->
      <div style="margin-bottom: 24px">
        <a href="../dashboard.php" class="btn-back">
          <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
        </a>
      </div>

      <div class="header-actions">
        <div>
          <h1
            style="
              font-size: 2rem;
              font-weight: 800;
              color: var(--text-primary);
            "
          >
            Kelola Berita
          </h1>
          <p style="color: var(--text-secondary); font-size: 0.9rem">
            Selamat datang di halaman pengelolaan berita Mahatar AMNI.
          </p>
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
              <th style="width: 50px">No</th>
              <th>Judul Berita</th>
              <th>Isi Berita</th>
              <th style="width: 100px; text-align: center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1; if (mysqli_num_rows($result) > 0) : while ($berita =
            mysqli_fetch_assoc($result)) : ?>
            <tr>
              <td><?= $no++; ?></td>
              <td class="news-title-cell">
                <?= htmlspecialchars($berita['judul']); ?>
              </td>
              <td class="news-excerpt-cell">
                <?= htmlspecialchars(strip_tags($berita['isi'])); ?>
              </td>
              <td>
                <div class="action-btns" style="justify-content: center">
                  <a
                    href="edit.php?id=<?= $berita['id']; ?>"
                    class="btn-action btn-edit"
                    title="Edit"
                  >
                    <i class="fa-solid fa-pen"></i>
                  </a>
                  <a
                    href="hapus.php?id=<?= $berita['id']; ?>"
                    class="btn-action btn-delete"
                    title="Hapus"
                    onclick="
                      return confirm(
                        'Apakah Anda yakin ingin menghapus berita ini?',
                      );
                    "
                  >
                    <i class="fa-solid fa-trash"></i>
                  </a>
                </div>
              </td>
            </tr>
            <?php endwhile; else : ?>
            <tr>
              <td colspan="4" class="empty-state">
                <i
                  class="fa-regular fa-folder-open"
                  style="font-size: 2rem; margin-bottom: 8px; display: block"
                ></i>
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

