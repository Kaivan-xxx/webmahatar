<?php

include "config/koneksi.php";

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];

      $stmt = mysqli_prepare(
        $conn,
        "SELECT * FROM users WHERE username = ?"
    );

    mysqli_stmt_bind_param($stmt, "s", $username);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) >
0) { $user = mysqli_fetch_assoc($result); if (password_verify($password,
$user['password'])) { $_SESSION['id_user'] = $user['id_user'];
$_SESSION['username'] = $user['username']; header("Location:admin/dashboard.php"); exit; } else { echo "Password salah!"; } } else { echo"Username tidak ditemukan!"; } } ?>

<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Admin Mahatar</title>

    <!-- Fonts -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    />
    <style>
      :root {
        --bg-dark: #071325;
        --bg-card: rgba(13, 30, 56, 0.75);

        --text-primary: #f0f6ff;
        --text-secondary: #94a3b8;

        --accent-blue: #0284c7;
        --accent-cyan: #38bdf8;
        --accent-gold: #f59e0b;

        --gradient-accent: linear-gradient(135deg, #0284c7 0%, #38bdf8 100%);

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

        --border-glass: 1px solid rgba(255, 255, 255, 0.08);
        --border-active: 1px solid rgba(56, 189, 248, 0.4);
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
          radial-gradient(
            circle at 50% 35%,
            rgba(9, 148, 207, 0.57) 0%,
            transparent 50%
          ),
          radial-gradient(
            circle at 80% 80%,
            rgba(245, 159, 11, 0.24) 0%,
            transparent 40%
          ),
          radial-gradient(
            circle at 20% 20%,
            rgba(14, 164, 233, 0.3) 0%,
            transparent 40%
          ),
          linear-gradient(180deg, #f0f4f9 0%, #e2e8f0 100%);
      }

      /* ==========================================
   RESET & LAYOUT BASE
   ========================================== */
      * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: "Plus Jakarta Sans", sans-serif;
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
      .login-container {
        width: 100%;
        max-width: 400px;
        background: var(--bg-card);
        backdrop-filter: var(--glass-backdrop);
        -webkit-backdrop-filter: var(--glass-backdrop);
        border: var(--border-glass);
        border-radius: var(--radius-lg);
        padding: 40px 32px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
        align-item: center;
      }

      .login-header {
        text-align: center;
        margin-bottom: 32px;
      }

      .login-title {
        font-size: 1.8rem;
        font-weight: 800;
        background: var(--gradient-accent);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 8px;
      }

      .login-subtitle {
        font-size: 0.9rem;
        color: var(--text-secondary);
      }

      .form-group {
        margin-bottom: 20px;
        display: flex;
        flex-direction: column;
        gap: 8px;
      }

      .form-label {
        font-size: 0.88rem;
        font-weight: 600;
        color: var(--text-secondary);
      }

      .form-input {
        width: 100%;
        background: rgba(255, 255, 255, 0.04);
        border: var(--border-glass);
        border-radius: var(--radius-sm);
        padding: 12px 16px;
        color: var(--text-primary);
        font-size: 0.95rem;
        outline: none;
        transition: var(--transition-smooth);
      }

      .form-input:focus {
        border: var(--border-active);
        background: rgba(255, 255, 255, 0.08);
        box-shadow: 0 0 15px rgba(56, 189, 248, 0.2);
      }

      .btn-login {
        width: 100%;
        padding: 14px;
        margin-top: 10px;
        background: var(--gradient-accent);
        color: #ffffff;
        border: none;
        border-radius: 100px;
        font-size: 0.95rem;
        font-weight: 700;
        cursor: pointer;
        transition: var(--transition-smooth);
        box-shadow: 0 10px 25px rgba(56, 189, 248, 0.3);
      }

      .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(56, 189, 248, 0.45);
      }

      .btn-back {
        display: block;
        width: 100%;
        padding: 14px;
        margin-top: 12px;
        background: rgba(255, 255, 255, 0.05);
        color: var(--text-primary);
        border: var(--border-glass);
        border-radius: 100px;
        font-size: 0.95rem;
        font-weight: 700;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
        transition: var(--transition-smooth);
      }

      .btn-back:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: translateY(-2px);
      }

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
    </style>
  </head>
  <body>
    <button
      id="themeToggle"
      class="btn-theme-toggle"
      type="button"
      aria-label="Toggle Theme"
    >
      <i id="themeIcon" class="fa-solid fa-moon"></i>
    </button>
    <main class="login-container">
      <div class="login-header">
        <h1 class="login-title">Admin Mahatar</h1>
        <p class="login-subtitle">Silakan masuk untuk mengelola portal</p>
      </div>

      <form method="POST" action="">
        <div class="form-group">
          <label for="username" class="form-label">Username</label>
          <input
            type="text"
            id="username"
            name="username"
            class="form-input"
            placeholder="Masukkan username"
            required
            autocomplete="username"
          />
        </div>

        <div class="form-group">
          <label for="password" class="form-label">Password</label>
          <input
            type="password"
            id="password"
            name="password"
            class="form-input"
            placeholder="••••••••"
            required
            autocomplete="current-password"
          />
        </div>

        <button type="submit" class="btn-login">Login</button>
        <a href="news.php" class="btn-back">Kembali</a>
      </form>
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