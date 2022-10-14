<?php
session_start();
include 'db.php';
if ($_SESSION['role_login'] != 'user') {

    echo '<script>window.location="logout.php"</script>';
} else if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}
$keranjang = mysqli_query($conn, "SELECT * FROM data_cart WHERE data_cart.product_id = '" . $_GET['id'] . "' ");
$produk = mysqli_query($conn, "SELECT * FROM data_product WHERE product_id = '" . $_GET['id'] . "' ");
if (mysqli_num_rows($keranjang) == 0) {
    echo '<script>window.location="user-home.php"</script>';
}
$p = mysqli_fetch_object($produk);

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
            <h3>Edit Data Produk</h3>
            <div class="box">
                <form action="" method="POST" enctype="multipart/form-data">
                    <h4>Kategori Barang</h4>
                    <select class="input-control" name="kategori" required>
                        <option value="">--Pilih--</option>
                        <?php
                        $kategori = mysqli_query($conn, "SELECT * FROM data_category ORDER BY category_id DESC");
                        while ($r = mysqli_fetch_array($kategori)) {
                        ?>
                            <option value="<?php echo $r['category_id'] ?>" <?php echo ($r['category_id'] == $p->category_id) ?
                                                                                'selected' : ''; ?>><?php echo $r['category_name'] ?> </option>
                        <?php } ?>
                    </select>
                    <h4>ID Barang</h4>
                    <input type="text" name="idbarang" class="input-control" value="<?php echo $p->product_id ?>" readonly>
                    <h4>Nama Barang</h4>
                    <input type="text" name="nama" class="input-control" placeholder="Nama Produk" value="<?php echo $p->product_name ?>" required>
                    <h4>Harga Barang</h4>
                    <input type="text" name="harga" class="input-control" placeholder="Harga" value="<?php echo $p->product_price ?>">

                    <h4>Gambar Barang</h4>
                    <img src="produk/<?php echo $p->product_image ?>" width="100px">
                    <input type="hidden" name="gambar" value="<?php echo $p->product_image ?>">

                    <input type="file" name="gambar" class="input-control" value="<?php echo $p->product_image ?>">
                    <h4>Deskripsi Barang</h4>
                    <textarea name="deskripsi" class="input-control" placeholder="Deskripsi"><?php echo $p->product_description ?></textarea><br>
                    <h4>ID Kantor</h4>
                    <input type="text" name="idkantor" class="input-control" value="<?php echo $_SESSION['a_global']->office_id ?>">
                    <h4>Jumlah Stok</h4>
                    <input type="text" name="stok" class="input-control" placeholder="Jumlah Stok" value="<?php echo $p->stock ?>" required>
                    <h4>Status Barang</h4>
                    <select name="status" class="input-control">
                        <option value="">--Pilih--</option>
                        <option value="1" <?php echo ($p->product_status == 1) ? 'selected' : ''; ?>>Aktif</option>
                        <option value="0" <?php echo ($p->product_status == 0) ? 'selected' : ''; ?>>Tidak Aktif</option>
                    </select>
                    <input type="submit" name="submit" value="Submit" class="btn">
                </form>
                <?php
                if (isset($_POST['submit'])) {
                    $idbarang = $_POST['idbarang'];
                    $kategori = $_POST['kategori'];
                    $nama = $_POST['nama'];
                    $harga = $_POST['harga'];
                    $deskripsi = $_POST['deskripsi'];
                    $status = $_POST['status'];
                    $stok = $_POST['stok'];
                    $idkantor = $_POST['idkantor'];

                    //menampung data file yang diupload
                    $filename = $_FILES['gambar']['name'];
                    $tmp_name = $_FILES['gambar']['tmp_name'];

                    $type1 = explode('.', $filename);
                    $type2 = $type1[1];

                    $newname = 'produk' . time() . '.' . $type2;

                    //menampung data format file yang diizinkan
                    $tipe_diizinkan = array('jpg', 'jpeg', 'png', 'gif');

                    //validasi format file
                    if (!in_array($type2, $tipe_diizinkan)) {
                        echo '<script>alert("Format file tidak diizinkan")</script>';
                    } else {
                        move_uploaded_file($tmp_name, './produk/' . $newname);
                        // echo '<script>alert("Berhasil Upload")</script>';

                        $update = mysqli_query($conn, "UPDATE data_product SET 
                            category_id = '" . $kategori . "',
                            product_name= '" . $nama . "',
                            product_price = '" . $harga . "',
                            product_description = '" . $deskripsi . "',
                            product_image = '" . $newname . "',
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

                    //query update data produk

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