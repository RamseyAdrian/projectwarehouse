<?php
session_start();
include 'db.php';
// Kondisi Supaya User, Admin, & Non User tidak dapat akses page ini
if ($_SESSION['role_login'] == 'user' && $_SESSION['role_login'] == 'admin') {
    echo '<script>window.location="logout.php"</script>';
} else if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}

$produk = mysqli_query($conn, "SELECT * FROM data_product WHERE office_id = '" . $_GET['id'] . "' ");
if (mysqli_num_rows($produk) == 0) {
    echo '<script>window.location="product-data.php"</script>';
}
$p = mysqli_fetch_object($produk);

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
    <!--------------------- Sweet Alert CDN ----------------------------->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css" rel="stylesheet" />
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            <?php
            $query_office = mysqli_query($conn, "SELECT * FROM data_office WHERE office_id = '" . $_GET['id'] . "' ");
            while ($fa_office = mysqli_fetch_array($query_office)) {
            ?>
                <h2>Data Barang <?php echo $fa_office['office_name'] ?></h2>
            <?php
            }
            ?>
            <div class="box1">
                <button><a href="product-data.php" style="text-decoration: none ; font-weight:bold;">Kembali</a></button><br><br>
            </div>
            <br>
            <!-----------------------search-------------------------------------->
            <div class="search">
                <form action="search-product.php" method="GET">
                    <input type="text" name="search" placeholder="cari produk">
                    <input type="submit" name="cari" value="Cari">
                </form>
            </div>
            <div class="box">

                <table border="1" cellspacing="0" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kategori</th>
                            <th>ID Barang</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th>Gambar</th>
                            <th>Status</th>
                            <th>Stock</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $produk = mysqli_query($conn, "SELECT * FROM data_product LEFT JOIN data_category  USING (category_id) WHERE office_id = '" . $_GET['id'] . "' ORDER BY product_name  ");
                        if (mysqli_num_rows($produk) > 0) {
                            while ($row = mysqli_fetch_array($produk)) {
                                $idperwakilan = $row['office_id'];
                                $namaperwakilan = mysqli_query($conn, "SELECT * FROM data_office WHERE office_id = '" . $idperwakilan . "' ");
                                $row_np = mysqli_fetch_array($namaperwakilan);
                                $namasatuan = mysqli_query($conn, "SELECT * FROM data_unit WHERE unit_id = '" . $row['unit_id'] . "' ");
                                $fa_satuan = mysqli_fetch_array($namasatuan);
                        ?>
                                <tr>
                                    <td><?php echo $no++ ?></td>
                                    <td><?php echo $row['category_name'] ?></td>
                                    <td><?php echo $row['product_id'] ?></td>
                                    <td><?php echo $row['product_name'] ?></td>
                                    <td><?php echo $fa_satuan['unit_name'] ?></td>
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
</body>

</html>