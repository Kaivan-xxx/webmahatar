<?php

include "config/koneksi.php";

// ==========================================
// KONFIGURASI PAGINATION
// ==========================================
$limit = 8; // Jumlah berita per halaman
$page = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
if ($page < 1) { $page = 1; }
$start = ($page > 1) ? ($page * $limit) - $limit : 0;

// Query Hitung Total Data Berita
$query_total = "SELECT COUNT(*) AS total FROM berita";
$result_total = mysqli_query($conn, $query_total);
$row_total = mysqli_fetch_assoc($result_total);
$total_data = $row_total['total'];
$total_pages = ceil($total_data / $limit);

// Query Utama Ambil Data Berita (Dengan LIMIT dan OFFSET)
$query = " SELECT berita.*, kategori_berita.nama_kategori
    FROM berita
    JOIN kategori_berita
    ON berita.id_kategori = kategori_berita.id_kategori
    ORDER BY berita.tanggal DESC
    LIMIT $start, $limit
";

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
      /* CSS Tambahan Khusus Pagination (Sesuai Tema Dark/Light) */
      .pagination-container {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        margin-top: 40px;
        margin-bottom: 20px;
        flex-wrap: wrap;
      }
      .page-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        padding: 0 12px;
        border-radius: var(--radius-md, 8px);
        background-color: var(--bg-card, rgba(255, 255, 255, 0.05));
        color: var(--text-primary, #fff);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        border: 1px solid var(--border-color, rgba(255, 255, 255, 0.1));
        transition: all 0.3s ease;
      }
      .page-link:hover {
        background-color: var(--color-primary, #007bff);
        color: #ffffff;
        border-color: var(--color-primary, #007bff);
      }
      .page-link.active {
        background-color: var(--color-primary, #007bff);
        color: #ffffff;
        border-color: var(--color-primary, #007bff);
      }
      .page-link.disabled {
        opacity: 0.4;
        cursor: not-allowed;
        pointer-events: none;
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

    <div class="filter-group">
    <button class="filter-btn active" data-filter="all">Semua</button>
    <button class="filter-btn" data-filter="berita">Berita</button>
    <button class="filter-btn" data-filter="prestasi">Prestasi</button>
  </div>

  <a href="login.php" class="btn-add-news">
    <i class="fa-solid fa-plus"></i> Tambah Berita
  </a>
    </div>

      <!-- Grid Berita & Pengumuman (Area Kosong) -->
      <div class="news-grid" id="newsGrid">
        <!-- TEMPLATE KARTU BERITA KOSONG (Silakan duplikasi & isi saat menambah berita baru) -->
      <?php while ($berita = mysqli_fetch_assoc($result)) { ?>
      <article  class="news-card"
          data-category="<?php echo strtolower($berita['nama_kategori']); ?>">
       <div class="news-thumb-wrapper">

      <span class="news-badge badge-primary">
        <?php echo $berita['nama_kategori']; ?>
      </span>

    <img 
        src="uploads/<?php echo $berita['gambar']; ?>"
        class="news-thumb-img"
        alt="<?php echo $berita['judul']; ?>"
     >

</div>
        <div class="news-content">
          <div class="news-meta">
            <span><i class="fa-regular fa-calendar"></i> <?php echo $berita['tanggal']; ?></span>
          </div>
          <h3 class="news-title"> <?php echo $berita['judul']; ?></h3>

          <div class="news-full-body" style="display:none;">
            <p><?= nl2br(htmlspecialchars($berita['isi'])); ?></p>
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
          <h3>Belum Ada Berita Atau Prestasi</h3>
          <p>
            Belum ada Prestasi atau Berita terbaru yang dipublikasikan untuk
            saat ini.
          </p>
        </div>
      </div>

      <!-- NAVIGASI PAGINATION -->
      <?php if ($total_pages > 1) { ?>
      <div class="pagination-container">
        <!-- Tombol Previous -->
        <a href="?halaman=<?php echo $page - 1; ?>" class="page-link <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
          <i class="fa-solid fa-chevron-left"></i>
        </a>

        <!-- Nomor Halaman -->
        <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
          <a href="?halaman=<?php echo $i; ?>" class="page-link <?php echo ($page == $i) ? 'active' : ''; ?>">
            <?php echo $i; ?>
          </a>
        <?php } ?>

        <!-- Tombol Next -->
        <a href="?halaman=<?php echo $page + 1; ?>" class="page-link <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
          <i class="fa-solid fa-chevron-right"></i>
        </a>
      </div>
      <?php } ?>

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
      <small
        >&copy; 2026 Barayudha Arkano, Gilang Dwikananda - SMK Ibu Kartini Semarang.</small
      >
    </footer>

    <!-- JAVASCRIPT SCRIPT -->
    <script>
      document.addEventListener("DOMContentLoaded", () => {
  // ==========================================
  // 1. KONTROL EMPTY STATE
  // ==========================================
  const newsGrid = document.querySelector(".news-grid");
  const emptyState = document.querySelector(".news-empty-state");

  function checkEmptyState() {
    if (!newsGrid || !emptyState) return;

    const cards = newsGrid.querySelectorAll(".news-card");
    let visibleCount = 0;

    cards.forEach((card) => {
      if (window.getComputedStyle(card).display !== "none") {
        visibleCount++;
      }
    });

    if (visibleCount > 0) {
      emptyState.style.setProperty("display", "none", "important");
    } else {
      emptyState.style.setProperty("display", "block", "important");
    }
  }

  // ==========================================
  // 2. LOGIKA FILTER CATEGORY
  // ==========================================
  const filterBtns = document.querySelectorAll(".filter-btn");
  const newsCards = document.querySelectorAll(".news-card");

  function filterNews(category) {
    newsCards.forEach((card) => {
      const cardCategory = card.getAttribute("data-category");
      if (cardCategory === category || category === "all") {
        card.style.display = "flex";
      } else {
        card.style.display = "none";
      }
    });
    checkEmptyState();
  }

  filterBtns.forEach((btn) => {
    btn.addEventListener("click", function () {
      filterBtns.forEach((b) => b.classList.remove("active"));
      this.classList.add("active");

      const category = this.getAttribute("data-filter");
      filterNews(category);
    });
  });

  // Filter awal (default)
  const defaultFilter = "all";
  filterNews(defaultFilter);

  // ==========================================
  // 3. LOGIKA MODAL POPUP BERITA
  // ==========================================
  const newsModal = document.getElementById("newsModal");
  const modalCloseBtn = document.getElementById("modalCloseBtn");

  document.querySelectorAll(".news-read-more-btn").forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();
      const card = this.closest(".news-card");
      if (!card) return;

      const badge = card.querySelector(".news-badge")?.cloneNode(true);
      const title = card.querySelector(".news-title")?.innerText || "";
      const meta = card.querySelector(".news-meta")?.innerHTML || "";
      const imgSrc = card.querySelector(".news-thumb-img")?.src || "";
      const bodyContent =
        card.querySelector(".news-full-body")?.innerHTML ||
        card.querySelector(".news-excerpt")?.innerHTML ||
        "";

      const modalBadge = document.getElementById("modalBadge");
      if (modalBadge) {
        modalBadge.innerHTML = "";
        if (badge) modalBadge.appendChild(badge);
      }

      if (document.getElementById("modalTitle"))
        document.getElementById("modalTitle").innerText = title;
      if (document.getElementById("modalMeta"))
        document.getElementById("modalMeta").innerHTML = meta;
      if (document.getElementById("modalImg"))
        document.getElementById("modalImg").src = imgSrc;
      if (document.getElementById("modalBodyText"))
        document.getElementById("modalBodyText").innerHTML = bodyContent;

      if (newsModal) {
        newsModal.classList.add("active");
        document.body.style.overflow = "hidden";
      }
    });
  });

  const closeModal = () => {
    if (newsModal) {
      newsModal.classList.remove("active");
      document.body.style.overflow = "auto";
    }
  };

  if (modalCloseBtn) modalCloseBtn.addEventListener("click", closeModal);

  // ==========================================
  // 4. NAVBAR & DROPDOWN
  // ==========================================
  const menuToggle = document.getElementById("menuToggle");
  const navMenu = document.getElementById("navMenu");
  const dropdowns = document.querySelectorAll(".dropdown");

  if (menuToggle && navMenu) {
    menuToggle.addEventListener("click", (e) => {
      e.stopPropagation();
      navMenu.classList.toggle("active");
    });
  }

  dropdowns.forEach((dropdown) => {
    const dropdownLink = dropdown.querySelector(".nav-link");
    const dropdownMenu = dropdown.querySelector(".dropdown-menu");

    if (dropdownLink && dropdownMenu) {
      dropdownLink.addEventListener("click", (e) => {
        if (window.innerWidth <= 768) {
          e.preventDefault();
          e.stopPropagation();
          dropdownMenu.classList.toggle("active");
        }
      });
    }
  });

  // Close modal/navbar when clicking outside
  window.addEventListener("click", (e) => {
    if (e.target === newsModal) closeModal();

    if (!e.target.closest(".navbar")) {
      if (navMenu) navMenu.classList.remove("active");
      dropdowns.forEach((dropdown) => {
        const dropdownMenu = dropdown.querySelector(".dropdown-menu");
        if (dropdownMenu) dropdownMenu.classList.remove("active");
      });
    }
  });

  // ==========================================
  // 5. TOGGLE LIGHT / DARK MODE
  // ==========================================
  const themeToggleBtn = document.getElementById("theme-toggle");
  const themeIcon = document.getElementById("theme-icon");

  if (themeToggleBtn && themeIcon) {
    const currentTheme = localStorage.getItem("theme") || "dark";

    // Set awal tema
    if (currentTheme === "light") {
      document.documentElement.setAttribute("data-theme", "light");
      themeIcon.classList.replace("fa-moon", "fa-sun");
    } else {
      document.documentElement.setAttribute("data-theme", "dark");
      themeIcon.classList.replace("fa-sun", "fa-moon");
    }

    // Event Switch Tema
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
  }
});
    </script>

  </body>
</html>