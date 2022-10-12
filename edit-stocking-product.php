<?php
session_start();
include 'db.php';
if ($_SESSION['role_login'] == 'user') {

    echo '<script>window.location="logout.php"</script>';
} else if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}

$produk = mysqli_query($conn, "SELECT * FROM data_product LEFT JOIN data_category USING (category_id) LEFT JOIN data_office USING (office_id) WHERE product_id = '" . $_GET['id'] . "' ");
if (mysqli_num_rows($produk) == 0) {
    echo '<script>window.location="product-data.php"</script>';
}
$p = mysqli_fetch_object($produk);

$query = mysqli_query($conn, "SELECT * FROM data_admin WHERE admin_id = '" . $_SESSION['id'] . "' ");
$d = mysqli_fetch_object($query);

$query_super = mysqli_query($conn, "SELECT * FROM data_superadmin WHERE super_admin_id = '" . $_SESSION['id'] . "' ");
$s = mysqli_fetch_object($query_super);

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
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="profile.php">Profil</a></li>
                <li><a href="product-data.php">Data Produk</a></li>
                <li><a href="user-data.php">Data User</a></li>
                <li><a href="order-table.php">Pesanan</a></li>
                <li><a href="logout.php">Keluar</a></li>
            </ul>
        </div>
    </header>

    <!-- Content -->
    <div class="section">
        <div class="container">
            <h3>Tambah Stock Produk</h3>
            <div class="box">
                <form action="" method="POST" enctype="multipart/form-data">

                    <h4>Jumlah Stok</h4>
                    <input type="number" name="stock" class="input-control" value="<?php echo $p->stock ?>" min="<?php echo $p->stock ?>">
                    <input type="submit" name="submit" value="Submit" class="btn">
                    <style>
                        #kembali {
                            border: 2px bold;
                            font-size: 20px;
                        }
                    </style>
                    <br><br>
                    <center>
                        <a href="stocking-product.php" id="kembali">Kembali ke Stocking</a>
                    </center>

                </form>
                <?php
                if (isset($_POST['submit'])) {
                    $stok = $_POST['stock'];
                    $stocking_id = rand();
                    $produk_id = $p->product_id;
                    $produk_nama = $p->product_name;
                    $kategori_id = $p->category_id;
                    $kategori_nama = $p->category_name;
                    $stoking_sebelum = $p->stock;
                    $stoking_setelah = $_POST['stock'];
                    $admin_id = $d->admin_id;
                    $admin_name = $d->admin_name;
                    $office_id = $p->office_id;
                    $office_name = $p->office_name;
                    //modified pake fungsi NOW() di mysqli
                    $qty = $stoking_setelah - $stoking_sebelum;


                    //query update data produk
                    $update = mysqli_query($conn, "UPDATE data_product SET 
                            stock = '" . $stok . "'
                            WHERE product_id = '" . $p->product_id . "'
                    ");

                    $insert = mysqli_query($conn, "INSERT INTO stocking_item VALUES (
                        '" . $stocking_id . "',
                        '" . $produk_id . "',
                        '" . $produk_nama . "',
                        '" . $kategori_id . "',
                        '" . $kategori_nama . "',
                        '" . $stoking_sebelum . "',
                        '" . $stoking_setelah . "',
                        '" . $admin_id . "',
                        '" . $admin_name . "',
                        '" . $office_id . "',
                        '" . $office_name . "',
                        NOW(),
                        '" . $qty . "'
                    )");

                    if ($update && $insert) {
                        echo '<script>Swal.fire({
                            title: "Berhasil Menambah Stock Barang !",
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