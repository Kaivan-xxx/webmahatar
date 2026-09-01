<?php

include "config/koneksi.php";

$query = "SELECT * FROM berita ORDER BY tanggal DESC";

$result = mysqli_query($conn, $query);

?>

<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pengumuman & Berita - Kemahataran AMNI</title>

    <!-- Font Awesome Icons -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    />

    <!-- File CSS Utama -->
    <link rel="stylesheet" href="assets/style/WebMahatarAMNI (STYLE).css" />
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
.news-container {
  max-width: 1200px;
  margin: 140px auto 80px;
  padding: 0 20px;
  flex-grow: 1;
}

.news-filter-wrapper {
  display: flex;
  justify-content: center;
  gap: 12px;
  margin-bottom: 40px;
  flex-wrap: wrap;
  margin-top: 20px;
}

.filter-btn {
  background: var(--bg-card);
  backdrop-filter: var(--glass-backdrop);
  border: var(--border-glass);
  color: var(--text-secondary);
  padding: 10px 24px;
  border-radius: 100px;
  font-size: 0.9rem;
  font-weight: 600;
  cursor: pointer;
  transition: var(--transition-smooth);
}

.filter-btn:hover,
.filter-btn.active {
  background: var(--gradient-accent);
  color: #ffffff;
  border-color: transparent;
  box-shadow: 0 8px 20px rgba(56, 189, 248, 0.25);
}

.news-grid {
  display: flex !important;
  flex-wrap: wrap !important; /* KUNCI UTAMA: Memaksa kartu ke-3 dan seterusnya turun ke baris bawah */
  gap: 24px;                  /* Jarak antar kartu */
  width: 100%;
  justify-content: center;
}

.news-card {
  background: var(--bg-card);
  backdrop-filter: var(--glass-backdrop);
  border: var(--border-glass);
  border-radius: var(--radius-lg);
  padding: 0 !important;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  transition: var(--transition-smooth);
  position: relative;
  flex: 0 0 calc(50% - 12px);
  max-width: calc(50% - 12px);
  text-align: left;
  align-items: flex-start;
}

.news-card:hover {
  background: var(--bg-card-hover);
  border: var(--border-active);
  transform: translateY(-6px);
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
}

.news-badge {
  align-self: flex-start;
  font-size: 0.75rem;
  font-weight: 700;
  padding: 6px 14px;
  border-radius: 100px;
  margin-bottom: 16px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.badge-warning {
  background: rgba(245, 158, 11, 0.15);
  color: #fbbf24;
  border: 1px solid rgba(245, 158, 11, 0.3);
}

.badge-primary {
  background: rgba(56, 189, 248, 0.15);
  color: #38bdf8;
  border: 1px solid rgba(56, 189, 248, 0.3);
}

.badge-success {
  background: rgba(34, 197, 94, 0.15);
  color: #4ade80;
  border: 1px solid rgba(34, 197, 94, 0.3);
}

.news-meta {
  display: flex;
  gap: 16px;
  font-size: 0.82rem;
  color: var(--text-muted);
  margin-bottom: 14px;
}

.news-meta i {
  margin-right: 4px;
}

.news-title {
  font-size: 1.2rem;
  font-weight: 700;
  color: var(--text-primary);
  line-height: 1.4;
  margin-bottom: 12px;
}

.news-excerpt {
  font-size: 0.92rem;
  color: var(--text-secondary);
  line-height: 1.6;
  margin-bottom: 24px;
  flex-grow: 1;
}

.news-link {
  font-size: 0.9rem;
  font-weight: 600;
  color: var(--accent-blue);
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: var(--transition-smooth);
}

.news-link:hover {
  color: var(--accent-cyan);
  transform: translateX(4px);
}

.news-thumb-wrapper {
  position: relative;
  width: 100%;
  height: 200px;
  overflow: hidden;
}

.news-thumb-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: var(--transition-smooth);
}

.news-card:hover .news-thumb-img {
  transform: scale(1.05);
}

.news-thumb-wrapper .news-badge {
  position: absolute;
  top: 16px;
  left: 16px;
  z-index: 2;
  margin-bottom: 0;
}

