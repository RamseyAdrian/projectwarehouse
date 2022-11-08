<?php
session_start();
include 'db.php';
//Kondisi Supaya User & Non User tidak dapat akses page ini
if ($_SESSION['role_login'] == 'user') {
    echo '<script>window.location="logout.php"</script>';
} else if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}

$kantor_admin = $_SESSION['a_global']->office_id;

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
$_SESSION['jumlah_pesanan'] = $jml_keranjang;
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
    <!--------------------- Additional CSS ----------------------------->
    <style>
        #buttdetail {
            font-size: 17px;
            background-color: white;
            color: black;
            border-radius: 5px;
            padding: 2px;
        }

        #buttdetail a {
            text-decoration: none;
            font-weight: bold;
        }

        #buttdetail:hover {
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
        <!---------------------- header ----------------------------------->
        <header>
            <div class="container">
                <h1><a href="dashboard.php"><img style="width: 80px ; margin-bottom :-10px ;" src="img/logo-ombudsman2.png" alt=""> Gudang Ombudsman</a></h1>
                <ul style="margin-top: 20px ;">
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="profile.php">Profil</a></li>
                    <li><a href="product-data.php">Data Barang</a></li>
                    <li><a href="user-data.php">Data User</a></li>
                    <li><a href="order-table.php">Pesanan (<?php echo $_SESSION['jumlah_pesanan']; ?>)</a></li>
                    <li><a href="logout.php">Keluar</a></li>
                </ul>
            </div>
        </header>

        <!---------------------- Content ----------------------------------->

        <div class="section">
            <div class="container">
                <div class="box-dash-0">
                    <i class="fa fa-area-chart fa-2x" aria-hidden="true"></i>
                    <h2 style="font-weight:bolder;">Dashboard</h2>
                </div>
                <?php
                $idperwakilan = $_SESSION['a_global']->office_id;
                $namaperwakilan = mysqli_query($conn, "SELECT * FROM data_office WHERE office_id = '" . $idperwakilan . "' ");
                $row_np = mysqli_fetch_array($namaperwakilan);
                ?>
                <div class="box">
                    <h2>Admin <?php echo $row_np['office_name'] ?> </h2>
                    <h4><?php echo $_SESSION['a_global']->admin_name ?></h4>
                    <h4>Admin ID : <?php echo $_SESSION['a_global']->admin_id ?></h4>
                </div>
                <div class="box-dash-1">
                    <div class="box">
                        <?php
                        if ($jml_keranjang == 0) {
                        ?>
                            <h2 style="color:green ;">Belum Ada Pesanan </h2>
                            <h3>Pastikan Semua Stock Aman Ya ! <br> Supaya Transaksi Barang Lancar</h3>
                        <?php
                        } else {
                        ?>
                            <a href="order-table.php" style="text-decoration: none ; cursor : pointer ;">
                                <h2 style="color:red ;">Ada <?php echo $jml_keranjang ?> Pesanan User !</h2>
                                <h3 style="color:red ;">Segera Proses</h3>
                            </a>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="box">
                        <h2>Jumlah User</h2>
                        <?php
                        $user_query = mysqli_query($conn, "SELECT * FROM data_user WHERE office_id = '" . $kantor_admin . "' ");
                        if (mysqli_num_rows($user_query) > 0) {
                            $jumlah_user = 0;
                            while ($fetch_user = mysqli_fetch_array($user_query)) {
                                $jumlah_user++;
                            }
                        ?>
                            <div class="box-item">
                                <i class="fa fa-user fa-2x" aria-hidden="true"></i>
                                <h1 style="text-align: center ;"><?php echo $jumlah_user ?></h1>
                            </div>
                        <?php
                        } else {
                        ?>
                            <h1 style="text-align: center ;">Tidak Ada User</h1>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="box">
                        <h2>Jumlah Barang</h2>
                        <?php
                        $barang_query = mysqli_query($conn, "SELECT * FROM data_product WHERE office_id = '" . $kantor_admin . "' ");
                        if (mysqli_num_rows($barang_query) > 0) {
                            $jumlah_barang = 0;
                            while ($fetch_barang = mysqli_fetch_array($barang_query)) {
                                $jumlah_barang++;
                            }
                        ?>
                            <div class="box-item">
                                <i class="fa fa-archive fa-2x" aria-hidden="true"></i>
                                <h1 style="text-align: center ;"><?php echo $jumlah_barang ?></h1>
                            </div>
                        <?php
                        } else {
                        ?>
                            <h1 style="text-align: center ;">Tidak Ada Barang</h1>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="box">
                        <h2>Jumlah Transaksi</h2>
                        <?php
                        $trans_query = mysqli_query($conn, "SELECT * FROM data_order WHERE office_id = '" . $kantor_admin . "' AND status != 'Diproses Admin' ");
                        if (mysqli_num_rows($trans_query) > 0) {
                            $jumlah_trans = 0;
                            while ($fetch_trans = mysqli_fetch_array($trans_query)) {
                                $jumlah_trans++;
                            }
                        ?>
                            <div class="box-item">
                                <i class="fa fa-shopping-basket fa-2x" aria-hidden="true"></i>
                                <h1 style="text-align: center ;"><?php echo $jumlah_trans ?></h1>
                            </div>
                        <?php
                        } else {
                        ?>
                            <h1 style="text-align: center ;">Tidak Ada Transaksi</h1>
                        <?php
                        }
                        ?>
                    </div>
                </div>

                <div class="box">
                    <h2>Pengambilan Barang</h2>
                    <br>
                    <table border="1" cellspacing="0" class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Pesanan</th>
                                <th>Barang</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $where_status = "OR data_order.status = 'Ambil Sebagian' ";
                            $trans = mysqli_query($conn, "SELECT * FROM data_order WHERE data_order.office_id = '" . $kantor_admin . "' AND data_order.status = 'Akan Diambil' $where_status ORDER BY times_updated");
                            if (mysqli_num_rows($trans) > 0) {
                                while ($fo_trans = mysqli_fetch_array($trans)) {
                            ?>
                                    <tr>
                                        <td><?php echo $no++ ?></td>
                                        <td><?php echo $fo_trans['cart_id'] ?></td>
                                        <td>
                                            <?php
                                            $query_trans = mysqli_query($conn, "SELECT * FROM data_transaction WHERE data_transaction.cart_id = '" . $fo_trans['cart_id'] . "' ");
                                            $jumlah_item = mysqli_num_rows($query_trans);
                                            if ($jumlah_item > 0) {
                                                $neff = 0;
                                                while ($fa_trans = mysqli_fetch_array($query_trans)) {
                                                    echo $fa_trans['product_name'], " (", $fa_trans['quantity'], " ", $fa_trans['unit_name'], ")";
                                                    $neff++;
                                                    if ($neff < $jumlah_item) {
                                                        print ", ";
                                                    }
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <center>
                                                <button id="buttdetail"><a href="pickup-detail.php?id=<?php echo $fo_trans['cart_id'] ?>">Proses</a></button>
                                            </center>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            <?php
                            } else {
                            ?>
                                <td style="text-align:center ;" colspan="8">Tidak Ada Data</td>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="box">
                    <h2>Notifikasi Restock Barang</h2>
                    <br>
                    <table border="1" cellspacing="0" class="table">
                        <thead>
                            <tr>
                                <th>ID Barang</th>
                                <th>Kategori</th>
                                <th>Nama Barang</th>
                                <th>Stock</th>
                                <th>Batas</th>
                                <th>Restock</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stock_query = mysqli_query($conn, "SELECT * FROM data_product LEFT JOIN data_category USING (category_id) LEFT JOIN data_unit USING (unit_id) WHERE office_id = '" . $kantor_admin . "' AND stock_point >= stock ");
                            if (mysqli_num_rows($stock_query) > 0) {
                                while ($fetch_stock = mysqli_fetch_array($stock_query)) {
                            ?>
                                    <tr>
                                        <td><?php echo $fetch_stock['product_id'] ?></td>
                                        <td><?php echo $fetch_stock['category_name'] ?></td>
                                        <td><?php echo $fetch_stock['product_name'] ?></td>
                                        <td><?php echo $fetch_stock['stock'] ?></td>
                                        <td><?php echo $fetch_stock['stock_point'] ?></td>
                                        <td>
                                            <center>
                                                <button id="buttdetail"><a href="edit-stocking-product.php?id=<?php echo $fetch_stock['product_id'] ?>">Stock Barang</a></button>
                                            </center>
                                        </td>
                                    </tr>
                                <?php
                                }
                            } else {
                                ?>
                                <td style="text-align:center ;" colspan="8">Tidak Ada Data</td>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="box">
                    <h2>Keluar Masuk Barang</h2>
                    <br>
                    <table border="1" cellspacing="0" class="table">
                        <thead>
                            <tr>
                                <th>ID Barang</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Satuan</th>
                                <th>Stock Awal</th>
                                <th>Masuk</th>
                                <th>Keluar</th>
                                <th>Tersedia</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query_barang = mysqli_query($conn, "SELECT * FROM data_product LEFT JOIN data_category USING (category_id) LEFT JOIN data_unit USING (unit_id) WHERE office_id = '" . $kantor_admin . "' ORDER BY product_name LIMIT 5");
                            if (mysqli_num_rows($query_barang) > 0) {
                                while ($fetch_barang = mysqli_fetch_array($query_barang)) {
                            ?>
                                    <tr>
                                        <td><?php echo $fetch_barang['product_id'] ?></td>
                                        <td><?php echo $fetch_barang['product_name'] ?></td>
                                        <td><?php echo $fetch_barang['category_name'] ?></td>
                                        <td><?php echo $fetch_barang['unit_name'] ?></td>
                                        <td><?php echo $fetch_barang['initial_stock'] ?></td>
                                        <?php
                                        $reset = "AND reset_status = '0' ";
                                        $query_stocking = mysqli_query($conn, "SELECT * FROM stocking_item WHERE product_id = '" . $fetch_barang['product_id'] . "' AND office_id = '" . $kantor_admin . "' $reset");
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
                    <br>
                    <a href="in-out-product.php"><input type="submit" class="btn" value="Lihat Lebih Banyak"></a>
                </div>
            </div>
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

        <!---------------------- Content ----------------------------------->

        <div class="section">
            <div class="container">
                <div class="box-dash-0">
                    <i class="fa fa-area-chart fa-2x" aria-hidden="true"></i>
                    <h2 style="font-weight:bolder;">Dashboard</h2>
                </div>
                <?php
                $idperwakilan = $_SESSION['a_global']->office_id;
                $namaperwakilan = mysqli_query($conn, "SELECT * FROM data_office WHERE office_id = '" . $idperwakilan . "' ");
                $row_np = mysqli_fetch_array($namaperwakilan);
                ?>
                <div class="box-dash-0">
                    <div class="box" style="width : 80% ;">
                        <h1>Super Admin Ombudsman RI</h1><br>
                        <h2><?php echo $_SESSION['a_global']->super_name ?></h2>
                    </div>
                    <a href="in-out-product.php" style="text-decoration: none ;">
                        <div id="box-inout">
                            <h2 style="color: red ;">Riwayat Keluar Masuk Barang</h2>
                            <h3>Klik Untuk Lihat Lebih Lanjut</h3>
                        </div>
                    </a>
                </div>
                <div class="box-dash-1">
                    <div class="box">
                        <h2>Jumlah Perwakilan</h2>
                        <?php
                        $perwakilan = mysqli_query($conn, "SELECT * FROM data_office");
                        if (mysqli_num_rows($perwakilan) > 0) {
                            $jumlah_perwakilan = 0;
                            while ($fetch_perwakilan = mysqli_fetch_array($perwakilan)) {
                                $jumlah_perwakilan++;
                            }
                        ?>
                            <div class="box-item">
                                <i class="fa fa-building fa-2x" aria-hidden="true"></i>
                                <h1 style="text-align: center ;"><?php echo $jumlah_perwakilan ?></h1>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="box">
                        <h2>Total Admin</h2>
                        <?php
                        $admin_query = mysqli_query($conn, "SELECT * FROM data_admin ");
                        if (mysqli_num_rows($admin_query) > 0) {
                            $jumlah_admin = 0;
                            while ($fetch_admin = mysqli_fetch_array($admin_query)) {
                                $jumlah_admin++;
                            }
                        ?>
                            <div class="box-item">
                                <i class="fa fa-user fa-2x" aria-hidden="true"></i>
                                <h1 style="text-align: center ;"><?php echo $jumlah_admin ?></h1>
                            </div>
                        <?php
                        } else {
                        ?>
                            <h1 style="text-align: center ;">Tidak Ada User</h1>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="box">
                        <h2>Total User</h2>
                        <?php
                        $user_query = mysqli_query($conn, "SELECT * FROM data_user ");
                        if (mysqli_num_rows($user_query) > 0) {
                            $jumlah_user = 0;
                            while ($fetch_user = mysqli_fetch_array($user_query)) {
                                $jumlah_user++;
                            }
                        ?>
                            <div class="box-item">
                                <i class="fa fa-users fa-2x" aria-hidden="true"></i>
                                <h1 style="text-align: center ;"><?php echo $jumlah_user ?></h1>
                            </div>
                        <?php
                        } else {
                        ?>
                            <h1 style="text-align: center ;">Tidak Ada User</h1>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="box">
                        <h2>Jumlah Barang</h2>
                        <?php
                        $barang_query = mysqli_query($conn, "SELECT * FROM data_product");
                        if (mysqli_num_rows($barang_query) > 0) {
                            $jumlah_barang = 0;
                            while ($fetch_barang = mysqli_fetch_array($barang_query)) {
                                $jumlah_barang++;
                            }
                        ?>
                            <div class="box-item">
                                <i class="fa fa-archive fa-2x" aria-hidden="true"></i>
                                <h1 style="text-align: center ;"><?php echo $jumlah_barang ?></h1>
                            </div>
                        <?php
                        } else {
                        ?>
                            <h1 style="text-align: center ;">Tidak Ada Barang</h1>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="box">
                        <h2>Jumlah Transaksi</h2>
                        <?php
                        $trans_query = mysqli_query($conn, "SELECT * FROM data_order WHERE status != 'Diproses Admin' ");
                        if (mysqli_num_rows($trans_query) > 0) {
                            $jumlah_trans = 0;
                            while ($fetch_trans = mysqli_fetch_array($trans_query)) {
                                $jumlah_trans++;
                            }
                        ?>
                            <div class="box-item">
                                <i class="fa fa-shopping-basket fa-2x" aria-hidden="true"></i>
                                <h1 style="text-align: center ;"><?php echo $jumlah_trans ?></h1>
                            </div>
                        <?php
                        } else {
                        ?>
                            <h1 style="text-align: center ;">Tidak Ada Transaksi</h1>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="box-new-product-head">
                    <div class="box">
                        <h2>Produk Terbaru</h2>
                    </div>
                </div>
                <div class="box-new-product">
                    <?php
                    $barang_query = mysqli_query($conn, "SELECT * FROM data_product LEFT JOIN data_category USING (category_id) LEFT JOIN data_office USING (office_id) ORDER BY date_created DESC LIMIT 5");
                    while ($fetch_barang = mysqli_fetch_array($barang_query)) {
                    ?>
                        <div class="box">
                            <h4 style="color: red ;"><?php echo $fetch_barang['office_name'] ?></h4>
                            <br>
                            <div class="box-item">

                                <img src="produk/<?php echo $fetch_barang['product_image'] ?>" width="50px" height="50px" alt="">
                                <h4><?php echo $fetch_barang['product_name'] ?></h4>
                            </div>
                            <center>
                                <br>
                                <p><?php echo $fetch_barang['date_created'] ?></p>
                            </center>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <br>
                <div class="box">
                    <h2 style="text-align:center ;">Notifikasi Restock Barang</h2>
                    <br>
                    <table border="1" cellspacing="0" class="table">
                        <thead>
                            <tr>
                                <th width="25%">Perwakilan</th>
                                <th width="12%">ID Barang</th>
                                <th>Kategori</th>
                                <th>Nama Barang</th>
                                <th>Stock</th>
                                <th>Batas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $leftjoin_office = "LEFT JOIN data_office USING (office_id)";
                            $stock_query = mysqli_query($conn, "SELECT * FROM data_product LEFT JOIN data_category USING (category_id) LEFT JOIN data_unit USING (unit_id) $leftjoin_office WHERE stock_point >= stock LIMIT 8 ");
                            $stock_query2 = mysqli_query($conn, "SELECT * FROM data_product LEFT JOIN data_category USING (category_id) LEFT JOIN data_unit USING (unit_id) $leftjoin_office WHERE stock_point >= stock ");
                            if (mysqli_num_rows($stock_query) > 0) {
                                while ($fetch_stock = mysqli_fetch_array($stock_query)) {
                            ?>
                                    <tr>
                                        <td><?php echo $fetch_stock['office_name'] ?></td>
                                        <td><?php echo $fetch_stock['product_id'] ?></td>
                                        <td><?php echo $fetch_stock['category_name'] ?></td>
                                        <td><?php echo $fetch_stock['product_name'] ?></td>
                                        <td><?php echo $fetch_stock['stock'] ?></td>
                                        <td><?php echo $fetch_stock['stock_point'] ?></td>
                                    </tr>
                                <?php
                                }
                            } else {
                                ?>
                                <td style="text-align:center ;" colspan="8">Tidak Ada Data</td>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php
                    if (mysqli_num_rows($stock_query2) > 8) {
                    ?>
                        <center>
                            <br>
                            <a href="more-restock-product.php" style="font-size: 15px ; text-decoration :none;"><button class="btn">Lihat Lebih Banyak</button></a>
                        </center>
                    <?php
                    }
                    ?>
                </div>

            </div>
        </div>


    <?php
    }
    ?>

</body>

</html>