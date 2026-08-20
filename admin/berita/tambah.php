<?php

session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: ../../login.php");
    exit;
}

include "../../config/koneksi.php";

$query_kategori = "SELECT * FROM kategori_berita ORDER BY nama_kategori ASC";

$result_kategori = mysqli_query($conn, $query_kategori);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $id_kategori = $_POST['id_kategori'];

    echo "<pre>";
    print_r($_FILES['gambar']);
    echo "</pre>";

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO berita (judul, isi, id_kategori, tanggal)
         VALUES (?, ?, ?, NOW())"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "sss",
        $judul,
        $isi,
        $id_kategori
    );

    if (mysqli_stmt_execute($stmt)) {

         echo "Berita berhasil ditambahkan!";

    } else {

        echo "Berita gagal ditambahkan: "
             . mysqli_error($conn);

    }

}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Berita</title>

</head>

<body>

    <h1>Tambah Berita</h1>

    <form method="POST" enctype="multipart/form-data">

        <label>Judul Berita</label>
        <br>

        <input
            type="text"
            name="judul"
        >

        <br><br>

        <label>Kategori</label>
<br>

<select name="id_kategori">

    <option value="">
        -- Pilih Kategori --
    </option>

    <?php while ($kategori = mysqli_fetch_assoc($result_kategori)) { ?>

        <option value="<?php echo $kategori['id_kategori']; ?>">
            <?php echo $kategori['nama_kategori']; ?>
        </option>

    <?php } ?>

</select>

<br><br>

        <label>Isi Berita</label>
        <br>

        <textarea
            name="isi"
            rows="10"
            cols="50"
        ></textarea>

        <br><br>

<label>Foto Berita</label>
<br>

<input
    type="file"
    name="gambar"
    accept="image/*"
>

<br><br>

        <button type="submit">
            Simpan Berita
        </button>

    </form>

    <br>

    <a href="index.php">
        Kembali ke Daftar Berita
    </a>

</body>

</html>