.news-content {
  padding: 24px;
  display: flex;
  flex-direction: column;
  flex-grow: 1;
}

.news-read-more-btn {
  background: none;
  border: none;
  color: var(--accent-blue);
  font-size: 0.9rem;
  font-weight: 600;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 0;
  margin-top: auto;
  transition: var(--transition-smooth);
}

.news-read-more-btn:hover {
  color: var(--accent-cyan);
  transform: translateX(4px);
}

/* Modal Popup Glassmorphism */
.news-modal {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(9, 13, 22, 0.8);
  backdrop-filter: blur(12px);
  z-index: 2000;
  align-items: center;
  justify-content: center;
  padding: 20px;
  opacity: 0;
  transition: var(--transition-smooth);
}

.news-modal.active {
  display: flex;
  opacity: 1;
}

.news-modal-content {
  background: rgba(15, 23, 42, 0.95);
  border: var(--border-glass);
  border-radius: var(--radius-lg);
  max-width: 700px;
  width: 100%;
  max-height: 85vh;
  overflow-y: auto;
  position: relative;
  padding: 32px;
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);
}

.news-modal-close {
  position: absolute;
  top: 20px;
  right: 24px;
  background: none;
  border: none;
  color: var(--text-secondary);
  font-size: 2rem;
  cursor: pointer;
  transition: var(--transition-smooth);
}

.news-modal-close:hover {
  color: var(--text-primary);
}

/* Empty State */
.news-empty-state {
  grid-column: 1 / -1;
  text-align: center;
  padding: 60px 20px;
  background: var(--bg-card);
  backdrop-filter: var(--glass-backdrop);
  border: var(--border-glass);
  border-radius: var(--radius-lg);
}

.news-grid:has(.news-card) .news-empty-state {
  display: none;
}

.empty-icon {
  font-size: 3.5rem;
  color: var(--text-muted);
  margin-bottom: 16px;
}

.news-empty-state h3 {
  font-size: 1.4rem;
  color: var(--text-primary);
  margin-bottom: 8px;
}

