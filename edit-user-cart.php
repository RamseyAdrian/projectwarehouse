<?php
session_start();
include 'db.php';
if ($_SESSION['role_login'] != 'user') {

    echo '<script>window.location="logout.php"</script>';
} else if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}

$iduser = $_SESSION['a_global']->user_id;
$produk = mysqli_query($conn, "SELECT * FROM data_product WHERE product_id = '" . $_GET['id'] . "' ");
$keranjang = mysqli_query($conn, "SELECT * FROM data_cart WHERE data_cart.product_id = '" . $_GET['id'] . "' AND data_cart.user_id = '" . $iduser . "' ");
$fetch_keranjang = mysqli_fetch_object($keranjang);
if (mysqli_num_rows($keranjang) == 0) {
    echo '<script>window.location="user-home.php"</script>';
}
$p = mysqli_fetch_object($produk);
$idkategori = $p->category_id;
$user_office = $_SESSION['a_global']->office_id;

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
    <script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <!-- header -->
    <header>
        <div class="container">
            <h1><img style="width: 80px ; margin-bottom :-10px ;" src="img/logo-ombudsman2.png" alt=""><a href="user-home.php"> Gudang Ombudsman</a></h1>
            <ul style="margin-top: 20px ;">
                <?php
                $isi = 0;
                $keranjang2 = mysqli_query($conn, "SELECT * FROM data_cart WHERE user_id = '" . $iduser . "' AND office_id = '" . $user_office . "' ");
                if (mysqli_num_rows($keranjang2) > 0) {
                    while ($fetch_keranjang2 = mysqli_fetch_array($keranjang2)) {
                        $isi++;
                    }
                }
                ?>
                <li><a href="user-home.php">Home</a></li>
                <li><a href="user-category-product.php">Kategori</a></li>
                <li><a href="user-cart.php"><img style="width:16px ;" src="img/cart.png" alt="">(<?php echo $isi; ?>)</a></li>
                <li><a href="user-order.php">Transaksi</a></li>
                <li><a href="user-profile.php">Profil Saya</a></li>
                <li><a href="logout.php">Log out</a></li>
            </ul>
        </div>
    </header>

    <!-- Content -->
    <div class="section">
        <div class="container">
            <h3>Edit Jumlah Pesanan</h3>
            <div class="box">
                <div class="col-2">
                    <img src="produk/<?php echo $p->product_image ?>" width="70%">
                </div>
                <div class="col-2">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <?php
                        $kategori = mysqli_query($conn, "SELECT * FROM data_category WHERE category_id = '" . $idkategori . "' ");
                        $fetch_kategori = mysqli_fetch_array($kategori);
                        ?>
                        <h2 style="color: red ;"><?php echo $p->product_name ?></h2><br>
                        <h3>Kategori Barang : <?php echo $fetch_kategori['category_name'] ?></h3>
                        <?php
                        ?>
                        <h4 style="color: grey ;">ID Barang : <?php echo $p->product_id ?></h4><br>
                        <br>
                        <h3>Deskripsi Barang</h3>
                        <h4><?php echo $p->product_description ?></h4><br><br>
                        <?php
                        $satuan = mysqli_query($conn, "SELECT * FROM data_unit WHERE unit_id = '" . $p->unit_id . "' ");
                        while ($fa_satuan = mysqli_fetch_array($satuan)) {
                        ?>
                            <h3>Stok Barang : <?php echo $p->stock, " ", $fa_satuan['unit_name'] ?> </h3>
                        <?php
                        }
                        ?>
                        <h3>Edit Jumlah</h3>
                        <input type="number" name="jumlah" class="input-control" value="<?php echo $fetch_keranjang->quantity ?>" min="1" max="<?php echo $p->stock ?>">
                        <input type="submit" name="submit" value="Ubah Jumlah Pesanan" class="btn">
                    </form>
                </div>

                <?php
                if (isset($_POST['submit'])) {
                    $update_jumlah = $_POST['jumlah'];

                    $update = mysqli_query($conn, "UPDATE data_cart SET 
                            quantity = '" . $update_jumlah . "'
                            WHERE product_id = '" . $p->product_id . "' AND user_id = '" . $iduser . "'
                    ");

                    if ($update) {
                        echo '<script>Swal.fire({
                            title: "Berhasil Mengubah Pesanan !",
                            text: "Klik OK Untuk Lanjut.",
                            icon: "success"
                          }).then(function() {
                            window.location = "user-cart.php";
                          });
                        </script>';
                    } else {
                        echo 'gagal' . mysqli_error($conn);
                    }
                }

                //query update data produk


                ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
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

</body>

</html>