<?php
session_start();
include 'db.php';
// Kondisi Supaya User, Admin, & Non User tidak dapat akses page ini
if ($_SESSION['role_login'] == 'user' && $_SESSION['role_login'] == 'admin') {
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
            <h3>Tambah Data Admin</h3>
            <div class="box">
                <form action="" method="POST">
                    <h4>ID Admin</h4>
                    <?php $admin_id = rand() ?>
                    <input type="text" name="id" value="<?php echo $admin_id ?>" class="input-control" required>
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

                    $cek_data_sama = mysqli_query($conn, "SELECT * FROM data_admin WHERE admin_username = '" . $usernameakun . "' ");
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
                            title: "Berhasil Tambah Admin",
                            text: "Klik OK Untuk Lanjut",
                            icon: "success"
                          }).then(function() {
                            window.location = "admin-data.php";
                          });
                        </script>';
                    } else {
                        echo '<script>Swal.fire({
                            title: "Username Tidak Tersedia",
                            text: "Cari Username Lain",
                            icon: "error"
                          })
                        </script>';
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <!---------------------- Footer ----------------------------------->

    <div class="footer-dark">
        <footer>
            <div class="container">
                <div class="row" style="display: flex ;">
                    <div class="col-md-6 item text" style="margin-right: 90px ;">
                        <h3>Ombudsman RI</h3>
                        <p>Kantor Pusat <br>
                            Jl. HR. Rasuna Said Kav. C-19 Kuningan, Jakarta Selatan 12920</p>
                    </div>
                    <div class="col-sm-6 col-md-3 item" style="margin-right: 90px ;">
                        <h3>Kontak</h3>
                        <ul>
                            <li><a href="#">No Telfon : (021) 2251 3737</a></li>
                            <li><a href="#">Fax : (021) 5296 0907 / 5296 0908</a></li>
                            <li><a href="#">Email : humas@ombudsman.go.id</a></li>
                        </ul>
                    </div>
                    <br>
                    <div class="col-sm-6 col-md-3 item" style="margin-right: 90px ;">
                        <h3>About</h3>
                        <ul>
                            <li><a href="https://ombudsman.go.id/">Ombudsman</a></li>
                            <li><a href="dev-team.php">Dev Team</a></li>
                        </ul>
                    </div>
                    <br>
                </div>
                <p class="copyright">Ombudsman RI © 2022</p>
                <p class="copyright">Made By Divisi HTI & <a href="dev-team.php" target="-blank">Team RJN</a></p>
                <i class="fa-regular fa-cart-shopping"></i>
            </div>
        </footer>
    </div>

</body>

</html>