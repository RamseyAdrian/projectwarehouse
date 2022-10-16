<?php
session_start();
include 'db.php';
if ($_SESSION['role_login'] == 'user') {

    echo '<script>window.location="logout.php"</script>';
} else if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}

$query_admin = mysqli_query($conn, "SELECT * FROM data_admin WHERE admin_id = '" . $_GET['id'] . "' ");
if (mysqli_num_rows($query_admin) == 0) {
    echo '<script>window.location="admin-data.php"</script>';
}
$fo_admin = mysqli_fetch_object($query_admin);

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

    <!-- Content -->
    <div class="section">
        <div class="container">
            <h3>Edit Data Admin</h3>
            <div class="box">
                <form action="" method="POST">
                    <h4>ID Admin</h4>
                    <input type="text" name="id" class="input-control" value="<?php echo $fo_admin->admin_id ?>" readonly>
                    <h4>Nama Admin</h4>
                    <input type="text" name="nama" class="input-control" value="<?php echo $fo_admin->admin_name ?>" required>
                    <h4>Username Akun</h4>
                    <input type="text" name="username" class="input-control" value="<?php echo $fo_admin->admin_username ?>" required>
                    <h4>Nomor Telfon</h4>
                    <input type="text" name="telp" class="input-control" value="<?php echo $fo_admin->admin_telp ?>" required>
                    <h4>Email Admin</h4>
                    <input type="text" name="email" class="input-control" value="<?php echo $fo_admin->admin_email ?>" required>
                    <h4>Alamat Admin</h4>
                    <input type="text" name="address" class="input-control" value="<?php echo $fo_admin->admin_address ?>" required>
                    <input type="submit" name="submit" value="Submit" class="btn">
                </form>
                <?php
                if (isset($_POST['submit'])) {
                    $nama = ucwords($_POST['nama']);
                    $username = $_POST['username'];
                    $telp = $_POST['telp'];
                    $email = $_POST['email'];
                    $address = $_POST['address'];


                    $update = mysqli_query($conn, "UPDATE data_admin SET                                            
                                            admin_name = '" . $nama . "',
                                            admin_username = '" . $username . "',                                            
                                            admin_telp = '" . $telp . "',
                                            admin_email = '" . $email . "',
                                            admin_address = '" . $address . "'
                                            WHERE admin_id = '" . $fo_admin->admin_id . "'
                                            ");
                    if ($update) {
                        echo '<script>Swal.fire({
                            title: "Berhasil Edit Admin",
                            text: "Klik OK Untuk Lanjut",
                            icon: "success"
                          }).then(function() {
                            window.location = "admin-data.php";
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
            <small>Copyright &copy; 2022 - Ramsey Adrian</small>
        </div>
    </footer>

</body>

</html>