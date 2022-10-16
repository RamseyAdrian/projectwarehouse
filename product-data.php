<?php
session_start();
include 'db.php';
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
    <title>KP Ombudsman</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
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
            margin: 4px 20px 4px 0;
            cursor: pointer;
            border-radius: 8px;
            padding: 5px;
            transition-duration: 0.4s;
        }

        .section .container .box1 button:hover {
            background-color: black;
            color: white;
        }

        .section .container .box table tbody tr td button {
            font-size: 17px;
            background-color: white;
            color: black;
            border-radius: 5px;
            padding: 2px;
        }

        .section .container .box table tbody tr td button a {
            text-decoration: none;
            font-weight: bold;
        }

        .section .container .box table tbody tr td button:hover {
            background-color: black;
            color: white;
            transition-duration: 0.3s;
        }
    </style>

</head>

<body>
    <!--------------------------------------------------------------------------------- ADMIN ---------------------------------------------------------------------------->
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
                <h2>Data Barang</h2>
                <div class="box1">
                    <button><a href="add-product.php" style="text-decoration: none ; font-weight:bold;">Tambah Data Produk</a></button><br><br>
                    <button><a href="stocking-product.php" style="text-decoration: none ; font-weight:bold;">Stocking Barang</a></button>
                    <!-- <button><a href="min-stocking-product.php" style="text-decoration: none ; font-weight:bold;">Barang Keluar</a></button> -->
                    <button><a href="stocking-history.php" style="text-decoration: none ; font-weight:bold;">Riwayat Stocking</a></button>
                </div>
                <br>
                <div class="box">

                    <table border="1" cellspacing="0" class="table">
                        <thead>
                            <tr>
                                <th width="60px">No</th>
                                <!-- <th>Perwakilan</th>
                                <th>ID Kategori</th> -->
                                <th>Kategori</th>
                                <th>ID Barang</th>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                                <!-- <th>Deskripsi</th> -->
                                <th>Gambar</th>
                                <th>Status</th>
                                <th>Stock</th>
                                <th width="150px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $produk = mysqli_query($conn, "SELECT * FROM data_product LEFT JOIN data_category  USING (category_id) WHERE office_id = '" . $idkantoradmin . "' ORDER BY product_id DESC ");
                            if (mysqli_num_rows($produk) > 0) {
                                while ($row = mysqli_fetch_array($produk)) {
                                    $idperwakilan = $row['office_id'];
                                    $namaperwakilan = mysqli_query($conn, "SELECT * FROM data_office WHERE office_id = '" . $idperwakilan . "' ");
                                    $row_np = mysqli_fetch_array($namaperwakilan);
                            ?>
                                    <tr>
                                        <td><?php echo $no++ ?></td>
                                        <!-- <td><?php echo $row_np['office_name'] ?></td>
                                        <td><?php echo $row['category_id'] ?></td> -->
                                        <td><?php echo $row['category_name'] ?></td>
                                        <td><?php echo $row['product_id'] ?></td>
                                        <td><?php echo $row['product_name'] ?></td>
                                        <td>Rp. <?php echo number_format($row['product_price']) ?></td>
                                        <!-- <td><?php echo $row['product_description'] ?></td> -->
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
        <!--------------------------------------------------------------------------------- SUPER ADMIN ---------------------------------------------------------------------------->
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
                <h2>Data Barang</h2>
                <div class="box1">
                    <button><a href="add-product.php" style="text-decoration: none ;">Tambah Data Produk</a></button><br><br>
                    <button><a href="stocking-history.php" style="text-decoration: none ;">Riwayat Stocking</a></button>
                </div>
                <div class="box">
                    <table border="1" cellspacing="0" class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Perwakilan</th>
                                <th>Kategori</th>
                                <th>ID Barang</th>
                                <th>Nama Produk</th>
                                <!-- <th>Deskripsi</th> -->
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
                                        <td><?php echo $row['category_name'] ?></td>
                                        <td><?php echo $row['product_id'] ?></td>
                                        <td><?php echo substr($row['product_name'], 0, 20) ?></td>
                                        <!-- <td><?php echo $row['product_description'] ?></td> -->
                                        <td><a href="produk/<?php echo $row['product_image'] ?>"> <img src="produk/<?php echo $row['product_image'] ?>" width="50px"></a></td>
                                        <td><?php echo ($row['product_status'] == 0) ? 'Tidak AKtif' : 'Aktif' ?></td>
                                        <td><?php echo ($row['stock']) ?></td>
                                        <td>
                                            <center>
                                                <button>
                                                    <a id="buttdetail" href="edit-product.php?id=<?php echo $row['product_id'] ?>">Edit</a>
                                                </button>
                                                <button>
                                                    <a id="buttdetail" href="delete-data.php?idp=<?php echo $row['product_id'] ?>" onclick="return confirm('Yakin Hapus Barang ?') ">Hapus</a> </button>
                                            </center>

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
    <?php
    }
    ?>

</body>

</html>