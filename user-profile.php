<?php
session_start();
include 'db.php';
if ($_SESSION['role_login'] != 'user') {
    echo '<script>window.location="logout.php"</script>';
} else if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}

$iduser = $_SESSION['a_global']->user_id;
$idkantor = $_SESSION['a_global']->office_id;

$query = mysqli_query($conn, "SELECT * FROM data_user WHERE user_id = '" . $_SESSION['id'] . "' ");
$d = mysqli_fetch_object($query);

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <!-- header -->
    <header>
        <div class="container">
            <h1><img style="width: 80px ; margin-bottom :-10px ;" src="img/logo-ombudsman2.png" alt=""><a href="user-home.php"> Gudang Ombudsman</a></h1>
            <ul style="margin-top: 20px ;">
                <?php
                $isi = 0;
                $keranjang = mysqli_query($conn, "SELECT * FROM data_cart WHERE user_id = '" . $iduser . "' AND office_id = '" . $idkantor . "' ");
                if (mysqli_num_rows($keranjang) > 0) {
                    while ($fetch_keranjang = mysqli_fetch_array($keranjang)) {
                        $isi++;
                    }
                }
                ?>
                <li><a href="user-home.php">Home</a></li>
                <li><a href="user-category-product.php">Kategori</a></li>
                <li><a href="user-cart.php"><i class="fa fa-shopping-cart" aria-hidden="true" style="width:16px;"></i>(<?php echo $isi ?>)</a></li>
                <li><a href="user-order.php">Transaksi</a></li>
                <li><a href="user-profile.php">Profil Saya</a></li>
                <li><a href="logout.php">Log out</a></li>
            </ul>
        </div>
    </header>

    <!-- Content -->
    <div class="section">
        <div class="container">
            <h3>Profil</h3>
            <div class="box">
                <form action="" method="POST">
                    <h4>USER ID</h4>
                    <input type="text" class="input-control" value="<?php echo $d->user_id ?>" readonly>
                    <h4>Nama Lengkap</h4>
                    <input type="text" name="nama" placeholder="Nama Lengkap" class="input-control" value="<?php echo $d->user_name ?>" required>
                    <h4>Username</h4>
                    <input type="text" name="user" placeholder="Username" class="input-control" value="<?php echo $d->user_username ?>" required>
                    <h4>Nomor Telfon</h4>
                    <input type="text" name="hp" placeholder="No HP" class="input-control" value="<?php echo $d->user_telp ?>" required>
                    <h4>Email</h4>
                    <input type="email" name="email" placeholder="Email" class="input-control" value="<?php echo $d->user_email ?>" required>
                    <h4>Alamat Tempat Tinggal</h4>
                    <input type="text" name="alamat" placeholder="Alamat" class="input-control" value="<?php echo $d->user_address ?>" required>
                    <input type="submit" name="submit" value="Ubah Profil" class="btn">
                </form>
                <?php
                if (isset($_POST['submit'])) {

                    $nama = ucwords($_POST['nama']);
                    $user = $_POST['user'];
                    $hp = $_POST['hp'];
                    $email = $_POST['email'];
                    $alamat = ucwords($_POST['alamat']);

                    $update = mysqli_query($conn, "UPDATE data_user SET
                                        user_name = '" . $nama . "',
                                        user_username = '" . $user . "',
                                        user_telp = '" . $hp . "',
                                        user_email = '" . $email . "',
                                        user_address = '" . $alamat . "'
                                        WHERE user_id = '" . $d->admin_id . "' ");

                    if ($update) {
                        echo '<script>alert("Berhasil Ubah Data")</script>';
                        echo '<script>window.location="profil.php"</script>';
                    } else {
                        echo 'gagal' . mysqli_error($conn);
                    }
                }
                ?>

            </div>
        </div>
    </div>

</body>

</html>