<?php
session_start();
include 'db.php';
// Kondisi Supaya User, Admin, & Non User tidak dapat akses page ini
if ($_SESSION['role_login'] == 'user' || $_SESSION['role_login'] == 'admin') {
    echo '<script>window.location="logout.php"</script>';
} else if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gudang Ombudsman</title>
    <!--------------------- CSS ------------------------------------->
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <!--------------------- Font Used ----------------------------->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
    <!--------------------- Sweet Alert CDN ----------------------------->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css" rel="stylesheet" />
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <!---------------------- header ----------------------------------->
    <header>
        <div class="container">
            <h1><a href="dashboard.php"><img style="width: 70px ; margin-bottom :-10px ;" src="img/logo-ombudsman2.png" alt=""> Gudang Ombudsman</a></h1>
            <ul style="margin-top: 20px ;">
                <li><a href="dashboard.php">Dashboard </a></li>
                <li><a href="profile.php">Profil</a></li>
                <li><a href="category-data.php">Kategori</a></li>
                <li><a href="product-data.php">Barang</a></li>
                <li><a href="unit-data.php">Satuan</a></li>
                <li><a href="office-data.php">Perwakilan</a></li>
                <li><a href="admin-data.php">Admin</a></li>
                <li><a href="user-data.php">User</a></li>
                <li><a href="order-table.php">Pesanan</a></li>
                <li><a href="logout.php">Keluar</a></li>
            </ul>
        </div>
    </header>

    <!---------------------- Content ----------------------------------->
    <div class="section">
        <div class="container">
            <h2>Tambah Kategori</h2>
            <div class="box">
                <form action="" method="POST">
                    <!-- <h4>ID Kategori</h4> -->
                    <input type="hidden" name="id" placeholder="ID Kategori" class="input-control" value="<?php echo rand() ?>" readonly>
                    <h4>Nama Kategori</h4>
                    <input type="text" name="nama" placeholder="Nama Kategori" class="input-control" required>
                    <input type="submit" name="submit" value="Submit" class="btn">
                </form>
                <?php
                if (isset($_POST['submit'])) {
                    $nama = ucwords($_POST['nama']);
                    $id = $_POST['id'];
                    $approve = true;

                    $cek_kategori = mysqli_query($conn, "SELECT * FROM data_category WHERE category_name = '" . $nama . "' ");
                    if (mysqli_num_rows($cek_kategori) > 0) {
                        $approve = false;
                    }

                    if ($approve) {
                        $insert = mysqli_query($conn, "INSERT INTO data_category VALUES (
                            '" . $id . "',
                            '" . $nama . "'
                            ) ");

                        echo '<script>Swal.fire({
                            title: "Berhasil Tambah Kategori",
                            text: "Klik OK Untuk Lanjut",
                            icon: "success"
                          }).then(function() {
                            window.location = "category-data.php";
                          });
                        </script>';
                    } else {
                        echo '<script>Swal.fire({
                            title: "Kategori Sudah Tersedia",
                            text: "Masukkan Kategori Lain",
                            icon: "warning"
                          });
                        </script>';
                    }
                }
                ?>
            </div>
        </div>
    </div>

</body>

</html>