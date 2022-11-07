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

        .box table tbody tr td .abutt {
            margin-right: 10px;
            padding: 2px;
            border: 1px solid;
            background-color: white;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.5s;
        }

        .box table tbody tr td .abutt:hover {
            color: white;
            background-color: black;
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
                <h2>Data Barang</h2>
                <div class="box1">
                    <button><a href="add-product.php" style="text-decoration: none ; font-weight:bold;">Tambah Data Produk</a></button><br><br>
                    <button><a href="stocking-product.php" style="text-decoration: none ; font-weight:bold;">Stocking Barang</a></button>
                    <button><a href="stocking-history.php" style="text-decoration: none ; font-weight:bold;">Riwayat Stocking</a></button>
                </div>
                <br>
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
                            $produk = mysqli_query($conn, "SELECT * FROM data_product LEFT JOIN data_category  USING (category_id) WHERE office_id = '" . $idkantoradmin . "' ORDER BY product_name ");
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
                                        <td> <img src="produk/<?php echo $row['product_image'] ?>" width="50px"></td>
                                        <td><?php echo ($row['product_status'] == 0) ? 'Tidak AKtif' : 'Aktif' ?></td>
                                        <td><?php echo ($row['stock']) ?></td>
                                        <td style="text-align:center ;">
                                            <a class="abutt" href="edit-product.php?id=<?php echo $row['product_id'] ?>&idoffice=<?php echo $idkantoradmin ?>">Edit</a><a class="abutt" href="delete-data.php?idp=<?php echo $row['product_id'] ?>&idoffice=<?php echo $idkantoradmin ?>" onclick="return confirm('R U Sure about dat ?') ">Hapus</a>
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
        </div>x
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

        <!---------------------- Content ----------------------------------->
        <div class="section">
            <div class="container">
                <h2>Data Barang</h2>
                <div class="box1">
                    <button><a href="add-product.php" style="text-decoration: none ;">Tambah Data Produk</a></button><br><br>
                    <button><a href="stocking-history.php" style="text-decoration: none ;">Riwayat Stocking</a></button>
                </div>
                <br>
                <div class="box">
                    <form action="" method="POST">
                        <h3>Perwakilan</h3>
                        <select name="perwakilan" class="input-control">
                            <option value="">--Pilih--</option>
                            <?php
                            $query_office = mysqli_query($conn, "SELECT * FROM data_office ORDER BY office_id");
                            while ($fa_office = mysqli_fetch_array($query_office)) {
                            ?>
                                <option value="<?php echo $fa_office['office_id'] ?>"><?php echo $fa_office['office_name'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                        <input type="submit" name="submit" value="Submit" class="btn">
                    </form>
                </div>
                <?php
                if (isset($_POST['submit'])) {
                ?>
                    <div class="box">
                        <?php
                        $query_office2 = mysqli_query($conn, "SELECT * FROM data_office WHERE office_id = '" . $_POST['perwakilan'] . "' ");
                        while ($fa_office2 = mysqli_fetch_array($query_office2)) {
                        ?>
                            <center>
                                <h2><?php echo $fa_office2['office_name'] ?></h2><br>
                            </center>
                        <?php
                        }
                        ?>
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
                                $produk = mysqli_query($conn, "SELECT * FROM data_product LEFT JOIN data_category  USING (category_id) WHERE office_id = '" . $_POST['perwakilan'] . "' ORDER BY product_name LIMIT 20");
                                if (mysqli_num_rows($produk) > 0) {
                                    while ($row = mysqli_fetch_array($produk)) {
                                        $idperwakilan = $row['office_id'];
                                        $namasatuan = mysqli_query($conn, "SELECT * FROM data_unit WHERE unit_id = '" . $row['unit_id'] . "' ");
                                        $fa_satuan = mysqli_fetch_array($namasatuan);
                                ?>
                                        <tr>
                                            <td><?php echo $no++ ?></td>
                                            <td><?php echo $row['category_name'] ?></td>
                                            <td><?php echo $row['product_id'] ?></td>
                                            <td><?php echo substr($row['product_name'], 0, 20) ?></td>
                                            <td><?php echo $fa_satuan['unit_name'] ?></td>
                                            <td> <img src="produk/<?php echo $row['product_image'] ?>" width="50px"></td>
                                            <td><?php echo ($row['product_status'] == 0) ? 'Tidak AKtif' : 'Aktif' ?></td>
                                            <td><?php echo ($row['stock']) ?></td>
                                            <td>
                                                <center>
                                                    <button>
                                                        <a id="buttdetail" href="edit-product.php?id=<?php echo $row['product_id'] ?>&idoffice=<?php echo $_POST['perwakilan'] ?>">Edit</a>
                                                    </button>
                                                    <button>
                                                        <a id="buttdetail" href="delete-data.php?idp=<?php echo $row['product_id'] ?>&idoffice=<?php echo $_POST['perwakilan'] ?>" onclick="return confirm('Yakin Hapus Barang ?') ">Hapus</a> </button>
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
                    <?php
                    $produk2 = mysqli_query($conn, "SELECT * FROM data_product LEFT JOIN data_category  USING (category_id) WHERE office_id = '" . $_POST['perwakilan'] . "' ORDER BY product_name ");
                    if (mysqli_num_rows($produk2) > 20) {
                    ?>
                        <center>
                            <a href="more-product.php?id=<?php echo $_POST['perwakilan'] ?>" style="font-size: 15px ; text-decoration :none;"><button class="btn">Lihat Lebih Banyak Produk</button></a>
                        </center>
                <?php
                    }
                }
                ?>
                <?php

                ?>
            </div>
        </div>
    <?php
    }
    ?>

</body>

</html>