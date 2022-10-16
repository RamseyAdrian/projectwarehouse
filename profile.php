<?php
session_start();
include 'db.php';
if ($_SESSION['role_login'] == 'user') {

    echo '<script>window.location="logout.php"</script>';
} else if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}

$query = mysqli_query($conn, "SELECT * FROM data_admin WHERE admin_id = '" . $_SESSION['id'] . "' ");
$d = mysqli_fetch_object($query);

$query_super = mysqli_query($conn, "SELECT * FROM data_superadmin WHERE super_admin_id = '" . $_SESSION['id'] . "' ");
$s = mysqli_fetch_object($query_super);
$kantor_admin = $_SESSION['a_global']->office_id;
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
    <!--------------------------------------------------------------------------------- ADMIN ---------------------------------------------------------------------------->
    <?php
    if ($_SESSION['role_login'] == 'admin') {
    ?>
        <!-- header -->
        <header>
            <div class="container">
                <h1><a href="dashboard.php"><img style="width: 80px ; margin-bottom :-10px ;" src="img/logo-ombudsman2.png" alt=""> Gudang Ombudsman</a></h1>
                <ul style="margin-top: 20px ;">
                    <?php
                    $idk_1 = "";
                    $idk_2 = "";
                    $jml_produk = 0;
                    $jml_keranjang = 0;
                    $keranjang = mysqli_query($conn, "SELECT * FROM data_transaction WHERE office_id = '" . $kantor_admin . "' ORDER BY cart_id");
                    if (mysqli_num_rows($keranjang) > 0) {
                        while ($fetch_keranjang = mysqli_fetch_array($keranjang)) {
                            $jml_produk++;
                            $idk_1 = $fetch_keranjang['cart_id'];
                            if ($idk_2 == $idk_1) {
                                $jml_keranjang = $jml_keranjang * 1;
                            } else {
                                $jml_keranjang++;
                            }
                            $idk_2 = $fetch_keranjang['cart_id'];
                        }
                    }
                    ?>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="profile.php">Profil</a></li>
                    <li><a href="product-data.php">Data Produk</a></li>
                    <li><a href="user-data.php">Data User</a></li>
                    <li><a href="order-table.php">Pesanan (<?php echo $jml_keranjang; ?>)</a></li>
                    <li><a href="logout.php">Keluar</a></li>
                </ul>
            </div>
        </header>

        <!-- Content -->
        <div class="section">
            <div class="container">
                <h3>Profil</h3>
                <div class="box">
                    <form action="" method="POST">
                        <h4>ID Admin</h4>
                        <input type="text" name="idadmin" placeholder="ID Admin" class="input-control" value="<?php echo $d->admin_id ?>" readonly>
                        <h4>Nama Lengkap</h4>
                        <input type="text" name="nama" placeholder="Nama Lengkap" class="input-control" value="<?php echo $d->admin_name ?>" required>
                        <h4>Username</h4>
                        <input type="text" name="user" placeholder="Username" class="input-control" value="<?php echo $d->admin_username ?>" required>
                        <h4>Nomor Telfon</h4>
                        <input type="text" name="hp" placeholder="No HP" class="input-control" value="<?php echo $d->admin_telp ?>" required>
                        <h4>Email</h4>
                        <input type="email" name="email" placeholder="Email" class="input-control" value="<?php echo $d->admin_email ?>" required>
                        <h4>Alamat Tempat Tinggal</h4>
                        <input type="text" name="alamat" placeholder="Alamat" class="input-control" value="<?php echo $d->admin_address ?>" required>
                        <input type="submit" name="submit" value="Ubah Profil" class="btn">
                    </form>
                    <?php
                    if (isset($_POST['submit'])) {

                        $nama = ucwords($_POST['nama']);
                        $user = $_POST['user'];
                        $hp = $_POST['hp'];
                        $email = $_POST['email'];
                        $alamat = ucwords($_POST['alamat']);

                        $update = mysqli_query($conn, "UPDATE data_admin SET
                                        admin_name = '" . $nama . "',
                                        admin_username = '" . $user . "',
                                        admin_telp = '" . $hp . "',
                                        admin_email = '" . $email . "',
                                        admin_address = '" . $alamat . "'
                                        WHERE admin_id = '" . $d->admin_id . "' ");

                        if ($update) {
                            echo '<script>Swal.fire({
                                title: "Berhasil Update Profile !",
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
                            $u_pass = mysqli_query($conn, "UPDATE data_admin SET
                                        admin_password = '" . MD5($pass1) . "'
                                        WHERE admin_id = '" . $d->admin_id . "' ");
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

        <!-- Footer -->
        <footer>
            <div class="container">
                <small>Copyright &copy; 2022 - Universitas Pertamina</small>
            </div>
        </footer>
        <!--------------------------------------------------------------------------------- SUPER ADMIN ---------------------------------------------------------------------------->
    <?php
    } else if ($_SESSION['role_login'] == 'super') {
    ?>
        <!-- SUPER ADMIN-->
        <!-- header -->
        <header>
            <div class="container">
                <h1><a href="dashboard.php"><img style="width: 80px ; margin-bottom :-10px ;" src="img/logo-ombudsman2.png" alt=""> Gudang Ombudsman</a></h1>
                <ul style="margin-top: 20px ;">
                    <?php
                    $idk_1 = "";
                    $idk_2 = "";
                    $jml_produk = 0;
                    $jml_keranjang = 0;
                    $keranjang = mysqli_query($conn, "SELECT * FROM data_transaction WHERE office_id = '" . $kantor_admin . "' ORDER BY cart_id");
                    if (mysqli_num_rows($keranjang) > 0) {
                        while ($fetch_keranjang = mysqli_fetch_array($keranjang)) {
                            $jml_produk++;
                            $idk_1 = $fetch_keranjang['cart_id'];
                            if ($idk_2 == $idk_1) {
                                $jml_keranjang = $jml_keranjang * 1;
                            } else {
                                $jml_keranjang++;
                            }
                            $idk_2 = $fetch_keranjang['cart_id'];
                        }
                    }
                    ?>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="profile.php">Profil</a></li>
                    <li><a href="category-data.php">Data Kategori</a></li>
                    <li><a href="product-data.php">Data Produk</a></li>
                    <li><a href="office-data.php">Perwakilan</a></li>
                    <li><a href="admin-data.php">Data Admin</a></li>
                    <li><a href="user-data.php">Data User</a></li>
                    <li><a href="order-table.php">Pesanan (<?php echo $jml_keranjang; ?>)</a></li>
                    <li><a href="logout.php">Keluar</a></li>
                </ul>
            </div>
        </header>

        <!-- Content -->
        <div class="section">
            <div class="container">
                <h3>Profil</h3>
                <div class="box">
                    <form action="" method="POST">
                        <h4>ID Super Admin</h4>
                        <input type="text" name="idsuper" placeholder="ID Admin" class="input-control" value="<?php echo $s->super_admin_id ?>" readonly>
                        <h4>Nama Lengkap</h4>
                        <input type="text" name="nama" placeholder="Nama Lengkap" class="input-control" value="<?php echo $s->super_name ?>" required>
                        <h4>Username</h4>
                        <input type="text" name="user" placeholder="Username" class="input-control" value="<?php echo $s->super_username ?>" required>
                        <h4>Nomor Telfon</h4>
                        <input type="text" name="hp" placeholder="No HP" class="input-control" value="<?php echo $s->super_telp ?>" required>
                        <h4>Email</h4>
                        <input type="email" name="email" placeholder="Email" class="input-control" value="<?php echo $s->super_email ?>" required>
                        <h4>Alamat Tempat Tinggal</h4>
                        <input type="text" name="alamat" placeholder="Alamat" class="input-control" value="<?php echo $s->super_address ?>" required>
                        <input type="submit" name="submit" value="Ubah Profil" class="btn">
                    </form>
                    <?php
                    if (isset($_POST['submit'])) {

                        $nama = ucwords($_POST['nama']);
                        $user = $_POST['user'];
                        $hp = $_POST['hp'];
                        $email = $_POST['email'];
                        $alamat = ucwords($_POST['alamat']);

                        $update = mysqli_query($conn, "UPDATE data_superadmin SET
                                        super_name = '" . $nama . "',
                                        super_username = '" . $user . "',
                                        super_telp = '" . $hp . "',
                                        super_email = '" . $email . "',
                                        super_address = '" . $alamat . "'
                                        WHERE super_admin_id = '" . $s->super_admin_id . "' ");

                        if ($update) {
                            echo '<script>Swal.fire({
                                title: "Berhasil Update Profile !",
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
                            $u_pass = mysqli_query($conn, "UPDATE data_admin SET
                                        admin_password = '" . MD5($pass1) . "'
                                        WHERE admin_id = '" . $s->super_admin_id . "' ");
                            if ($u_pass) {
                                echo '<script>swal({
                                    title: "Berhasil Update Password !",
                                    text: "Klik OK Untuk Lanjut.",
                                    type: "success"
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

        <!-- Footer -->
        <footer>
            <div class="container">
                <small>Copyright &copy; 2022 - Universitas Pertamina</small>
            </div>
        </footer>
    <?php
    }
    ?>


</body>

</html>