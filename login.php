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

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin Mahatar</title>
  
  <!-- Fonts -->
  <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    />
    <link rel="stylesheet" href="assets/style/WebMahatarAMNI (STYLE).css" />
  
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
        >
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
        >
      </div>

      <button type="submit" class="btn-login">Login</button>
    </form>
  </main>

</body>
</html>