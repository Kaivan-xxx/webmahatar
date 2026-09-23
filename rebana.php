<?php

include "config/koneksi.php";

$limit = 2; // Jumlah data per halaman
$page = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
if ($page < 1) { $page = 1; }
$start = ($page - 1) * $limit;

$id_kategori = 6;

// 1. Hitung total data sesuai tabel dan kategori yang difilter
$query_total = "SELECT COUNT(*) AS total FROM kegiatan WHERE id_kategori_kegiatan = ?";
$stmt_total = mysqli_prepare($conn, $query_total);
mysqli_stmt_bind_param($stmt_total, "i", $id_kategori);
mysqli_stmt_execute($stmt_total);
$result_total = mysqli_stmt_get_result($stmt_total);
$row_total = mysqli_fetch_assoc($result_total);

$total_data = $row_total['total'];
$total_pages = ceil($total_data / $limit);

// 2. Tambahkan LIMIT dan OFFSET pada query utama
$query = "SELECT *
    FROM kegiatan
    WHERE id_kategori_kegiatan = ?
    ORDER BY tanggal DESC
    LIMIT ?, ?
";

$stmt = mysqli_prepare($conn, $query);

// Bind parameter: "iii" (id_kategori, start, limit)
mysqli_stmt_bind_param(
    $stmt,
    "iii",
    $id_kategori,
    $start,
    $limit
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Seni Rebana - Kemahataran AMNI</title>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    />
    <link rel="stylesheet" href="assets/style/WebMahatarAMNI (STYLE).css" />
    <style>
      .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 40px;
        margin-bottom: 20px;
      }
      .pagination-container {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: var(--bg-card);
        backdrop-filter: var(--glass-backdrop);
        -webkit-backdrop-filter: var(--glass-backdrop);
        padding: 8px 18px;
        border-radius: var(--radius-lg);
        border: var(--border-glass);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        transition: var(--transition-smooth);
      }
      .page-link-text {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: var(--accent-cyan);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 4px 8px;
        transition: var(--transition-smooth);
      }
      .page-link-text:hover {
        opacity: 0.75;
      }
      .page-link-text.disabled {
        opacity: 0.35;
        pointer-events: none;
        cursor: not-allowed;
      }
      .page-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 36px;
        padding: 0 10px;
        border-radius: var(--radius-sm);
        background: var(--bg-radial-1);
        color: var(--text-primary);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        border: 1px solid transparent;
        transition: var(--transition-smooth);
      }
      .page-link:hover {
        border: var(--border-active);
        color: var(--accent-cyan);
      }
      .page-link.active {
        background: var(--accent-blue);
        color: #ffffff;
        border-color: var(--accent-blue);
      }
      .page-dots {
        color: var(--text-secondary);
        font-weight: 700;
        padding: 0 4px;
        letter-spacing: 2px;
        user-select: none;
      }
    </style>
  </head>
  <body>
    <!-- NAVBAR -->
    <nav class="navbar">
      <div class="navbar-container">
        <a href="home.html" class="navbar-brand">
          <img src="assets/image/LogoMahatarAMNI2.png" alt="Logo AMNI" />
        </a>
        <!-- TAMBAHKAN TOMBOL INI -->
        <button
          class="menu-toggle"
          id="menuToggle"
          aria-label="Toggle Navigation"
          data-bs-target="#navMenu"
        >
          <span></span>
          <span></span>
          <span></span>
        </button>
        <ul class="nav-menu" id="navMenu">
          <li><a class="nav-link" href="home.html">Home</a></li>
          <li><a class="nav-link" href="profile.html">Profil</a></li>
          <li><a class="nav-link" href="team.html">Team</a></li>
          <li class="dropdown">
            <a class="nav-link" href="#" id="dropdownBtn">
              Kegiatan Mahasiswa
              <i class="fa-solid fa-chevron-down" style="font-size: 0.7rem"></i>
            </a>
            <ul class="dropdown-menu" id="dropdownMenu">
              <li><a class="dropdown-item" href="bem.php">BEM Mahatar</a></li>
              <li>
                <a class="dropdown-item" href="drum-corps.php">Drum Corps</a>
              </li>
              <li>
                <a class="dropdown-item" href="pedang-pora.php">Pedang Pora</a>
              </li>
              <li>
                <a class="dropdown-item" href="zenkyokushin.php"
                  >Zenkyokushin</a
                >
              </li>
              <li><a class="dropdown-item" href="pkm.php">PKM</a></li>
              <li><a class="dropdown-item" href="rebana.php">Rebana</a></li>
              <li>
                <a class="dropdown-item" href="pencak-silat.php"
                  >Pencak Silat</a
                >
              </li>
              <li>
                <a class="dropdown-item" href="mangrove.php"
                  >Penanaman Mangrove</a
                >
              </li>
            </ul>
          </li>
          <li><a class="nav-link" href="news.php">Pengumuman & Berita</a></li>
          <li>
            <button
              id="theme-toggle"
              class="btn-theme-toggle"
              aria-label="Toggle Theme"
            >
              <i id="theme-icon" class="fa-solid fa-moon"></i>
            </button>
          </li>
        </ul>
      </div>
    </nav>
    <!-- MAIN CONTENT -->
    <main class="news-container">
      <div class="header-section">
        <h1 class="header-title">Seni Rebana & Kerohanian Islam</h1>
        <p class="header-subtitle">
          Wadah pelestarian musik islami tradisional dan pembinaan spiritual
          taruna
        </p>
        <hr class="divider" />
      </div>

      <!-- INFORMASI UTAMA -->
      <div class="profile-card" style="margin-bottom: 30px">
        <div
          style="display: flex; gap: 20px; align-items: center; flex-wrap: wrap"
        >
          <img
            src="assets/image/rebana.jpeg"
            alt="Rebana AMNI"
            style="
              width: 100%;
              max-width: 350px;
              border-radius: var(--radius-md);
              object-fit: cover;
            "
          />
          <div style="flex: 1; min-width: 280px">
            <h3
              style="
                font-size: 1.4rem;
                color: var(--accent-blue);
                margin-bottom: 12px;
              "
            >
              Tentang Tim Rebana
            </h3>
            <p
              style="
                color: var(--text-secondary);
                line-height: 1.7;
                margin-bottom: 15px;
              "
            >
              Grup kerohanian yang mengasah keterampilan seni ketukan
              terbang/rebana dan pembacaan sholawat. Bertujuan menyeimbangkan
              ketahanan fisik ketarunaan dengan kedalaman nilai-nilai keagamaan.
            </p>
            <ul
              style="list-style: none; padding: 0; color: var(--text-primary)"
            >
              <li style="margin-bottom: 8px">
                <i
                  class="fa-solid fa-drum"
                  style="color: var(--accent-cyan); width: 25px"
                ></i>
                <strong>Kategori:</strong> Hadroh Klasik & Modern
              </li>
              <li style="margin-bottom: 8px">
                <i
                  class="fa-solid fa-calendar-check"
                  style="color: var(--accent-cyan); width: 25px"
                ></i>
                <strong>Jadwal Latihan:</strong> Kamis Malam (Bada Isya)
              </li>
              <li style="margin-bottom: 8px">
                <i
                  class="fa-solid fa-location-dot"
                  style="color: var(--accent-cyan); width: 25px"
                ></i>
                <strong>Lokasi:</strong> Masjid Kampus AMNI
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- STRUKTUR TIM REBANA -->
      <div class="profile-card" style="margin-bottom: 30px">
        <h3 style="font-size: 1.2rem; margin-bottom: 15px">
          <i
            class="fa-solid fa-users-gear"
            style="color: var(--accent-blue)"
          ></i>
          Pengurus Tim Hadroh
        </h3>
        <div
          style="
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            text-align: center;
          "
        >
          <div
            style="
              padding: 15px;
              border: var(--border-glass);
              border-radius: var(--radius-sm);
              background: rgba(255, 255, 255, 0.02);
            "
          >
            <h4 style="color: var(--accent-cyan); margin-bottom: 5px">
              Pembina Kerohanian
            </h4>
            <p style="color: var(--text-primary); font-weight: bold; margin: 0">
              Ust. H. Nur Hidayat, S.Ag.
            </p>
            <span style="font-size: 0.8rem; color: var(--text-secondary)"
              >Pembimbing Rohani</span
            >
          </div>
          <div
            style="
              padding: 15px;
              border: var(--border-glass);
              border-radius: var(--radius-sm);
              background: rgba(255, 255, 255, 0.02);
            "
          >
            <h4 style="color: var(--accent-cyan); margin-bottom: 5px">
              Ketua Grup Hadroh
            </h4>
            <p style="color: var(--text-primary); font-weight: bold; margin: 0">
              Faisal Anwar
            </p>
            <span style="font-size: 0.8rem; color: var(--text-secondary)"
              >NPT. 23010022</span
            >
          </div>
        </div>
      </div>

 <!-- BERITA & AGENDA REBANA -->

