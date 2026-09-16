<?php

include "config/koneksi.php";

$id_kategori = 3;

$query = "SELECT *
    FROM kegiatan
    WHERE id_kategori_kegiatan = ?
    ORDER BY tanggal DESC
";

$stmt = mysqli_prepare($conn, $query);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id_kategori
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pedang Pora - Kemahataran AMNI</title>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    />
    <link rel="stylesheet" href="assets/style/WebMahatarAMNI (STYLE).css" />
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
        <h1 class="header-title">Tim Protokoler & Pedang Pora</h1>
        <p class="header-subtitle">
          Pasukan jajar kehormatan pengawal tradisi sakral Kemahataran AMNI
        </p>
        <hr class="divider" />
      </div>

      <!-- INFORMASI UTAMA -->
      <div class="profile-card" style="margin-bottom: 30px">
        <div
          style="display: flex; gap: 20px; align-items: center; flex-wrap: wrap"
        >
          <img
            src="assets/image/profile.jpg"
            alt="Pedang Pora AMNI"
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
              Tentang Pedang Pora
            </h3>
            <p
              style="
                color: var(--text-secondary);
                line-height: 1.7;
                margin-bottom: 15px;
              "
            >
              Tradisi Pedang Pora melambangkan persaudaraan, perlindungan, dan
              penghormatan. Pasukan ini terdiri dari taruna pilihan yang
              terlatih melakukan formasi jajar pedang pada upacara resmi.
            </p>
            <ul
              style="list-style: none; padding: 0; color: var(--text-primary)"
            >
              <li style="margin-bottom: 8px">
                <i
                  class="fa-solid fa-shield-halved"
                  style="color: var(--accent-cyan); width: 25px"
                ></i>
                <strong>Kriteria Pasukan:</strong> Ketahanan PBB, Ketenangan, &
                Postur Ideal
              </li>
              <li style="margin-bottom: 8px">
                <i
                  class="fa-solid fa-calendar-check"
                  style="color: var(--accent-cyan); width: 25px"
                ></i>
                <strong>Jadwal Latihan:</strong> Rabu Sore & Menjelang Event
                Kedinasan
              </li>
              <li style="margin-bottom: 8px">
                <i
                  class="fa-solid fa-location-dot"
                  style="color: var(--accent-cyan); width: 25px"
                ></i>
                <strong>Lokasi Latihan:</strong> Hall Rektorat AMNI
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- STRUKTUR TIM PEDANG PORA -->
      <div class="profile-card" style="margin-bottom: 30px">
        <h3 style="font-size: 1.2rem; margin-bottom: 15px">
          <i
            class="fa-solid fa-users-gear"
            style="color: var(--accent-blue)"
          ></i>
          Komando Pasukan
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
              Komandan Peleton
            </h4>
            <p style="color: var(--text-primary); font-weight: bold; margin: 0">
              Bagas Hendrawan
            </p>
            <span style="font-size: 0.8rem; color: var(--text-secondary)"
              >NPT. 22010076</span
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
              Wakil Danton
            </h4>
            <p style="color: var(--text-primary); font-weight: bold; margin: 0">
              Doni Setiawan
            </p>
            <span style="font-size: 0.8rem; color: var(--text-secondary)"
              >NPT. 22010091</span
            >
          </div>
        </div>
      </div>

<!-- BERITA & AGENDA PEDANG PORA -->

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
    </script>
  </body>
</html>