.news-empty-state p {
  color: var(--text-secondary);
  font-size: 0.95rem;
}
    </style>
  </head>
  <body>
    <!-- Navigation Bar -->

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
              <li><a class="dropdown-item" href="bem.html">BEM Mahatar</a></li>
              <li>
                <a class="dropdown-item" href="drum-corps.html">Drum Corps</a>
              </li>
              <li>
                <a class="dropdown-item" href="pedang-pora.html">Pedang Pora</a>
              </li>
              <li>
                <a class="dropdown-item" href="zenkyokushin.html"
                  >Zenkyokushin</a
                >
              </li>
              <li><a class="dropdown-item" href="pkm.html">PKM</a></li>
              <li><a class="dropdown-item" href="rebana.html">Rebana</a></li>
              <li>
                <a class="dropdown-item" href="pencak-silat.html"
                  >Pencak Silat</a
                >
              </li>
              <li>
                <a class="dropdown-item" href="mangrove.html"
                  >Penanaman Mangrove</a
                >
              </li>
            </ul>
          </li>
          <li><a class="nav-link" href="news.html">Pengumuman & Berita</a></li>
        </ul>
      </div>
    </nav>

    <!-- MAIN CONTENT SECTION -->
    <main class="news-container">
      <div class="header-section">
        <h1 class="header-title">Pengumuman & Berita</h1>
        <p class="header-subtitle">
          Informasi akademik, kegiatan taruna, dan pengumuman terbaru
          Kemahataran AMNI
        </p>
        <hr class="divider" />
     

    <div class="news-filter-wrapper">
        <button class="filter-btn " data-filter="all">Semua</button>
        <button class="filter-btn" data-filter="pengumuman">Pengumuman</button>
    </div>


    

      <!-- Grid Berita & Pengumuman (Area Kosong) -->
      <div class="news-grid" id="newsGrid">
        <!-- TEMPLATE KARTU BERITA KOSONG (Silakan duplikasi & isi saat menambah berita baru) -->
      <?php while ($berita = mysqli_fetch_assoc($result)) { ?>
      <article class="news-card" data-category="pengumuman">
        <div class="news-thumb-wrapper">
          <img 
            src="uploads/<?php echo $berita['gambar']; ?>"
            width="300"
           class="news-thumb-img" />
        </div>
        <div class="news-content">
          <div class="news-meta">
            <span><i class="fa-regular fa-calendar"></i> <?php echo $berita['tanggal']; ?></span>
            <span><i class="fa-regular fa-user"></i> Penulis</span>
          </div>
          <h3 class="news-title"> <?php echo $berita['judul']; ?></h3>

          <div class="news-full-body" style="display:none;">
            <p><?php echo $berita['isi']; ?></p>
          </div>

          <button class="news-read-more-btn">
            Baca Selengkapnya <i class="fa-solid fa-arrow-right"></i>
          </button>
        </div>
      </article>


      <?php } ?>

        <!-- Tampilan Status Saat Berita Kosong -->
        <div class="news-empty-state">
          <i class="fa-regular fa-newspaper empty-icon"></i>
          <h3>Belum Ada Berita</h3>
          <p>
            Belum ada pengumuman atau berita terbaru yang dipublikasikan untuk
            saat ini.
          </p>
        </div>
      </div>
    </main>

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

    <!-- FOOTER -->
    <footer>
      <p>&copy; 2026 Kemahataran AMNI Semarang. All rights reserved.</p>
    </footer>

    <!-- JAVASCRIPT SCRIPT -->
    <script>
      // 1. Dropdown Navbar
      const dropdownBtn = document.getElementById("dropdownBtn");
      const dropdownMenu = document.getElementById("dropdownMenu");

      dropdownBtn.addEventListener("click", function (e) {
        e.preventDefault();
        dropdownMenu.classList.toggle("active");
      });

      window.addEventListener("click", function (e) {
        if (
          !dropdownBtn.contains(e.target) &&
          !dropdownMenu.contains(e.target)
        ) {
          dropdownMenu.classList.remove("active");
        }
      });

      // 2. Filter Kategori
      const filterBtns = document.querySelectorAll(".filter-btn");
      const newsCards = document.querySelectorAll(".news-card");

      filterBtns.forEach((btn) => {
        btn.addEventListener("click", () => {
          filterBtns.forEach((b) => b.classList.remove("active"));
          btn.classList.add("active");

          const filterValue = btn.getAttribute("data-filter");

          newsCards.forEach((card) => {
            if (
              filterValue === "all" ||
              card.getAttribute("data-category") === filterValue
            ) {
              card.style.display = "flex";
            } else {
              card.style.display = "none";
            }
          });
        });
      });

      // 3. Modal Baca Selengkapnya
      const newsModal = document.getElementById("newsModal");
      const modalCloseBtn = document.getElementById("modalCloseBtn");

      document.querySelectorAll(".news-read-more-btn").forEach((button) => {
        button.addEventListener("click", function () {
          const card = this.closest(".news-card");

          const badge = card.querySelector(".news-badge").cloneNode(true);
          const title = card.querySelector(".news-title").innerText;
          const meta = card.querySelector(".news-meta").innerHTML;
          const imgSrc = card.querySelector(".news-thumb-img").src;
          const bodyContent = card.querySelector(".news-full-body").innerHTML;

          document.getElementById("modalBadge").innerHTML = "";
          document.getElementById("modalBadge").appendChild(badge);
          document.getElementById("modalTitle").innerText = title;
          document.getElementById("modalMeta").innerHTML = meta;
          document.getElementById("modalImg").src = imgSrc;
          document.getElementById("modalBodyText").innerHTML = bodyContent;

          newsModal.classList.add("active");
        });
      });

      modalCloseBtn.addEventListener("click", () => {
        newsModal.classList.remove("active");
      });

      window.addEventListener("click", (e) => {
        if (e.target === newsModal) {
          newsModal.classList.remove("active");
        }
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
    </script>
  </body>
</html>
