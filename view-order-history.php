<?php
session_start();
include 'db.php';
if ($_SESSION['role_login'] != 'user') {

    echo '<script>window.location="logout.php"</script>';
} else if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}

$trans = mysqli_query($conn, "SELECT * FROM transaction_history LEFT JOIN data_product USING (product_id) WHERE cart_id = '" . $_GET['id'] . "' ");
if (mysqli_num_rows($trans) == 0) {
    echo '<script>window.location="user-order.php"</script>';
}

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
            <h1><a href="user-home.php">KP Ombudsman</a></h1>
            <ul>
                <li><a href="user-homepage-product.php">Produk</a></li>
                <li><a href="user-cart.php">Keranjang</a></li>
                <li><a href="user-order.php">Pesanan</a></li>
                <li><a href="user-profile.php">Profil Saya</a></li>
                <li><a href="logout.php">Log out</a></li>
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
                    if (mysqli_num_rows($trans) > 0) {
                        while ($fo_trans = mysqli_fetch_object($trans)) {
                    ?>

                            <h1>Produk ke <?php echo $no ?></h1><br>
                            <h2 id="h2produk"><?php echo $fo_trans->product_name ?></h2><br><br>
                            <img src="produk/<?php echo $fo_trans->product_image ?>" width="100px">
                            <br><br>
                            <h4>Jumlah Pesanan</h4>
                            <input type="text" name="quantity" class="input-control" value="<?php echo $fo_trans->quantity ?>" readonly>
                            <h4>Waktu Pesanan Dibuat</h4>
                            <input type="text" name="waktu" class="input-control" value="<?php echo $fo_trans->created ?>" readonly>
                            <h4>Catatan Dari Admin</h4>
                            <textarea name="notes" class="input-control" readonly><?php echo $fo_trans->notes ?></textarea><br>
                            <h4>Status Barang</h4>
                            <input type="text" class="input-control" readonly value="<?php echo $fo_trans->status ?>">

                            <br><br>
                    <?php
                            $no++;
                        }
                    }
                    ?>
                    <input type="submit" name="print" class="btn" value="Print Surat Pengambilan Barang">
                </form>
                <?php
                if (isset($_POST['submit'])) {

                    //query update data produk
                    $update = mysqli_query($conn, "UPDATE data_product SET 
                            category_id = '" . $kategori . "',
                            product_name= '" . $nama . "',
                            product_price = '" . $harga . "',
                            product_description = '" . $deskripsi . "',
                            product_image = '" . $namagambar . "',
                            product_status = '" . $status . "',
                            stock = '" . $stok . "'
                            WHERE product_id = '" . $p->product_id . "'
                    ");

                    if ($update) {
                        echo '<script>Swal.fire({
                            title: "Anda telah mengambil Pesanan !",
                            text: "Klik OK Untuk Lanjut.",
                            icon: "success"
                          },
                          function(){
                            window.location="user-home.php"
                          });
                        </script>';
                    } else {
                        echo 'gagal' . mysqli_error($conn);
                    }
                } else if (isset($_POST['wait'])) {
                    echo '<script>window.location="user-order.php"</script>';
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <small>Copyright &copy; 2022 - Ramsey Adrian</small>
        </div>
    </footer>

</body>

</html>