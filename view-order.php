<?php
session_start();
include 'db.php';
if ($_SESSION['role_login'] != 'user') {

    echo '<script>window.location="logout.php"</script>';
} else if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}

$trans = mysqli_query($conn, "SELECT * FROM data_transaction LEFT JOIN data_product USING (product_id) WHERE order_id = '" . $_GET['id'] . "' ");
if (mysqli_num_rows($trans) == 0) {
    echo '<script>window.location="user-order.php"</script>';
}
$fo_trans = mysqli_fetch_object($trans);

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
            <div class="box">
                <form action="" method="POST" enctype="multipart/form-data">
                    <h4>Order ID</h4>
                    <input type="text" name="idorder" class="input-control" value="<?php echo $fo_trans->order_id ?>" readonly>
                    <h4>User ID</h4>
                    <input type="text" name="iduser" class="input-control" value="<?php echo $fo_trans->user_id ?>" readonly>
                    <h4>Nama Pemesan</h4>
                    <input type="text" name="namauser" class="input-control" value="<?php echo $fo_trans->user_name ?>" readonly>
                    <h4>Perwakilan</h4>
                    <input type="text" name="perwakilan" class="input-control" value="<?php echo $fo_trans->office_name ?>" readonly>
                    <h4>Produk</h4>
                    <input type="text" name="produk" class="input-control" value="<?php echo $fo_trans->product_name ?>" readonly>
                    <h4>Gambar Barang</h4>
                    <img src="produk/<?php echo $fo_trans->product_image ?>" width="100px">
                    <h4>Deskripsi Barang</h4>
                    <textarea name="deskripsi" class="input-control" readonly><?php echo $fo_trans->product_description ?></textarea><br>
                    <h4>Jumlah Pesanan</h4>
                    <input type="text" name="quantity" class="input-control" value="<?php echo $fo_trans->quantity ?>" readonly>
                    <h4>Waktu Pesanan Dibuat</h4>
                    <input type="text" name="waktu" class="input-control" value="<?php echo $fo_trans->created ?>" readonly>
                    <h4>Status Barang</h4>
                    <input type="text" name="status" class="input-control" value="<?php
                                                                                    if ($fo_trans->status == 0) {
                                                                                        echo "Belum Disetujui";
                                                                                    } else if ($fo_trans->status == 1) {
                                                                                        echo "Disetujui";
                                                                                    }
                                                                                    ?>" readonly>
                    <input type="submit" name="submit" value="Submit" class="btn">
                </form>
                <?php
                if (isset($_POST['submit'])) {

                    //data input dari form
                    $kategori = $_POST['kategori'];
                    $nama = $_POST['nama'];
                    $harga = $_POST['harga'];
                    $deskripsi = $_POST['deskripsi'];
                    $status = $_POST['status'];
                    $foto = $_POST['foto'];
                    $stok = $_POST['stok'];

                    //data gambar baru
                    $filename = $_FILES['gambar']['name'];
                    $tmp_name = $_FILES['gambar']['tmp_name'];

                    $type1 = explode('.', $filename);
                    $type2 = $type1[1];

                    $newname = 'produk' . time() . '.' . $type2;

                    // return isset($type2) ? $type2 : null;

                    //menampung data format file yang diizinkan
                    $tipe_diizinkan = array('jpg', 'jpeg', 'png', 'gif');

                    //jika admin ganti gambar
                    if ($filename != '') {
                        if (!in_array($type2, $tipe_diizinkan)) {
                            echo '<script>alert("Format file tidak diizinkan")</script>';
                        } else {

                            unlink('./produk/' . $foto);

                            move_uploaded_file($tmp_name, './produk/' . $newname);
                            echo '<script>alert("Berhasil Upload")</script>';

                            $namagambar = $newname;
                        }
                    } else {
                        //jika admin tidak ganti gambar
                        $namagambar = $foto;
                    }

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
                            title: "Berhasil Edit Produk !",
                            text: "Klik OK Untuk Lanjut.",
                            icon: "success"
                          },
                          function(){
                            window.location="product-data.php"
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