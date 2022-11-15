<?php
session_start();
include 'db.php';

if ($_SESSION['role_login'] == 'user' || $_SESSION['role_login'] == 'super') {
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

    <div class="section">
        <div class="container">
            <h2>Pencarian Stocking Barang</h2>
            <div class="box1">
                <button><a href="stocking-product.php" style="text-decoration: none ; font-weight:bold;">Kembali</a></button><br><br>
            </div>
            <br>
            <!-----------------------search-------------------------------------->
            <div class="search">
                <form action="search-stocking.php" method="GET">
                    <input type="text" name="search" placeholder="cari produk" value="<?php echo $_GET['search'] ?>">
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
                        $stocking = mysqli_query($conn, "SELECT * FROM data_product LEFT JOIN data_category USING (category_id) LEFT JOIN data_unit USING (unit_id) WHERE office_id = '" . $idkantoradmin . "' AND product_name LIKE '%" . $_GET['search'] . "%' ORDER BY product_name ");
                        if (mysqli_num_rows($stocking) > 0) {
                            while ($fetch_stocking = mysqli_fetch_assoc($stocking)) {
                        ?>
                                <tr>
                                    <td><?php echo $no++ ?></td>
                                    <td><?php echo $fetch_stocking['category_name'] ?></td>
                                    <td><?php echo $fetch_stocking['product_name'] ?></td>
                                    <td><img src="produk/<?php echo $fetch_stocking['product_image'] ?>" width="50px"></td>
                                    <td>
                                        <center>
                                            <?php echo ($fetch_stocking['product_status'] == 0) ? 'Tidak Aktif' : 'Aktif' ?>
                                        </center>
                                    </td>
                                    <?php
                                    if ($fetch_stocking['stock'] > $fetch_stocking['stock_point']) {
                                    ?>
                                        <td><?php echo $fetch_stocking['stock'], " ", $fetch_stocking['unit_name'] ?></td>
                                    <?php
                                    } else {
                                    ?>
                                        <td style="color: red ;"><?php echo $fetch_stocking['stock'], " ", $fetch_stocking['unit_name'] ?></td>
                                    <?php
                                    }
                                    ?>
                                    <td><?php echo $fetch_stocking['stock_point'] ?></td>
                                    <td>
                                        <center>
                                            <a id="stocking" href="edit-stocking-product.php?id=<?php echo $fetch_stocking['product_id'] ?>">Stock Barang</a>
                                        </center>
                                    </td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>