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
            <h1><a href="dashboard.php">KP Ombudsman</a></h1>
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
                        <h3>Kategori Barang : <?php echo $fetch_kategori['category_name'] ?></h3><br>
                        <?php
                        ?>
                        <h3>ID Barang : <?php echo $p->product_id ?></h3><br>
                        <h3>Nama : <?php echo $p->product_name ?></h3><br>
                        <h3>Deskripsi Barang</h3>
                        <h4><?php echo $p->product_description ?></h4><br><br>
                        <?php

                        ?>
                        <h4>Stok Barang : <?php echo $p->stock ?> </h4>
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
    <footer>
        <div class="container">
            <small>Copyright &copy; 2022 - Ramsey Adrian</small>
        </div>
    </footer>
    <script>
        CKEDITOR.replace('deskripsi');
    </script>

</body>

</html>