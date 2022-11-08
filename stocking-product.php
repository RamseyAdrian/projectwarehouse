<?php
session_start();
include 'db.php';
//Kondisi Supaya User & Non User tidak dapat akses page ini
if ($_SESSION['role_login'] == 'user') {
    echo '<script>window.location="logout.php"</script>';
} else if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}
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
    <!--------------------- Additional CSS ----------------------------->
    <style>
        .box1 {
            margin: 10px 0 -10px 0;
            display: flex;
        }

        .section .container .box1 button {
            background-color: #fff;
            color: black;
            display: inline-block;
            font-size: 20px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 8px;
            padding: 5px;
            transition-duration: 0.4s;
        }

        .section .container .box1 button:hover {
            background-color: black;
            color: white;
        }

        #stocking {
            margin-right: 10px;
            padding: 3px;
            border: 1px solid;
            background-color: white;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.5s;
        }

        #stocking:hover {
            background-color: black;
            color: white;
        }
    </style>

</head>

<body>
    <!--------------------------------------------------------------------------------- ADMIN ---------------------------------------------------------------------------->
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
                <h2>Stocking Barang</h2>
                <div class="box1">
                    <button><a href="product-data.php" style="text-decoration: none ;">Kembali</a></button><br><br>
                </div>
                <br>
                <!-----------------------search-------------------------------------->
                <div class="search">
                    <form action="search-stocking.php" method="GET">
                        <input type="text" name="search" placeholder="cari produk">
                        <input type="submit" name="cari" value="Cari">
                    </form>
                </div>
                <br>
                <div class="box">
                    <table border="1" cellspacing="0" class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kategori</th>
                                <th>Nama Barang</th>
                                <th>Gambar</th>
                                <th>Status</th>
                                <th>Stock</th>
                                <th width="5%">Batas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $produk = mysqli_query($conn, "SELECT * FROM data_product LEFT JOIN data_category  USING (category_id) WHERE office_id = '" . $idkantoradmin . "' ORDER BY product_name ");
                            if (mysqli_num_rows($produk) > 0) {
                                while ($row = mysqli_fetch_array($produk)) {
                                    $idperwakilan = $row['office_id'];
                                    $namaperwakilan = mysqli_query($conn, "SELECT * FROM data_office WHERE office_id = '" . $idperwakilan . "' ");
                                    $row_np = mysqli_fetch_array($namaperwakilan);
                            ?>
                                    <tr>
                                        <td><?php echo $no++ ?></td>
                                        <td><?php echo $row['category_name'] ?></td>
                                        <td><?php echo $row['product_name'] ?></td>
                                        <td><a href="produk/<?php echo $row['product_image'] ?>"> <img src="produk/<?php echo $row['product_image'] ?>" width="50px"></a></td>
                                        <td>
                                            <center><?php echo ($row['product_status'] == 0) ? 'Tidak AKtif' : 'Aktif' ?></center>
                                        </td>
                                        <?php
                                        $query_unit = mysqli_query($conn, "SELECT * FROM data_unit WHERE unit_id = '" . $row['unit_id'] . "' ");
                                        $fetch_unit = mysqli_fetch_array($query_unit);
                                        if ($row['stock'] > $row['stock_point']) {
                                        ?>
                                            <td>
                                                <center>
                                                    <?php echo $row['stock'], " ", $fetch_unit['unit_name']; ?>
                                                </center>
                                            </td>
                                        <?php
                                        } else {
                                        ?>
                                            <td style="color: red ;">
                                                <center>
                                                    <?php echo $row['stock'], " ", $fetch_unit['unit_name']; ?>
                                                </center>
                                            </td>
                                        <?php
                                        }
                                        ?>
                                        <td style="text-align: center ;"><?php echo $row['stock_point'] ?></td>
                                        <td>
                                            <center>
                                                <a id="stocking" href="edit-stocking-product.php?id=<?php echo $row['product_id'] ?>">Stock Barang</a>
                                            </center>
                                        </td>
                                    </tr>
                                <?php }
                            } else { ?>
                                <tr>
                                    <td colspan="8" style="text-align: center ;">Tidak Ada Data</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!--------------------------------------------------------------------------------- SUPER ADMIN ---------------------------------------------------------------------------->
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

        <!-- Content -->
        <div class="section">
            <div class="container">
                <h3>Data Barang</h3>
                <div class="box1">
                    <button><a href="add-product.php" style="text-decoration: none ;">Tambah Data Produk</a></button><br><br>
                    <button><a href="stocking-product.php" style="text-decoration: none ;">Stocking Barang</a></button>
                    <button><a href="stocking-history.php" style="text-decoration: none ;">Riwayat Stocking</a></button>
                </div>
                <div class="box">
                    <p><a href="add-product.php">Tambah Data Produk</a></p><br>
                    <table border="1" cellspacing="0" class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Perwakilan</th>
                                <th>ID Kategori</th>
                                <th>Kategori</th>
                                <th>ID Produk</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Gambar</th>
                                <th>Status</th>
                                <th>Stock</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $produk = mysqli_query($conn, "SELECT * FROM data_product LEFT JOIN data_category  USING (category_id) ORDER BY product_id DESC ");
                            if (mysqli_num_rows($produk) > 0) {
                                while ($row = mysqli_fetch_array($produk)) {
                                    $idperwakilan = $row['office_id'];
                                    $namaperwakilan = mysqli_query($conn, "SELECT * FROM data_office WHERE office_id = '" . $idperwakilan . "' ");
                                    $row_np = mysqli_fetch_array($namaperwakilan);
                            ?>
                                    <tr>
                                        <td><?php echo $no++ ?></td>
                                        <td><?php echo $row_np['office_name'] ?></td>
                                        <td><?php echo $row['category_id'] ?></td>
                                        <td><?php echo $row['category_name'] ?></td>
                                        <td><?php echo $row['product_id'] ?></td>
                                        <td><?php echo $row['product_name'] ?></td>
                                        <td>Rp. <?php echo number_format($row['product_price']) ?></td>
                                        <td><a href="produk/<?php echo $row['product_image'] ?>"> <img src="produk/<?php echo $row['product_image'] ?>" width="50px"></a></td>
                                        <td><?php echo ($row['product_status'] == 0) ? 'Tidak AKtif' : 'Aktif' ?></td>
                                        <td><?php echo ($row['stock']) ?></td>
                                        <td>
                                            <a href="edit-product.php?id=<?php echo $row['product_id'] ?>">Edit</a> || <a href="delete-data.php?idp=<?php echo $row['product_id'] ?>" onclick="return confirm('R U Sure about dat ?') ">Hapus</a>
                                        </td>
                                    </tr>
                                <?php }
                            } else { ?>
                                <tr>
                                    <td colspan="8">Tidak Ada Data</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
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
    <?php
    }
    ?>

</body>

</html>