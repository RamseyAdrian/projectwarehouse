<?php
session_start();
include 'db.php';
//Kondisi Supaya User, Super Admin & Non User tidak dapat akses page ini
if ($_SESSION['role_login'] == 'user' && $_SESSION['role_login'] == 'super') {
    echo '<script>window.location="logout.php"</script>';
} else if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}

$produk = mysqli_query($conn, "SELECT * FROM data_product LEFT JOIN data_category USING (category_id) LEFT JOIN data_office USING (office_id) WHERE product_id = '" . $_GET['id'] . "' ");
if (mysqli_num_rows($produk) == 0) {
    echo '<script>window.location="product-data.php"</script>';
}
$p = mysqli_fetch_object($produk);

$satuan = mysqli_query($conn, "SELECT * FROM data_unit WHERE unit_id = '" . $p->unit_id . "' ");
$fo_satuan = mysqli_fetch_object($satuan);

$query = mysqli_query($conn, "SELECT * FROM data_admin WHERE admin_id = '" . $_SESSION['id'] . "' ");
$d = mysqli_fetch_object($query);

$query_super = mysqli_query($conn, "SELECT * FROM data_superadmin WHERE super_admin_id = '" . $_SESSION['id'] . "' ");
$s = mysqli_fetch_object($query_super);
$kantor_admin = $_SESSION['a_global']->office_id;

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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <!--------------------- Sweet Alert CDN ----------------------------->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css" rel="stylesheet" />
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!--------------------- Additional CSS ----------------------------->
    <style>
        #kembali {
            border: 2px bold;
            font-size: 20px;
        }
    </style>
</head>

<body>
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
                $keranjang = mysqli_query($conn, "SELECT * FROM data_transaction WHERE office_id = '" . $kantor_admin . "' ORDER BY cart_id");
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
            <h3>Tambah Stock Barang</h3>
            <div class="box">
                <form action="" method="POST" enctype="multipart/form-data">
                    <h2><?php echo $p->product_name ?></h2>
                    <h3>Sisa Stock : <?php echo $p->stock ?></h3><br>
                    <h4>Jumlah Stok Tambahan</h4>
                    <input type="number" name="stock" class="input-control" value="1" min="1" max="1000">
                    <input type="submit" name="submit" value="Submit" class="btn">
                    <br><br>
                    <center>
                        <a href="stocking-product.php" id="kembali">Kembali ke Stocking</a>
                    </center>
                </form>
                <?php
                if (isset($_POST['submit'])) {
                    $stok_added = $_POST['stock'];
                    $stocking_id = rand();
                    $produk_id = $p->product_id;
                    $produk_nama = $p->product_name;
                    $kategori_id = $p->category_id;
                    $kategori_nama = $p->category_name;
                    $stoking_sebelum = $p->stock;
                    $satuan_id = $p->unit_id;
                    $satuan_nama = $fo_satuan->unit_name;

                    $stok = $stok_added + $p->stock;

                    $stoking_setelah = $stok_added + $p->stock;
                    $admin_id = $d->admin_id;
                    $admin_name = $d->admin_name;
                    $office_id = $p->office_id;
                    $office_name = $p->office_name;
                    //modified pake fungsi NOW() di mysqli
                    $qty = $_POST['stock'];

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
                        '" . $satuan_id . "',
                        '" . $satuan_nama . "',
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
                          }).then(function() {
                            window.location = "product-data.php";
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
        CKEDITOR.replace('deskripsi');
    </script>

</body>

</html>