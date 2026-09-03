<?php

include "config/koneksi.php";

$query = "
    SELECT berita.*, kategori_berita.nama_kategori
    FROM berita
    JOIN kategori_berita
        ON berita.id_kategori = kategori_berita.id_kategori
    ORDER BY berita.tanggal DESC
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


/* ==========================================
   2. RESET & GLOBAL STYLES
   ========================================== */</style>
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
       <button class="filter-btn active" data-filter="berita">
    Berita
</button>

<button class="filter-btn" data-filter="prestasi">
    Prestasi
</button>
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
          <h3>Belum Ada Berita Atau Prestasi</h3>
          <p>
            Belum ada Prestasi atau Berita terbaru yang dipublikasikan untuk
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
      document.addEventListener("DOMContentLoaded", () => {
  // ==========================================
  // 1. KONTROL EMPTY STATE (AUTOCHECK)
  // ==========================================
  const newsGrid = document.querySelector(".news-grid");
  const emptyState = document.querySelector(".news-empty-state");

  function checkEmptyState() {
    if (!newsGrid || !emptyState) return;

    // Ambil semua kartu berita yang sedang KELIHATAN (display != none)
    const cards = newsGrid.querySelectorAll(".news-card");
    let visibleCount = 0;

    cards.forEach((card) => {
      if (window.getComputedStyle(card).display !== "none") {
        visibleCount++;
      }
    });

    // Jika kartu aktif ada, sembunyikan empty state. Jika 0, tampilkan!
    if (visibleCount > 0) {
      emptyState.style.setProperty("display", "none", "important");
    } else {
      emptyState.style.setProperty("display", "block", "important");
    }
  }

  // Jalankan cek pertama kali saat halaman dimuat
  checkEmptyState();

  // ==========================================
  // 2. LOGIKA FILTER CATEGORY (BERITA / PRESTASI)
  // ==========================================
  const filterBtns = document.querySelectorAll(".filter-btn");

  filterBtns.forEach((btn) => {
    btn.addEventListener("click", function () {
      // Ubah tombol aktif
      filterBtns.forEach((b) => b.classList.remove("active"));
      this.classList.add("active");

      const category = this.getAttribute("data-filter");
      const cards = document.querySelectorAll(".news-card");

      cards.forEach((card) => {
        if (category === "all" || card.classList.contains(category)) {
          card.style.display = "flex";
        } else {
          card.style.display = "none";
        }
      });

      // Panggil pengecekan empty state setiap kali kategori difilter
      checkEmptyState();
    });
  });

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
      const bodyContent = card.querySelector(".news-full-body")?.innerHTML || card.querySelector(".news-excerpt")?.innerHTML || "";

      const modalBadge = document.getElementById("modalBadge");
      if (modalBadge) {
        modalBadge.innerHTML = "";
        if (badge) modalBadge.appendChild(badge);
      }

      if (document.getElementById("modalTitle")) document.getElementById("modalTitle").innerText = title;
      if (document.getElementById("modalMeta")) document.getElementById("modalMeta").innerHTML = meta;
      if (document.getElementById("modalImg")) document.getElementById("modalImg").src = imgSrc;
      if (document.getElementById("modalBodyText")) document.getElementById("modalBodyText").innerHTML = bodyContent;

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
  window.addEventListener("click", (e) => {
    if (e.target === newsModal) closeModal();
  });
});
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

            const defaultFilter = "berita";

      newsCards.forEach((card) => {
        if (card.getAttribute("data-category") === defaultFilter) {
          card.style.display = "flex";
        } else {
          card.style.display = "none";
        }
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
  </script>
  </body>
</html>
