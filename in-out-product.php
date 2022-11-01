<?php
session_start();
include 'db.php';
//Kondisi Supaya User & Non User tidak dapat akses page ini
if ($_SESSION['role_login'] == 'user') {
    echo '<script>window.location="logout.php"</script>';
} else if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}

$piequery = "SELECT * FROM data_product";
$piequeryrecords = mysqli_query($conn, $piequery);
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
    <!--------------------- jQuery ----------------------------->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <script src="https://www.google.com/jsapi"></script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css" />
    <!--------------------- Font Awesome ----------------------------->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
                    $keranjang = mysqli_query($conn, "SELECT * FROM data_transaction WHERE office_id = '" . $kantor_admin . "' AND status = 'Diproses Admin' ORDER BY cart_id");
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
                <h2>Keluar Masuk Barang</h2>
                <div class="box">
                    <br>
                    <table border="1" cellspacing="0" class="table">
                        <thead>
                            <tr>
                                <th>ID Barang</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Satuan</th>
                                <th>Masuk</th>
                                <th>Keluar</th>
                                <th>Tersedia</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query_barang = mysqli_query($conn, "SELECT * FROM data_product LEFT JOIN data_category USING (category_id) LEFT JOIN data_unit USING (unit_id) WHERE office_id = '" . $kantor_admin . "' ORDER BY product_name ");
                            if (mysqli_num_rows($query_barang) > 0) {
                                while ($fetch_barang = mysqli_fetch_array($query_barang)) {
                            ?>
                                    <tr>
                                        <td><?php echo $fetch_barang['product_id'] ?></td>
                                        <td><?php echo $fetch_barang['product_name'] ?></td>
                                        <td><?php echo $fetch_barang['category_name'] ?></td>
                                        <td><?php echo $fetch_barang['unit_name'] ?></td>
                                        <?php
                                        $query_stocking = mysqli_query($conn, "SELECT * FROM stocking_item WHERE product_id = '" . $fetch_barang['product_id'] . "' AND office_id = $kantor_admin ");
                                        if (mysqli_num_rows($query_stocking) > 0) {
                                            $masuk = 0;
                                            while ($fetch_stocking = mysqli_fetch_array($query_stocking)) {
                                                $masuk += $fetch_stocking['quantity'];
                                            }
                                        ?>
                                            <td><?php echo $masuk ?></td>
                                        <?php
                                        } else {
                                        ?>
                                            <td><?php echo $fetch_barang['stock'] ?></td>
                                        <?php
                                        }
                                        $query_trans = mysqli_query($conn, "SELECT * FROM transaction_history WHERE office_id = '" . $kantor_admin . "' AND product_id = '" . $fetch_barang['product_id'] . "' ");
                                        if (mysqli_num_rows($query_trans) > 0) {
                                            $keluar = 0;
                                            while ($fetch_trans = mysqli_fetch_array($query_trans)) {
                                                $keluar += $fetch_trans['quantity'];
                                            }
                                        ?>
                                            <td><?php echo $keluar ?></td>
                                        <?php
                                        } else {
                                        ?>
                                            <td>0</td>
                                        <?php
                                        }
                                        ?>
                                        <td><?php echo $fetch_barang['stock'] ?></td>
                                    </tr>
                                <?php
                                }
                            } else {
                                ?>
                                <td colspan="7">Tidak Ada Data</td>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <?php
    }
    ?>

</body>

</html>