<div class="news-grid" id="newsGrid">

  <?php if (mysqli_num_rows($result) > 0) : ?>

    <?php while ($kegiatan = mysqli_fetch_assoc($result)) { ?>

      <article class="news-card">

        <div class="news-thumb-wrapper">

          <?php if (!empty($kegiatan['gambar'])) : ?>

            <img
              src="uploads/<?= htmlspecialchars($kegiatan['gambar']); ?>"
              class="news-thumb-img"
              alt="<?= htmlspecialchars($kegiatan['judul']); ?>"
            >

          <?php endif; ?>

        </div>

        <div class="news-content">

          <div class="news-meta">
            <span>
              <i class="fa-regular fa-calendar"></i>
              <?= htmlspecialchars($kegiatan['tanggal']); ?>
            </span>
          </div>

          <h3 class="news-title">
            <?= htmlspecialchars($kegiatan['judul']); ?>
          </h3>

          <!-- Isi lengkap untuk modal -->
          <div class="news-full-body" style="display: none;">
            <?= htmlspecialchars($kegiatan['isi']); ?>
          </div>

          <button
            type="button"
            class="news-read-more-btn"
          >
            Baca Selengkapnya
            <i class="fa-solid fa-arrow-right"></i>
          </button>

        </div>

      </article>

    <?php } ?>

  <?php else : ?>

    <div class="news-empty-state">
      <i class="fa-regular fa-newspaper empty-icon"></i>
      <h3>Belum Ada Kegiatan Yang Di Tambahkan</h3>
      <p>
        Belum ada Kegiatan terbaru yang dipublikasikan
        untuk saat ini.
      </p>
    </div>

  <?php endif; ?>

