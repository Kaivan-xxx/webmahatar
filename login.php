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

    if (mysqli_num_rows($result) > 0) {

        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {

            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['username'] = $user['username'];

            header("Location: admin/dashboard.php");
            exit;
            
        } else {

            echo "Password salah!";

        }

    } else {

        echo "Username tidak ditemukan!";

    }
}

?>

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
        justify-content: center;
        align-items: center;
      }

      a {
        text-decoration: none;
        color: inherit;
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
    </style>
  </head>
  <body>
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
      </form>
    </main>
  </body>
</html>
