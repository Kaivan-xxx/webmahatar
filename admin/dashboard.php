<?php
    session_start();

    if (!isset($_SESSION['id_user'])) {
        header("Location: ../login.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin Mahatar</title>

</head>

<body>

    <h1>Dashboard Admin Mahatar</h1>

    <p>
        Selamat datang,
        <?php echo $_SESSION['username']; ?>
    </p>

</body>

</html>