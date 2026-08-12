<?php

include "config/koneksi.php";

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = '$username'";

    $result = mysqli_query($conn, $query);

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
</head>

<body>

    <h1>Login Admin Mahatar</h1>

    <form method="POST">

        <label>Username</label>
        <br>
        <input type="text" name="username">

        <br><br>

        <label>Password</label>
        <br>
        <input type="password" name="password">

        <br><br>

        <button type="submit">Login</button>

    </form>

</body>
</html>