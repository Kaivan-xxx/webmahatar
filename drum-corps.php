<?php

include "config/koneksi.php";

$id_kategori = 2;

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
    <title>Drum Corps - Kemahataran AMNI</title>
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
        <h1 class="header-title">Drum Corps Gita Swara AMNI</h1>
        <p class="header-subtitle">
          Korps musik kebanggaan Kemahataran AMNI Semarang pembentuk kekompakan
          & musikalitas
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
            alt="Drum Corps AMNI"
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
              Tentang Drum Corps
            </h3>
            <p
              style="
                color: var(--text-secondary);
                line-height: 1.7;
                margin-bottom: 15px;
              "
            >
              Drum Corps Gita Swara AMNI adalah lambang kebanggaan dan presisi
              kedisiplinan taruna. Memadukan keahlian instrumen tiup,
              perkusional, serta aksi <em>color guard</em> dalam pertunjukkan
              publik dan kompetisi nasional.
            </p>
            <ul
              style="list-style: none; padding: 0; color: var(--text-primary)"
            >
              <li style="margin-bottom: 8px">
                <i
                  class="fa-solid fa-music"
                  style="color: var(--accent-cyan); width: 25px"
                ></i>
                <strong>Seksi Utama:</strong> Brass, Percussion, & Color Guard
              </li>
              <li style="margin-bottom: 8px">
                <i
                  class="fa-solid fa-calendar-check"
                  style="color: var(--accent-cyan); width: 25px"
                ></i>
                <strong>Jadwal Latihan:</strong> Selasa & Kamis (15.30 WIB -
                Selesai)
              </li>
              <li style="margin-bottom: 8px">
                <i
                  class="fa-solid fa-location-dot"
                  style="color: var(--accent-cyan); width: 25px"
                ></i>
                <strong>Lokasi Latihan:</strong> Lapangan Utama AMNI
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- PROGRAM KERJA -->
      <div class="profile-card" style="margin-bottom: 30px">
        <h3 style="font-size: 1.2rem; margin-bottom: 15px">
          <i class="fa-solid fa-star" style="color: var(--accent-blue)"></i>
          Agenda & Penampilan Utama
        </h3>
        <div
          style="
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
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
            <h4 style="color: var(--accent-cyan); margin-bottom: 8px">
              Upacara Wisuda & Bon Voyage
            </h4>
            <p style="font-size: 0.88rem; color: var(--text-secondary)">
              Mengisi korps musik utama pada pelepasan perwira pelayaran niaga.
            </p>
          </div>
          <div
            style="
              padding: 15px;
              border: var(--border-glass);
              border-radius: var(--radius-sm);
              background: rgba(255, 255, 255, 0.02);
            "
          >
            <h4 style="color: var(--accent-cyan); margin-bottom: 8px">
              Parade Hari Besar Nasional
            </h4>
            <p style="font-size: 0.88rem; color: var(--text-secondary)">
              Mewakili akademi pada pawai budaya dan parade kedinasan di Kota
              Semarang.
            </p>
          </div>
        </div>
      </div>

      <!-- STRUKTUR TIM DRUM CORPS -->
      <div class="profile-card" style="margin-bottom: 30px">
        <h3 style="font-size: 1.2rem; margin-bottom: 15px">
          <i
            class="fa-solid fa-users-gear"
            style="color: var(--accent-blue)"
          ></i>
          Tim Pelatih & Penanggung Jawab
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
              Penanggung Jawab
            </h4>
            <p style="color: var(--text-primary); font-weight: bold; margin: 0">
              Setyo Nugroho, M.T.
            </p>
            <span style="font-size: 0.8rem; color: var(--text-secondary)"
              >Pembina Utama</span
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
              Field Commander
            </h4>
            <p style="color: var(--text-primary); font-weight: bold; margin: 0">
              Taruna Dewa Saputra
            </p>
            <span style="font-size: 0.8rem; color: var(--text-secondary)"
              >NPT. 22020011</span
            >
          </div>
        </div>
      </div>

      <!-- BERITA & AGENDA DRUM CORPS -->

<div class="news-grid" id="newsGrid">
        <!-- TEMPLATE KARTU BERITA KOSONG (Silakan duplikasi & isi saat menambah berita baru) -->
      <?php while ($kegiatan = mysqli_fetch_assoc($result)) { ?>
      <article  class="news-card"
          data-category="<?php echo strtolower($kegiatan['id_kategori_kegiatan']); ?>">
       <div class="news-thumb-wrapper">

    <img 
        src="uploads/<?php echo $kegiatan['gambar']; ?>"
        class="news-thumb-img"
        alt="<?php echo $kegiatan['judul']; ?>"
     >

</div>
        <div class="news-content">
          <div class="news-meta">
            <span><i class="fa-regular fa-calendar"></i> <?php echo $kegiatan['tanggal']; ?></span>
          </div>
          <h3 class="news-title"> <?php echo $kegiatan['judul']; ?></h3>

          <div class="news-full-body" style="display:none;">
            <p><?= nl2br(htmlspecialchars($kegiatan['isi'])); ?></p>
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
