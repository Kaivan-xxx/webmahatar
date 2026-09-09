<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Penanaman Mangrove - Kemahataran AMNI</title>
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

    <!-- MAIN CONTENT -->
    <main class="news-container">
      <div class="header-section">
        <h1 class="header-title">Aksi Konservasi Penanaman Mangrove</h1>
        <p class="header-subtitle">
          Wujud nyata kepedulian lingkungan dan pelestarian ekosistem pesisir
          laut Semarang
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
            alt="Aksi Mangrove AMNI"
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
              Tentang Program Konservasi
            </h3>
            <p
              style="
                color: var(--text-secondary);
                line-height: 1.7;
                margin-bottom: 15px;
              "
            >
              Aksi kepedulian lingkungan laut berkala yang melibatkan seluruh
              taruna/i baru. Berfokus pada pencegahan abrasi pesisir pantai
              utara Jawa serta edukasi pentingnya menjaga ekosistem mangrove
              bagi masa depan kemaritiman.
            </p>
            <ul
              style="list-style: none; padding: 0; color: var(--text-primary)"
            >
              <li style="margin-bottom: 8px">
                <i
                  class="fa-solid fa-tree"
                  style="color: var(--accent-cyan); width: 25px"
                ></i>
                <strong>Fokus Aksi:</strong> Penanaman Bibit, Pembibitan, &
                Pembersihan Pesisir
              </li>
              <li style="margin-bottom: 8px">
                <i
                  class="fa-solid fa-calendar-check"
                  style="color: var(--accent-cyan); width: 25px"
                ></i>
                <strong>Pelaksanaan:</strong> Program Agenda Semesteran
              </li>
              <li style="margin-bottom: 8px">
                <i
                  class="fa-solid fa-location-dot"
                  style="color: var(--accent-cyan); width: 25px"
                ></i>
                <strong>Lokasi:</strong> Kawasan Pesisir Maritim Semarang
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- STRUKTUR TIM KONSORSIUM MANGROVE -->
      <div class="profile-card" style="margin-bottom: 30px">
        <h3 style="font-size: 1.2rem; margin-bottom: 15px">
          <i
            class="fa-solid fa-users-gear"
            style="color: var(--accent-blue)"
          ></i>
          Panitia Konservasi Lingkungan
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
              Ketua Pelaksana
            </h4>
            <p style="color: var(--text-primary); font-weight: bold; margin: 0">
              Dian Anggraini
            </p>
            <span style="font-size: 0.8rem; color: var(--text-secondary)"
              >NPT. 23010150</span
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
              Koordinator Lapangan
            </h4>
            <p style="color: var(--text-primary); font-weight: bold; margin: 0">
              Fajar Setiadi
            </p>
            <span style="font-size: 0.8rem; color: var(--text-secondary)"
              >NPT. 23010182</span
            >
          </div>
        </div>
      </div>

      <!-- BERITA & AGENDA MANGROVE -->
      <div class="profile-card">
        <h3 style="font-size: 1.2rem; margin-bottom: 15px">
          <i
            class="fa-solid fa-newspaper"
            style="color: var(--accent-blue)"
          ></i>
          Berita & Aksi Konservasi
        </h3>
        <div style="display: flex; flex-direction: column; gap: 12px">
          <div
            style="
              padding: 12px;
              border-left: 3px solid var(--accent-cyan);
              background: rgba(255, 255, 255, 0.01);
            "
          >
            <span style="font-size: 0.8rem; color: var(--accent-cyan)"
              ><i class="fa-solid fa-calendar"></i> 05 Juni 2026</span
            >
            <h4 style="margin: 5px 0; color: var(--text-primary)">
              Penanaman 1.000 Bibit Mangrove Hari Lingkungan Hidup
            </h4>
            <p
              style="
                font-size: 0.85rem;
                color: var(--text-secondary);
                margin: 0;
              "
            >
              Ratusan taruna/i AMNI turun ke Pesisir Mangunharjo untuk melakukan
              penanaman bibit mangrove secara masif.
            </p>
          </div>
        </div>
      </div>
    </main>

    <footer>
      <p>&copy; 2026 Kemahataran AMNI Semarang. All rights reserved.</p>
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
    </script>
  </body>
</html>
