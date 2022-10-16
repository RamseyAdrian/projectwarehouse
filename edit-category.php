<?php
session_start();
include 'db.php';
if ($_SESSION['role_login'] == 'user' || $_SESSION['role_login'] == 'admin') {

    echo '<script>window.location="logout.php"</script>';
}

$kategori = mysqli_query($conn, "SELECT * FROM data_category WHERE category_id = '" . $_GET['id'] . "' ");
if (mysqli_num_rows($kategori) == 0) {
    echo '<script>window.location="category-data.php"</script>';
}
$k = mysqli_fetch_object($kategori);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KP Ombudsman</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
    <!-- header -->
    <header>
        <div class="container">
            <h1><a href="dashboard.php"><img style="width: 70px ; margin-bottom :-10px ;" src="img/logo-ombudsman2.png" alt=""> Gudang Ombudsman</a></h1>
            <ul style="margin-top: 20px ;">
                <li><a href="dashboard.php">Dashboard </a></li>
                <li><a href="profile.php">Profil</a></li>
                <li><a href="category-data.php">Kategori</a></li>
                <li><a href="product-data.php">Produk</a></li>
                <li><a href="unit-data.php">Satuan</a></li>
                <li><a href="office-data.php">Perwakilan</a></li>
                <li><a href="admin-data.php">Admin</a></li>
                <li><a href="user-data.php">User</a></li>
                <li><a href="order-table.php">Pesanan</a></li>
                <li><a href="logout.php">Keluar</a></li>
            </ul>
        </div>
    </header>

    <!-- Content -->
    <div class="section">
        <div class="container">
            <h3>Edit Data Kategori</h3>
            <div class="box">
                <form action="" method="POST">
                    <h4>ID Kategori</h4>
                    <input type="text" name="id" placeholder="ID Kategori" class="input-control" value="<?php echo $k->category_id ?>" readonly>
                    <h4>Nama Kategori</h4>
                    <input type="text" name="nama" placeholder="Nama Kategori" class="input-control" value="<?php echo $k->category_name ?>" required>
                    <input type="submit" name="submit" value="Submit" class="btn">
                </form>
                <?php
                if (isset($_POST['submit'])) {
                    $nama = ucwords($_POST['nama']);
                    $id = $_POST['id'];

                    $update = mysqli_query($conn, "UPDATE data_category SET  
                                           category_id = '" . $id . "', 
                                           category_name = '" . $nama . "' 
                                           WHERE category_id = '" . $k->category_id . "'
                                           ");
                    if ($update) {
                        echo '<script>Swal.fire({
                            title: "Berhasil Edit Kategori",
                            text: "Klik OK Untuk Lanjut",
                            icon: "success"
                          }).then(function() {
                            window.location = "category-data.php";
                          });
                        </script>';
                    } else {
                        echo 'gagal' . mysqli_error($conn);
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <small>Copyright &copy; 2022 - Universitas Pertamina</small>
        </div>
    </footer>

</body>

</html>