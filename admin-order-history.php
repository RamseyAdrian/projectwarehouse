<?php
//Memulai Sesi
session_start();

//Connect Database
include 'db.php';

//Kondisi Jika User Mencoba Masuk Page ini
if ($_SESSION['role_login'] == 'user') {

    echo '<script>window.location="logout.php"</script>';
} else if ($_SESSION['status_login'] != true) { //Kondisi Jika Non-user Mencoba Akses Page Ini
    echo '<script>window.location="login.php"</script>';
}

//Mengambil Data Riwayat Transaksi 
$trans = mysqli_query($conn, "SELECT * FROM transaction_history LEFT JOIN data_product USING (product_id) WHERE cart_id = '" . $_GET['id'] . "' ");
if (mysqli_num_rows($trans) == 0) {
    echo '<script>window.location="order-table.php"</script>';
}
$idkantoradmin = $_SESSION['a_global']->office_id;
$idcart = $_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM data_order WHERE cart_id = '" . $idcart . "' ");
$fa_data = mysqli_fetch_array($data);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KP Ombudsman</title>
    <!--------------------------- STYLE CSS ------------------------------>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <!--------------------------- Library yang digunakan ------------------------------>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
    <script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
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
                    $keranjang = mysqli_query($conn, "SELECT * FROM data_transaction WHERE office_id = '" . $idkantoradmin . "' ORDER BY cart_id");
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
                <h3>Detail Pesanan</h3>
                <style>
                    #h2produk {
                        color: red;
                        font-weight: bold;
                    }
                </style>
                <div class="box">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <?php
                        $no = 1;
                        $stock_ready = 0;
                        if (mysqli_num_rows($trans) > 0) {
                            while ($fo_trans = mysqli_fetch_object($trans)) {
                        ?>

                                <h1>Produk ke <?php echo $no ?></h1><br>
                                <h2 id="h2produk"><?php echo $fo_trans->product_name ?></h2><br><br>
                                <img src="produk/<?php echo $fo_trans->product_image ?>" width="100px">
                                <br><br>
                                <h4>Jumlah Pesanan</h4>
                                <input type="text" name="quantity" class="input-control" value="<?php echo $fo_trans->quantity ?>" readonly>

                                <br>

                            <?php
                                $no++;
                            }
                            $trans2 = mysqli_query($conn, "SELECT * FROM transaction_history LEFT JOIN data_product USING (product_id) WHERE cart_id = '" . $_GET['id'] . "' ");
                            $fo_trans2 = mysqli_fetch_object($trans2);
                            ?>
                            <br>
                            <h2>Waktu Pesanan Diproses</h2>
                            <input type="text" name="waktu" class="input-control" value="<?php echo $fo_trans2->created ?>" readonly>
                            <br>
                            <h2>Catatan Dari Admin</h2>

                            <input type="text" name="notes" class="input-control" value="<?php echo $fo_trans2->notes ?>" readonly>
                        <?php
                        }
                        ?>

                        <br>
                        <h2>Status Pemesanan Barang</h2>
                        <input type="text" class="input-control" name="status" value="<?php echo $fa_data['status'] ?>" readonly>
                        <br><br>
                        <center>
                            <input type="submit" name="submit" class="btn" value="Kembali">
                        </center>
                    </form>
                    <?php
                    if (isset($_POST['submit'])) {
                        echo '<script>window.location="order-history.php"</script>';
                    }
                    ?>

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
        <script>
            CKEDITOR.replace('deskripsi');
        </script>
    <?php
    } else if ($_SESSION['role_login'] == 'super') {
    ?>
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
                <h3>Detail Pesanan</h3>
                <style>
                    #h2produk {
                        color: red;
                        font-weight: bold;
                    }
                </style>
                <div class="box">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <?php
                        $no = 1;
                        $stock_ready = 0;
                        if (mysqli_num_rows($trans) > 0) {
                            while ($fo_trans = mysqli_fetch_object($trans)) {
                        ?>

                                <h1>Produk ke <?php echo $no ?></h1><br>
                                <h2 id="h2produk"><?php echo $fo_trans->product_name ?></h2><br><br>
                                <img src="produk/<?php echo $fo_trans->product_image ?>" width="100px">
                                <br><br>
                                <h4>Jumlah Pesanan</h4>
                                <input type="text" name="quantity" class="input-control" value="<?php echo $fo_trans->quantity ?>" readonly>

                                <br>

                            <?php
                                $no++;
                            }
                            $trans2 = mysqli_query($conn, "SELECT * FROM transaction_history LEFT JOIN data_product USING (product_id) WHERE cart_id = '" . $_GET['id'] . "' ");
                            $fo_trans2 = mysqli_fetch_object($trans2);
                            ?>
                            <br>
                            <h2>Waktu Pesanan Diproses</h2>
                            <input type="text" name="waktu" class="input-control" value="<?php echo $fo_trans2->created ?>" readonly>
                            <br>
                            <h2>Catatan Dari Admin</h2>

                            <input type="text" name="notes" class="input-control" value="<?php echo $fo_trans2->notes ?>" readonly>
                        <?php
                        }
                        ?>

                        <br>
                        <h2>Status Pemesanan Barang</h2>
                        <input type="text" class="input-control" name="status" value="<?php echo $fa_data['status'] ?>" readonly>
                        <br><br>
                        <center>
                            <input type="submit" name="submit" class="btn" value="Kembali">
                        </center>
                    </form>
                    <?php
                    if (isset($_POST['submit'])) {
                        echo '<script>window.location="order-history.php"</script>';
                    }
                    ?>

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
        <script>
            CKEDITOR.replace('deskripsi');
        </script>
    <?php
    }
    ?>



</body>

</html>