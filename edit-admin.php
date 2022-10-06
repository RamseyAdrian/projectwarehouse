<?php
session_start();
include 'db.php';
if ($_SESSION['role_login'] == 'user') {

    echo '<script>window.location="logout.php"</script>';
} else if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}

$user = mysqli_query($conn, "SELECT * FROM data_user WHERE user_id = '" . $_GET['id'] . "' ");
if (mysqli_num_rows($user) == 0) {
    echo '<script>window.location="user-data.php"</script>';
}
$u = mysqli_fetch_object($user);

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
</head>

<body>
    <!-- header -->
    <header>
        <div class="container">
            <h1><a href="dashboard.php">KP Ombudsman</a></h1>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="profile.php">Profil</a></li>
                <li><a href="product-data.php">Data Produk</a></li>
                <li><a href="user-data.php">Data User</a></li>
                <li><a href="order-table.php">Pesanan</a></li>
                <li><a href="logout.php">Keluar</a></li>
            </ul>
        </div>
    </header>

    <!-- Content -->
    <div class="section">
        <div class="container">
            <h3>Edit Data User</h3>
            <div class="box">
                <form action="" method="POST">
                    <h4>ID User</h4>
                    <input type="text" name="id" class="input-control" value="<?php echo $u->user_id ?>" required>
                    <h4>Nama User</h4>
                    <input type="text" name="nama" class="input-control" value="<?php echo $u->user_name ?>" required>
                    <h4>Username Akun</h4>
                    <input type="text" name="username" class="input-control" value="<?php echo $u->user_username ?>" required>
                    <h4>Password Akun</h4>
                    <input type="text" name="pass" class="input-control" value="<?php echo $u->user_password ?>" required>
                    <h4>Nomor Telfon</h4>
                    <input type="text" name="telp" class="input-control" value="<?php echo $u->user_telp ?>" required>
                    <h4>Email User</h4>
                    <input type="text" name="email" class="input-control" value="<?php echo $u->user_email ?>" required>
                    <h4>Alamat User</h4>
                    <input type="text" name="address" class="input-control" value="<?php echo $u->user_address ?>" required>
                    <input type="submit" name="submit" value="Submit" class="btn">
                </form>
                <?php
                if (isset($_POST['submit'])) {
                    $nama = ucwords($_POST['nama']);
                    $id = $_POST['id'];
                    $username = $_POST['username'];
                    $pass = $_POST['pass'];
                    $telp = $_POST['telp'];
                    $email = $_POST['email'];
                    $address = $_POST['address'];
                    $location = $_POST[""];

                    $update = mysqli_query($conn, "UPDATE data_user SET
                                            user_id = '" . $id . "',
                                            user_name = '" . $nama . "',
                                            user_username = '" . $username . "',
                                            user_password = '" . MD5($pass) . "',
                                            user_telp = '" . $telp . "',
                                            user_email = '" . $email . "',
                                            user_address = '" . $address . "'
                                            WHERE user_id = '" . $u->user_id . "'
                                            ");
                    if ($update) {
                        echo '<script>alert("Edit data berhasil")</script>';
                        echo '<script>window.location="user-data.php"</script>';
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