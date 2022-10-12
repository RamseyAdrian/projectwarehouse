<?php
session_start();
include 'db.php';
if ($_SESSION['role_login'] == 'user') {

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
    <title>KP Ombudsman</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <!-- header -->
    <header>
        <div class="container">
            <h1><a href="dashboard.php">KP Ombudsman</a></h1>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="profile.php">Profil</a></li>
                <li><a href="category-data.php">Data Kategori</a></li>
                <li><a href="product-data.php">Data Produk</a></li>
                <li><a href="office-data.php">Perwakilan</a></li>
                <li><a href="admin-data.php">Data Admin</a></li>
                <li><a href="user-data.php">Data User</a></li>
                <li><a href="order-table.php">Pesanan</a></li>
                <li><a href="logout.php">Keluar</a></li>
            </ul>
        </div>
    </header>

    <!-- Content -->
    <div class="section">
        <div class="container">
            <h3>Tambah Data Admin</h3>
            <div class="box">
                <form action="" method="POST">
                    <h4>ID Admin</h4>
                    <input type="text" name="id" class="input-control" required>
                    <h4>Perwakilan</h4>
                    <select name="perwakilan" class="input-control" required>
                        <option value="">--Pilih Perwakilan</option>
                        <?php
                        $perwakilan = mysqli_query($conn, "SELECT * FROM data_office ORDER BY office_id");
                        while ($fa_perwakilan = mysqli_fetch_array($perwakilan)) {
                        ?>
                            <option value="<?php echo $fa_perwakilan['office_id'] ?>"><?php echo $fa_perwakilan['office_name'] ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <h4>Nama Admin</h4>
                    <input type="text" name="nama" class="input-control" required>
                    <h4>Username Akun Admin</h4>
                    <input type="text" name="username" class="input-control" required>
                    <h4>Password Akun Admin</h4>
                    <input type="text" name="pass" class="input-control" required>
                    <h4>Nomor Telfon</h4>
                    <input type="text" name="telp" class="input-control" required>
                    <h4>Email Admin</h4>
                    <input type="text" name="email" class="input-control" required>
                    <h4>Alamat Admin</h4>
                    <input type="text" name="address" class="input-control" required>
                    <input type="submit" name="submit" value="Submit" class="btn">
                </form>
                <?php
                if (isset($_POST['submit'])) {
                    $usernameakun = $_POST['username'];
                    $idakun = $_POST['id'];

                    $cek_data_sama = mysqli_query($conn, "SELECT * FROM data_admin WHERE admin_username = '" . $usernameakun . "' AND admin_id = '" . $idakun . "'");
                    $insert = true;
                    if (mysqli_num_rows($cek_data_sama) > 0) {
                        $after_check = mysqli_fetch_object($cek_data_sama);
                        $insert = false;
                    }


                    if ($insert) {
                        $nama = ucwords($_POST['nama']);
                        $id = $_POST['id'];
                        $username = $_POST['username'];
                        $pass = $_POST['pass'];
                        $telp = $_POST['telp'];
                        $email = $_POST['email'];
                        $address = $_POST['address'];
                        $perwakilan = $_POST['perwakilan'];

                        // $location = $_POST[""];

                        $insert = mysqli_query($conn, "INSERT INTO data_admin VALUES (
                                            '" . $id . "',
                                            '" . $perwakilan . "',
                                            '" . $nama . "',
                                            '" . $username . "',
                                            '" . MD5($pass) . "',
                                            '" . $telp . "',
                                            '" . $email . "',
                                            '" . $address . "'
                                            ) ");
                        echo '<script>Swal.fire({
                                title: "Berhasil Tambah Admin !",
                                text: "Klik OK Untuk Lanjut.",
                                icon: "success"
                              },
                              function(){
                                window.location="user-data.php"
                              });
                            </script>';
                    } else {
                        echo '<script>Swal.fire({
                            title: "ID tidak tersedia",
                            text: "Input ID selain ini",
                            icon: "error"
                        },
                        function(){
                            window.location="user-data.php"
                        })</script>';
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