</div>

    <!-- MODAL DIALOG UNTUK DETAIL BERITA -->
    <div class="news-modal" id="newsModal">
      <div class="news-modal-content">
        <button class="news-modal-close" id="modalCloseBtn">&times;</button>
        <div class="news-modal-body">
          <div class="news-badge" id="modalBadge"></div>
          <h2
            class="news-title"
            id="modalTitle"
            style="margin-top: 10px; font-size: 1.6rem"
          ></h2>
          <div class="news-meta" id="modalMeta" style="margin: 15px 0"></div>
          <div class="modal-img-wrapper" style="margin-bottom: 20px">
            <img
              id="modalImg"
              src=""
              alt=""
              style="
                width: 100%;
                max-height: 350px;
                object-fit: cover;
                border-radius: var(--radius-md);
              "
            />
          </div>
          <div
            id="modalBodyText"
            style="color: var(--text-secondary); line-height: 1.8"
          ></div>
        </div>
      </div>
    </div>  
    <!-- NAVIGASI PAGINATION -->
      <?php if ($total_pages > 1) { ?>
      <div class="pagination-wrapper">
        <div class="pagination-container">
          <!-- Tombol Back -->
          <a href="?halaman=<?php echo $page - 1; ?>" class="page-link-text <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
            <i class="fa-solid fa-chevron-left"></i> Back
          </a>

          <!-- Nomor Halaman & Dots (...) -->
          <?php
          $range = 1;
          $show_dots_left = true;
          $show_dots_right = true;

          for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == 1 || $i == $total_pages || ($i >= $page - $range && $i <= $page + $range)) {
              $activeClass = ($page == $i) ? 'active' : '';
              echo '<a href="?halaman=' . $i . '" class="page-link ' . $activeClass . '">' . $i . '</a>';
            } elseif ($i < $page - $range && $show_dots_left) {
              echo '<span class="page-dots">...</span>';
              $show_dots_left = false;
            } elseif ($i > $page + $range && $show_dots_right) {
              echo '<span class="page-dots">...</span>';
              $show_dots_right = false;
            }
          }
          ?>

          <!-- Tombol Next -->
          <a href="?halaman=<?php echo $page + 1; ?>" class="page-link-text <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
            Next <i class="fa-solid fa-chevron-right"></i>
          </a>
        </div>
      </div>
      <?php } ?>

    </main>

    <footer>
      <small
        >&copy; 2026 Barayudha Arkano, Gilang Dwikananda - SMK Ibu Kartini Semarang.</small
      >
    </footer>
    <script>
      const dropdownBtn = document.getElementById("dropdownBtn");
      const dropdownMenu = document.getElementById("dropdownMenu");
      dropdownBtn?.addEventListener("click", (e) => {
        e.preventDefault();
        dropdownMenu.classList.toggle("active");
      });
      document.addEventListener("DOMContentLoaded", () => {
        const menuToggle = document.getElementById("menuToggle");
        const navMenu = document.getElementById("navMenu");
        const dropdowns = document.querySelectorAll(".dropdown");

        // 1. Toggle Menu Utama (Hamburger)
        if (menuToggle && navMenu) {
          menuToggle.addEventListener("click", (e) => {
            e.stopPropagation();
            navMenu.classList.toggle("active");
          });
        }

        // 2. Toggle Submenu Dropdown saat Di-klik (Khusus Layar HP / Mobile)
        dropdowns.forEach((dropdown) => {
          const dropdownLink = dropdown.querySelector(".nav-link");
          const dropdownMenu = dropdown.querySelector(".dropdown-menu");

          if (dropdownLink && dropdownMenu) {
            dropdownLink.addEventListener("click", (e) => {
              // Jalankan click toggle hanya di tampilan mobile (< 768px)
              if (window.innerWidth <= 768) {
                e.preventDefault();
                e.stopPropagation();
                dropdownMenu.classList.toggle("active");
              }
            });
          }
        });

        // 3. Otomatis Tutup Menu saat Mengklik Area Luar Navbar
        document.addEventListener("click", (e) => {
          if (!e.target.closest(".navbar")) {
            if (navMenu) navMenu.classList.remove("active");
            dropdowns.forEach((dropdown) => {
              const dropdownMenu = dropdown.querySelector(".dropdown-menu");
              if (dropdownMenu) dropdownMenu.classList.remove("active");
            });
          }
        });
      });
      const themeToggleBtn = document.getElementById("theme-toggle");
      const themeIcon = document.getElementById("theme-icon");

      // 1. Cek pilihan tema sebelumnya dari localStorage
      const currentTheme = localStorage.getItem("theme") || "dark";

      // Apply tema saat halaman pertama kali dimuat
      if (currentTheme === "light") {
        document.documentElement.setAttribute("data-theme", "light");
        themeIcon.classList.replace("fa-moon", "fa-sun");
      } else {
        document.documentElement.setAttribute("data-theme", "dark");
        themeIcon.classList.replace("fa-sun", "fa-moon");
      }

      // 2. Event listener untuk mengubah tema saat tombol diklik
      themeToggleBtn.addEventListener("click", () => {
        let theme = document.documentElement.getAttribute("data-theme");

        if (theme === "light") {
          document.documentElement.setAttribute("data-theme", "dark");
          localStorage.setItem("theme", "dark");
          themeIcon.classList.replace("fa-sun", "fa-moon");
        } else {
          document.documentElement.setAttribute("data-theme", "light");
          localStorage.setItem("theme", "light");
          themeIcon.classList.replace("fa-moon", "fa-sun");
        }
      });

