<?php
session_start();
error_reporting(0);
include 'db.php';
if ($_SESSION['role_login'] != 'user') {
    echo '<script>window.location="logout.php"</script>';
} else if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}


$kontak = mysqli_query($conn, "SELECT admin_telp, admin_email, admin_address FROM data_admin WHERE admin_id = 1");
$a = mysqli_fetch_object($kontak);

$produk = mysqli_query($conn, "SELECT * FROM data_product LEFT JOIN data_category  USING (category_id) WHERE product_id = '" . $_GET['id'] . "' ");
$p = mysqli_fetch_object($produk);

$qd = mysqli_query($conn, "SELECT * FROM data_office WHERE office_id = 11");
$fo = mysqli_fetch_object($qd);

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
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</head>

<body>
    <!-- header -->
    <header>
        <div class="container">
            <h1><a href="user-home.php">KP Ombudsman</a></h1>
            <ul>
                <li><a href="user-homepage-product.php">Produk</a></li>
                <li><a href="user-cart.php">Keranjang</a></li>
                <li><a href="user-profile.php">Profil Saya</a></li>
                <li><a href="logout.php">Log out</a></li>
            </ul>
        </div>
        <div class="container">

            <h4>Selamat Datang <?php echo $_SESSION['a_global']->user_name ?> di Warehouse Ombudsman</h4>
        </div>
    </header>



    <!--search-->
    <div class="search">
        <div class="container">
            <form action="user-homepage-product.php" method="GET">
                <input type="text" name="search" placeholder="cari produk" value="<?php echo $_GET['search'] ?>">
                <input type="submit" name="cari" value="Cari">
            </form>
        </div>
    </div>

    <!--Product Detail-->
    <div class="section">
        <div class="container">
            <h3>Detail Produk</h3>
            <div class="box">
                <div class="col-2">
                    <img src="produk/<?php echo $p->product_image ?>" width="100%">
                </div>
                <div class="col-2">
                    <form action="" method="POST">
                        <h3><?php echo $p->product_name ?></h3>
                        <h4>RP. <?php echo number_format($p->product_price)  ?></h4>
                        <h4>Stok Barang : <?php echo $p->stock ?> </h4>
                        <p>Deskripsi : <br>
                            <?php echo $p->product_description ?>
                        </p>
                        <h4>Jumlah</h4>
                        <input type="text" name="qty" class="input-control" required>
                        <input type="submit" name="submit" value="Masukkan Keranjang" class="btn">
                    </form>
                    <?php
                    if (isset($_POST['submit'])) {
                        $iduser = $_SESSION['a_global']->user_id;
                        $idkantor = $_SESSION['a_global']->office_id;
                        $idbarang = $p->product_id;
                        $idkategori = $p->category_id;
                        $jumlah = $_POST['qty'];
                        $insert = true;


                        if ($insert) {
                            $insert = mysqli_query($conn, "INSERT INTO data_cart VALUES (
                                '" . $iduser . "',
                                '" . $idkantor . "',
                                '" . $idbarang . "',
                                '" . $idkategori . "',
                                '" . $jumlah . "',
                                NOW(),
                                NOW()
                            )");
                            echo '<script>alert("Berhasil masuk ke keranjang")</script>';
                            echo '<script>window.location="user-home.php"</script>';
                        } else {
                            echo 'gagal' . mysqli_error($conn);
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="container">
            <h4>Alamat Kantor Ombudsman RI</h4>
            <p><?php echo $fo->office_address ?></p>

            <h4>Email</h4>
            <p><?php echo $fo->office_email ?></p>

            <h4>Nomor Telfon</h4>
            <p><?php echo $fo->office_telp ?></p>
            <small>Copyright &copy; 2022 - KP Ombudsman</small>
        </div>
    </div>


</body>

</html>