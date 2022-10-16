<?php
error_reporting(0);
include 'db.php';
$kontak = mysqli_query($conn, "SELECT admin_telp, admin_email, admin_address FROM data_admin WHERE admin_id = 1");
$a = mysqli_fetch_object($kontak);

$produk = mysqli_query($conn, "SELECT * FROM data_product WHERE product_id = '" . $_GET['id'] . "' ");
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
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <!-- header -->
    <header>
        <div class="container">
            <h1><img style="width: 80px ; margin-bottom :-10px ;" src="img/logo-ombudsman2.png" alt=""><a href="index.php"> Gudang Ombudsman</a></h1>
            <ul style="margin-top: 20px ;">
                <li><a href="index.php">Home</a></li>
                <li><a href="category-product.php">Kategori</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </div>
    </header>

    <!--search-->
    <div class="search">
        <div class="container">
            <form action="produk.php" method="GET">
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
                    <img src="produk/<?php echo $p->product_image ?>" width="80%">
                </div>
                <div class="col-2">
                    <form action="" method="POST">
                        <h3><?php echo $p->product_name ?></h3>
                        <h4>RP. <?php echo number_format($p->product_price)  ?></h4>
                        <?php $qty = $p->stock; ?>
                        <h4>Stok Barang : <?php echo $qty ?> </h4>
                        <p>Deskripsi : <br>
                            <?php echo $p->product_description ?>
                        </p>
                        <h4>Jumlah</h4>
                        <input type="number" name="qty" class="input-control" min="1" max="<?php echo $qty ?>" value="1" required>
                        <input type="submit" name="submit" value="Masukkan Keranjang" class="btn">
                    </form>
                    <?php
                    if (isset($_POST['submit'])) {
                        echo '<script>Swal.fire({
                            title: "Login Untuk Pesan Barang",
                            text: "Hanya User yang Bisa Pesan Barang",
                            icon: "warning"
                          }).then(function() {
                            window.location = "index.php";
                          });
                        </script>';
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