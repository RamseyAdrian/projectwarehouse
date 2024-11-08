<?php
session_start();
include 'db.php';
// Kondisi Supaya User, Admin, & Non User tidak dapat akses page ini
if ($_SESSION['role_login'] == 'user') {
    echo '<script>window.location="logout.php"</script>';
} else if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}

$trans = mysqli_query($conn, "SELECT * FROM data_transaction LEFT JOIN data_product USING (product_id) WHERE cart_id = '" . $_GET['id'] . "' ");
if (mysqli_num_rows($trans) == 0) {
    echo '<script>window.location="order-table.php"</script>';
}
$idcart = $_GET['id'];
$idkantoradmin = $_SESSION['a_global']->office_id;
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
    <!--------------------- CK Editor CDN ----------------------------->
    <script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>
    <!--------------------- Sweet Alert CDN ----------------------------->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css" rel="stylesheet" />
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!--------------------- Additional CSS ----------------------------->
    <style>
        #h2produk {
            color: red;
            font-weight: bold;
        }

        .section .container .box form #button-restock {
            background-color: red;
            color: white;
            font-weight: bold;
            padding: 5px;
            border-radius: 5px;
            margin-top: 5px;
            font-size: 15px;
        }

        .section .container .box form #button-restock:hover {
            background-color: black;
            color: white;
        }

        .section .container .box form #button-restock a {
            text-decoration: none;
        }
    </style>
</head>


<body>
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
                    $keranjang = mysqli_query($conn, "SELECT * FROM data_transaction WHERE office_id = '" . $idkantoradmin . "' AND status = 'Diproses Admin' ORDER BY cart_id");
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
                <h2>Pilih Barang</h2>
                <?php
                $no = 1;
                while ($fo_trans = mysqli_fetch_object($trans)) {
                ?>
                    <a href="edit-order-detail.php?id=<?php echo $fo_trans->order_id ?>" style="text-decoration:none ;">
                        <div class="container-items">
                            <form action="" method="POST">
                                <div class="items-img">
                                    <img src="produk/<?php echo $fo_trans->product_image ?>" width="150px" height="150px">
                                </div>
                                <div class="items-content">
                                    <h2 id="h2produk"><?php echo $fo_trans->product_name ?></h2><br>
                                    <h3>Jumlah Pesanan</h3>
                                    <?php
                                    $satuan = mysqli_query($conn, "SELECT * FROM data_unit WHERE unit_id = '" . $fo_trans->unit_id . "' ");
                                    while ($fa_satuan = mysqli_fetch_array($satuan)) {
                                    ?>
                                        <h3 style="color: red ;"><?php echo $fo_trans->quantity, " ", $fa_satuan['unit_name'] ?></h3><br>
                                        <?php
                                        $barang = mysqli_query($conn, "SELECT * FROM data_product WHERE product_id = '" . $fo_trans->product_id . "' AND office_id = '" . $fo_trans->office_id . "' ");
                                        $fetch_barang = mysqli_fetch_array($barang);
                                        ?>
                                        <h3>Sisa Stock : <?php echo $fetch_barang['stock'], " ", $fa_satuan['unit_name'] ?></h3>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </form>
                        </div>
                    </a>

                <?php
                }

                ?>
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

        <script>
            CKEDITOR.replace('notes');
        </script>
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
                <h3>Detail Pesanan</h3>
                <style>
                    #h2produk {
                        color: red;
                        font-weight: bold;
                    }

                    .section .container .box form #button-restock {
                        background-color: red;
                        color: white;
                        font-weight: bold;
                        padding: 5px;
                        border-radius: 5px;
                        margin-top: 5px;
                        font-size: 15px;
                    }

                    .section .container .box form #button-restock:hover {
                        background-color: black;
                        color: white;
                    }

                    .section .container .box form #button-restock a {
                        text-decoration: none;
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
                                <!-- <h1><?php echo $fo_trans->user_id ?></h1> -->
                                <h2 id="h2produk"><?php echo $fo_trans->product_name ?></h2><br><br>
                                <img src="produk/<?php echo $fo_trans->product_image ?>" width="100px">
                                <br><br>
                                <h3>Jumlah Pesanan</h3>
                                <input type="text" name="quantity" class="input-control" value="<?php echo $fo_trans->quantity ?>" readonly>
                                <h3>Waktu Pesanan Dibuat</h3>
                                <input type="text" name="waktu" class="input-control" value="<?php echo $fo_trans->created ?>" readonly>
                                <h3>Ketersediaan Barang</h3>
                                <h4>Stock : <?php echo $fo_trans->stock ?></h4>
                                <?php
                                if ($fo_trans->stock >= $fo_trans->quantity) {
                                    $stock_ready++;
                                    $update_dt = mysqli_query($conn, "UPDATE data_transaction SET 
                                    red_flag = 'not red'
                                    WHERE order_id = '" . $fo_trans->order_id . "'
                                ");
                                ?>
                                    <h4 style="color: green ;">Stock Ready</h4>
                                <?php
                                } else if ($fo_trans->stock < $fo_trans->quantity) {
                                    $quantity_update = $fo_trans->stock;
                                    $update_dt = mysqli_query($conn, "UPDATE data_transaction SET 
                                    red_flag = 'red'
                                    WHERE order_id = '" . $fo_trans->order_id . "'
                                ");
                                ?>
                                    <h4 style="color: red ;">Stock tidak mencukupi</h4>
                                    <button id="button-restock"><a href="edit-stocking-product.php?id=<?php echo $fo_trans->product_id ?>">Restock</a></button>
                                <?php
                                }
                                ?>
                                <br><br>
                        <?php
                                $no++;
                            }
                        }
                        ?>
                        <center>
                            <input style="cursor: pointer ;" type="submit" name="kembali" class="input-control" value="Kembali">
                        </center>
                        <center>
                            <input type="submit" name="submit" class="btn" value="Berhasil Diambil">
                        </center>
                        <center>
                            <input type="submit" name="submit" class="btn" value="Gagal Diambil" style="background-color: green;">
                        </center>
                    </form>
                    <?php
                    if (isset($_POST['kembali'])) {
                        echo '<script>window.location="order-table.php"</script>';
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

        <script>
            CKEDITOR.replace('notes');
        </script>
    <?php
    }
    ?>


</body>

</html>