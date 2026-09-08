<?php
    session_start();

    if (!isset($_SESSION['id_user'])) {
        header("Location: ../login.php");
        exit;
    }
?>

<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Admin Mahatar</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
      rel="stylesheet"
    />
    <!-- FontAwesome untuk Icon -->
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

      /* ==========================================
   10. ADMIN & LOGIN MODULE
   ========================================== */
      .dashboard-container {
        width: 100%;
        background: var(--bg-card);
        backdrop-filter: var(--glass-backdrop);
        -webkit-backdrop-filter: var(--glass-backdrop);
        border: var(--border-glass);
        border-radius: var(--radius-lg);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
        margin: auto;
      }

      .dashboard-container {
        max-width: 900px;
        padding: 40px;
      }

      .dashboard-header {
        text-align: center;
        margin-bottom: 32px;
      }

      .dashboard-title {
        font-size: 2.2rem;
        font-weight: 800;
        background: var(--gradient-accent);
        background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 12px;
      }

      .alert-banner {
        background: rgba(245, 158, 11, 0.1);
        border: 1px solid rgba(245, 158, 11, 0.3);
        color: #fbbf24;
        padding: 16px 20px;
        border-radius: var(--radius-sm);
        font-size: 0.88rem;
        line-height: 1.5;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 36px;
      }

      .admin-menu-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        list-style: none;
        margin-bottom: 36px;
      }

      .menu-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 14px;
        padding: 30px 20px;
        background: rgba(255, 255, 255, 0.02);
        border: var(--border-glass);
        border-radius: var(--radius-md);
        text-decoration: none;
        color: var(--text-primary);
        font-weight: 600;
        font-size: 1.05rem;
        transition: var(--transition-smooth);
      }

      .menu-card i {
        font-size: 2rem;
        color: var(--accent-blue);
        transition: var(--transition-smooth);
      }

      .menu-card:hover {
        background: var(--bg-card-hover);
        border: var(--border-active);
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(56, 189, 248, 0.15);
      }

      .menu-card:hover i {
        transform: scale(1.1);
      }

      .dashboard-footer {
        display: flex;
        justify-content: flex-end;
        border-top: var(--border-glass);
        padding-top: 24px;
      }

      .btn-logout {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 28px;
        background: rgba(239, 68, 68, 0.15);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #fca5a5;
        text-decoration: none;
        border-radius: 100px;
        font-weight: 600;
        font-size: 0.95rem;
        transition: var(--transition-smooth);
      }

      .btn-logout:hover {
        background: #ef4444;
        color: #ffffff;
        border-color: #ef4444;
        box-shadow: 0 8px 20px rgba(239, 68, 68, 0.3);
        transform: translateY(-2px);
      }
      .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 20px;
        color: var(--text-primary);
      }

      @media (max-width: 576px) {
        .dashboard-container {
          padding: 24px;
        }

        .dashboard-title {
          font-size: 1.75rem;
        }

        .alert-banner {
          flex-direction: column;
          text-align: center;
        }

        .dashboard-footer {
          justify-content: center;
        }

        .btn-logout {
          width: 100%;
          justify-content: center;
        }
      }
    </style>
  </head>
  <body>
    <main class="dashboard-container">
      <header class="dashboard-header">
        <h1 class="dashboard-title">Dashboard Admin Mahatar</h1>
      </header>

      <div class="alert-banner">
        <i
          class="fa-solid fa-triangle-exclamation"
          style="font-size: 1.2rem"
        ></i>
        <span
          ><strong>Ingat!</strong> Selalu logout setelah menggunakan dashboard
          admin untuk menghindari akses yang tidak diinginkan.</span
        >
      </div>

      <h2 class="section-title">Menu Utama</h2>

      <ul class="admin-menu-grid">
        <li>
          <a href="berita/index.php" class="menu-card">
            <i class="fa-solid fa-newspaper"></i>
            <span>Kelola Berita Dan Prestasi</span>
          </a>
        </li>

        <li>
          <a href="galeri/index.php" class="menu-card">
            <i class="fa-solid fa-images"></i>
            <span>Kelola Galeri</span>
          </a>
        </li>

      </ul>

      <div class="dashboard-footer">
        <a href="logout.php" class="btn-logout">
          <i class="fa-solid fa-right-from-bracket"></i> Logout
        </a>
      </div>
    </main>
  </body>
</html>