// =========================
// MODAL BERITA / KEGIATAN
// =========================

const newsModal = document.getElementById("newsModal");
const modalCloseBtn = document.getElementById("modalCloseBtn");
const modalBadge = document.getElementById("modalBadge");
const modalTitle = document.getElementById("modalTitle");
const modalMeta = document.getElementById("modalMeta");
const modalImg = document.getElementById("modalImg");
const modalBodyText = document.getElementById("modalBodyText");

const readMoreButtons = document.querySelectorAll(".news-read-more-btn");

readMoreButtons.forEach((button) => {
  button.addEventListener("click", function () {
    const card = this.closest(".news-card");

    if (!card) return;

    const titleElement = card.querySelector(".news-title");
    const metaElement = card.querySelector(".news-meta");
    const bodyElement = card.querySelector(".news-full-body");
    const imageElement = card.querySelector(".news-thumb-img");

    const title = titleElement
      ? titleElement.textContent.trim()
      : "";

    const meta = metaElement
      ? metaElement.innerHTML
      : "";

    const body = bodyElement
      ? bodyElement.textContent.trim()
      : "";

    modalTitle.textContent = title;
    modalMeta.innerHTML = meta;
    modalBodyText.textContent = body;

    if (imageElement) {
      modalImg.src = imageElement.src;
      modalImg.alt = title;
      modalImg.style.display = "block";
    } else {
      modalImg.src = "";
      modalImg.alt = "";
      modalImg.style.display = "none";
    }

    newsModal.classList.add("active");
    document.body.style.overflow = "hidden";
  });
});


// Tombol tutup modal
modalCloseBtn.addEventListener("click", function () {
  newsModal.classList.remove("active");
  document.body.style.overflow = "";
});


// Klik area luar modal untuk menutup
newsModal.addEventListener("click", function (event) {
  if (event.target === newsModal) {
    newsModal.classList.remove("active");
    document.body.style.overflow = "";
  }
});


// Tombol Escape untuk menutup
document.addEventListener("keydown", function (event) {
  if (event.key === "Escape") {
    newsModal.classList.remove("active");
    document.body.style.overflow = "";
  }
});

document.addEventListener("DOMContentLoaded", () => {
  const urlParams = new URLSearchParams(window.location.search);
  
  // Cek apakah halaman dimuat karena di-REFRESH (F5 / Reload)
  const isReload = performance.getEntriesByType("navigation")[0]?.type === "reload";

  // Hanya scroll jika ada parameter 'halaman' DAN BUKAN hasil refresh
  if (urlParams.has('halaman') && !isReload) {
    const newsGrid = document.getElementById("newsGrid");
    if (newsGrid) {
      newsGrid.scrollIntoView({ behavior: "smooth" });
    }
  }
});
    </script>
  </body>
</html>
