<?php
session_start();
include 'db.php';
//Kondisi Supaya User & Non User tidak dapat akses page ini
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
$kantor_admin = $_SESSION['a_global']->office_id;
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
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <script src="https://www.google.com/jsapi"></script>
    <!--------------------- Sweet Alert CDN ----------------------------->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <!--------------------------------------------------------------------------------- ADMIN ---------------------------------------------------------------------------->
    <?php
    if ($_SESSION['role_login'] == 'admin') {
    ?>
        <!---------------------- header ----------------------------------->
        <header>
            <div class="container">
                <h1><a href="dashboard.php"><img style="width: 80px ; margin-bottom :-10px ;" src="img/logo-ombudsman2.png" alt=""> Gudang Ombudsman</a></h1>
                <ul style="margin-top: 20px ;">
                    <?php
                    //Deklarasi 2 variabel untuk menampung cart_id dari table data_transaction
                    $idk_1 = "";
                    $idk_2 = "";
                    //jml_produk digunakan untuk menampung berapa banyak barang yang di-query
                    $jml_produk = 0;
                    //jml_keranjang digunakan untuk menampung berapa banyak jumlah keranjang yang ada
                    $jml_keranjang = 0;
                    //query database
                    $keranjang = mysqli_query($conn, "SELECT * FROM data_transaction WHERE office_id = '" . $kantor_admin . "' AND status = 'Diproses Admin' ORDER BY cart_id");
                    if (mysqli_num_rows($keranjang) > 0) {
                        while ($fetch_keranjang = mysqli_fetch_array($keranjang)) {
                            $jml_produk++;
                            $idk_1 = $fetch_keranjang['cart_id'];
                            if ($idk_2 == $idk_1) { //Kondisi jika barang yang di fetch memiliki cart_id yang sama dengan barang sebelumnya
                                $jml_keranjang = $jml_keranjang * 1;
                            } else { //Jika cart_id barang yang di-fetch berbeda (dengan barang sebelumnya), maka jml_keranjang akan bertambah
                                $jml_keranjang++;
                            }
                            //cart_id barang yang di fetch, ditampung di idk_2 untuk looping (pengecekan) selanjutnya
                            $idk_2 = $fetch_keranjang['cart_id'];
                        }
                    }
                    //Hasilnya (jml_keranjang) akan ditampilkan dibagian navbar (sebelah pesanan)
                    ?>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="profile.php">Profil</a></li>
                    <li><a href="product-data.php">Data Barang</a></li>
                    <li><a href="user-data.php">Data User</a></li>
                    <li><a href="order-table.php">Pesanan (<?php echo $jml_keranjang; ?>)</a></li>
                    <li><a href="logout.php">Keluar</a></li>
                </ul>
            </div>
        </header>

        <!---------------------- Content ----------------------------------->
        <div class="section">
            <div class="container">
                <h3>Edit Data User</h3>
                <div class="box">
                    <form action="" method="POST">
                        <h4>ID User</h4>
                        <input type="text" name="id" class="input-control" value="<?php echo $u->user_id ?>" required>
                        <?php
                        $kantor = mysqli_query($conn, "SELECT * FROM data_office WHERE office_id = '" . $u->office_id . "' ");
                        $fetch_array_kantor = mysqli_fetch_array($kantor);
                        ?>
                        <h4>Perwakilan</h4>
                        <input type="text" name="perwakilan" class="input-control" value="<?php echo $fetch_array_kantor['office_name'] ?>" readonly>
                        <h4>Nama User</h4>
                        <input type="text" name="nama" class="input-control" value="<?php echo $u->user_name ?>" required>
                        <h4>Username Akun</h4>
                        <input type="text" name="username" class="input-control" value="<?php echo $u->user_username ?>" required>
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
                                            
                                            user_telp = '" . $telp . "',
                                            user_email = '" . $email . "',
                                            user_address = '" . $address . "'
                                            WHERE user_id = '" . $u->user_id . "'
                                            ");
                        if ($update) {
                            echo '<script>Swal.fire({
                                title: "Berhasil Edit User",
                                text: "Klik OK Untuk Lanjut",
                                icon : "success"
                           }).then(function() {
                                window.location = "user-data.php";
                           });
                           </script>';
                        } else {
                            echo 'gagal' . mysqli_error($conn);
                        }
                    }
                    ?>
                </div>
                <h3>Ubah Password</h3>
                <div class="box">
                    <form action="" method="POST">
                        <input type="password" name="pass1" placeholder="Password Baru" class="input-control" required>
                        <input type="password" name="pass2" placeholder="Konfirmasi Password Baru" class="input-control" required>
                        <input type="submit" name="ubah_password" value="Ubah Password" class="btn">
                    </form>
                    <?php
                    if (isset($_POST['ubah_password'])) {

                        $pass1 = $_POST['pass1'];
                        $pass2 = $_POST['pass2'];

                        if ($pass2 != $pass1) {
                            echo '<script>
                            Swal.fire({
                                icon: "error",
                                title: "Update Password Gagal !",
                                text: "Password baru tidak sesuai dengan Konfirmasi Password"
                              })
                            </script>';
                        } else {
                            $u_pass = mysqli_query($conn, "UPDATE data_user SET
                                        user_password = '" . MD5($pass1) . "'
                                        WHERE user_id = '" . $u->user_id . "' ");
                            if ($u_pass) {
                                echo '<script>Swal.fire({
                                    title: "Berhasil Ubah Password",
                                    text: "Klik OK Untuk Lanjut",
                                    icon : "success"
                               }).then(function() {
                                    window.location = "user-data.php";
                               });
                               </script>';
                            } else {
                                echo 'gagal' . mysqli_error($conn);
                            }
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
    <?php
    } else if ($_SESSION['role_login'] == 'super') {
    ?>
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
                <h3>Edit Data User</h3>
                <div class="box">
                    <form action="" method="POST">
                        <h4>ID User</h4>
                        <input type="text" name="id" class="input-control" value="<?php echo $u->user_id ?>" required>
                        <h4>Perwakilan</h4>
                        <select name="perwakilan" class="input-control" required>
                            <option value="">--Pilih Perwakilan</option>
                            <?php
                            $perwakilan = mysqli_query($conn, "SELECT * FROM data_office ORDER BY office_id");
                            while ($fa_perwakilan = mysqli_fetch_array($perwakilan)) {
                            ?>
                                <option value="<?php echo $fa_perwakilan['office_id'] ?>" <?php echo ($fa_perwakilan['office_id'] == $u->office_id) ? 'selected' : ''; ?>><?php echo $fa_perwakilan['office_name'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
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
                        $office = $_POST['perwakilan'];
                        $username = $_POST['username'];
                        $pass = $_POST['pass'];
                        $telp = $_POST['telp'];
                        $email = $_POST['email'];
                        $address = $_POST['address'];
                        $location = $_POST[""];

                        $update = mysqli_query($conn, "UPDATE data_user SET
                                            user_id = '" . $id . "',
                                            office_id = '" . $office . "',
                                            user_name = '" . $nama . "',
                                            user_username = '" . $username . "',
                                            user_password = '" . MD5($pass) . "',
                                            user_telp = '" . $telp . "',
                                            user_email = '" . $email . "',
                                            user_address = '" . $address . "'
                                            WHERE user_id = '" . $u->user_id . "'
                                            ");
                        if ($update) {
                            echo '<script>Swal.fire({
                                title: "Berhasil Ubah Data",
                                text: "Klik OK Untuk Lanjut",
                                icon : "success"
                           }).then(function() {
                                window.location = "user-data.php";
                           });
                           </script>';
                        } else {
                            echo 'gagal' . mysqli_error($conn);
                        }
                    }
                    ?>
                </div>

                <h3>Ubah Password</h3>
                <div class="box">
                    <form action="" method="POST">
                        <input type="password" name="pass1" placeholder="Password Baru" class="input-control" required>
                        <input type="password" name="pass2" placeholder="Konfirmasi Password Baru" class="input-control" required>
                        <input type="submit" name="ubah_password" value="Ubah Password" class="btn">
                    </form>
                    <?php
                    if (isset($_POST['ubah_password'])) {

                        $pass1 = $_POST['pass1'];
                        $pass2 = $_POST['pass2'];

                        if ($pass2 != $pass1) {
                            echo '<script>
                            Swal.fire({
                                icon: "error",
                                title: "Update Password Gagal !",
                                text: "Password baru tidak sesuai dengan Konfirmasi Password"
                              })
                            </script>';
                        } else {
                            $u_pass = mysqli_query($conn, "UPDATE data_user SET
                            user_password = '" . MD5($pass1) . "'
                            WHERE user_id = '" . $u->user_id . "' ");
                            if ($u_pass) {
                                echo '<script>Swal.fire({
                                    title: "Berhasil Update Password !",
                                    text: "Klik OK Untuk Lanjut.",
                                    icon: "success"
                                  },
                                  function(){
                                    window.location="profile.php"
                                  });
                                </script>';
                            } else {
                                echo 'gagal' . mysqli_error($conn);
                            }
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
    <?php
    }
    ?>

</body>

</html>