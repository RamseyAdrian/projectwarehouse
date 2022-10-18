<?php
session_start();
error_reporting(0);
include 'db.php';
if ($_SESSION['role_login'] != 'user') {
    echo '<script>window.location="logout.php"</script>';
} else if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}
$iduser = $_SESSION['a_global']->user_id;
$idkantor = $_SESSION['a_global']->office_id;

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
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                    <img src="produk/<?php echo $p->product_image ?>" width="80%">
                </div>
                <div class="col-2">
                    <form action="" method="POST">
                        <h2><?php echo $p->product_name ?></h2><br>
                        <h4>RP. <?php echo number_format($p->product_price)  ?></h4>
                        <?php $qty = $p->stock;
                        $satuan = mysqli_query($conn, "SELECT * FROM data_unit WHERE unit_id = '" . $p->unit_id . "' ");
                        while ($fa_satuan = mysqli_fetch_array($satuan)) {
                        ?>
                            <h4>Stok Barang : <?php echo $qty, " ", $fa_satuan['unit_name'] ?> </h4>
                        <?php
                        }
                        ?>
                        <p>Deskripsi : <br>
                            <?php echo $p->product_description ?>
                        </p>
                        <h4>Jumlah</h4>
                        <input type="number" name="qty" class="input-control" min="1" max="<?php echo $qty ?>" value="1" required>
                        <input type="submit" name="submit" value="Tambah ke Keranjang" class="btn">
                    </form>
                    <?php
                    if (isset($_POST['submit'])) {
                        $iduser = $_SESSION['a_global']->user_id;
                        $idkantor = $_SESSION['a_global']->office_id;
                        $idbarang = $p->product_id;
                        $idkategori = $p->category_id;
                        $idsatuan = $p->unit_id;
                        $jumlah = $_POST['qty'];
                        $insert = true;

                        $keranjang = mysqli_query($conn, "SELECT * FROM data_cart WHERE user_id = '" . $iduser . "' AND office_id = '" . $idkantor . "' ");
                        $produk_yang_sama = 0;
                        if (mysqli_num_rows($keranjang) > 0) {
                            while ($fetch_array_keranjang = mysqli_fetch_array($keranjang)) {
                                if ($fetch_array_keranjang['product_id'] == $idbarang) {
                                    $produk_yang_sama++;
                                }
                            }
                        }


                        if ($produk_yang_sama == 0) {
                            $insert = mysqli_query($conn, "INSERT INTO data_cart VALUES (
                                '" . $iduser . "',
                                '" . $idkantor . "',
                                '" . $idbarang . "',
                                '" . $idkategori . "',
                                '" . $idsatuan . "',
                                '" . $jumlah . "',
                                NOW(),
                                NOW()
                            )");
                            echo '<script>Swal.fire({
                                title: "Berhasil Masuk Keranjang !",
                                text: "Klik OK Untuk Lanjut",
                                icon : "success"
                           }).then(function() {
                                window.location = "user-cart.php";
                           });
                           </script>';
                        } else if ($produk_yang_sama > 0) {
                            echo '<script>Swal.fire({
                                title: "Barang Sudah di Keranjang !",
                                icon : "info"
                           }).then(function() {
                                window.location = "user-cart.php";
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
    </div>

    <!-- Footer -->
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
                            <li><a href="#">Company</a></li>
                            <li><a href="#">Team</a></li>
                        </ul>
                    </div>
                    <br>

                </div>
                <p class="copyright">Ombudsman RI © 2022</p>
                <p class="copyright">Made By Divisi HTI & Team RJN</p>
            </div>
        </footer>
    </div>


</body>

</html>