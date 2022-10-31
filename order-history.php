<?php
session_start();
include 'db.php';
//Kondisi Supaya User & Non User tidak dapat akses page ini
if ($_SESSION['role_login'] == 'user') {
    echo '<script>window.location="logout.php"</script>';
} else if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}

$kantoradmin = $_SESSION['a_global']->office_id;
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
                    <?php
                    //Deklarasi 2 variabel untuk menampung cart_id dari table data_transaction
                    $idk_1 = "";
                    $idk_2 = "";
                    //jml_produk digunakan untuk menampung berapa banyak barang yang di-query
                    $jml_produk = 0;
                    //jml_keranjang digunakan untuk menampung berapa banyak jumlah keranjang yang ada
                    $jml_keranjang = 0;
                    //query database
                    $keranjang = mysqli_query($conn, "SELECT * FROM data_transaction WHERE office_id = '" . $kantoradmin . "' AND status = 'Diproses Admin' ORDER BY cart_id");
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
                <h2>Riwayat Pesanan User</h2>
                <div class="box1">
                    <button><a href="order-table.php" style="text-decoration: none ;">Data Pesanan</a></button><br><br>
                </div><br>
                <center>
                    <h3>Pesanan Berhasil</h3>
                </center>
                <div class="box">

                    <table border="1" cellspacing="0" class="table">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">ID Pesanan</th>
                                <th width="10%">Nama Pemesan</th>
                                <th width="50%">Barang</th>
                                <!-- <th>Waktu Pesanan</th> -->
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $where = "OR data_order.status = 'Berhasil Diambil' ";
                            $trans = mysqli_query($conn, "SELECT * FROM data_order WHERE data_order.office_id = '" . $kantoradmin . "' AND data_order.status = 'Berhasil' $where ORDER BY times_updated DESC ");
                            if (mysqli_num_rows($trans) > 0) {

                                while ($fo_trans = mysqli_fetch_array($trans)) {
                            ?>
                                    <tr>
                                        <td><?php echo $no++ ?></td>
                                        <td><?php echo $fo_trans['cart_id'] ?></td>
                                        <?php
                                        $fetch_trans1 = mysqli_query($conn, "SELECT * FROM transaction_history WHERE transaction_history.cart_id = '" . $fo_trans['cart_id'] . "' ");
                                        $fa_fetch1 = mysqli_fetch_array($fetch_trans1)
                                        ?>
                                        <td><?php echo $fa_fetch1['user_name'] ?></td>
                                        <?php
                                        ?>
                                        <td>
                                            <?php
                                            $fetch_trans = mysqli_query($conn, "SELECT * FROM transaction_history WHERE transaction_history.cart_id = '" . $fo_trans['cart_id'] . "' ");
                                            if (mysqli_num_rows($fetch_trans) > 0) {
                                                $neff = 0;
                                                while ($fa_fetch = mysqli_fetch_array($fetch_trans)) {
                                                    echo $fa_fetch['product_name'], " (", $fa_fetch['quantity'], " ", $fa_fetch['unit_name'], ")";
                                                    $neff++;
                                                    if ($neff < mysqli_num_rows($fetch_trans)) {
                                                        print ", ";
                                                    }
                                                }
                                            }
                                            ?>
                                        </td>
                                        <!-- <td><?php echo $fo_trans['created'] ?></td> -->
                                        <td>
                                            <center>
                                                <button id="buttdetail"><a href="admin-order-history.php?id=<?php echo $fo_trans['cart_id'] ?>">Detail</a></button>
                                            </center>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            <?php
                            } else {
                            ?>
                                <td colspan="8">Tidak Ada Data</td>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <center>
                    <h3>Pesanan Berhasil Sebagian</h3>
                </center>
                <div class="box">

                    <table border="1" cellspacing="0" class="table">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">ID Pesanan</th>
                                <th width="10%">Nama Pemesan</th>
                                <th width="50%">Barang</th>
                                <!-- <th>Waktu Pesanan</th> -->
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $trans = mysqli_query($conn, "SELECT * FROM data_order WHERE data_order.office_id = '" . $kantoradmin . "' AND data_order.status = 'Berhasil Sebagian' ");
                            if (mysqli_num_rows($trans) > 0) {

                                while ($fo_trans = mysqli_fetch_array($trans)) {
                            ?>
                                    <tr>
                                        <td><?php echo $no++ ?></td>
                                        <td><?php echo $fo_trans['cart_id'] ?></td>
                                        <?php
                                        $fetch_trans1 = mysqli_query($conn, "SELECT * FROM transaction_history WHERE transaction_history.cart_id = '" . $fo_trans['cart_id'] . "' ");
                                        $fa_fetch1 = mysqli_fetch_array($fetch_trans1)
                                        ?>
                                        <td><?php echo $fa_fetch1['user_name'] ?></td>
                                        <?php
                                        ?>
                                        <td>
                                            <?php
                                            $fetch_trans = mysqli_query($conn, "SELECT * FROM transaction_history WHERE transaction_history.cart_id = '" . $fo_trans['cart_id'] . "' ");
                                            if (mysqli_num_rows($fetch_trans) > 0) {
                                                $neff = 0;
                                                while ($fa_fetch = mysqli_fetch_array($fetch_trans)) {
                                                    echo $fa_fetch['product_name'], " (", $fa_fetch['quantity'], " ", $fa_fetch['unit_name'], ")";
                                                    $neff++;
                                                    if ($neff < mysqli_num_rows($fetch_trans)) {
                                                        print ", ";
                                                    }
                                                }
                                            }
                                            ?>
                                        </td>
                                        <!-- <td><?php echo $fo_trans['created'] ?></td> -->
                                        <td>
                                            <center>
                                                <button id="buttdetail"><a href="admin-order-history.php?id=<?php echo $fo_trans['cart_id'] ?>">Detail</a></button>
                                            </center>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            <?php
                            } else {
                            ?>
                                <td colspan="8">Tidak Ada Data</td>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <center>
                    <h3>Pesanan Gagal</h3>
                </center>
                <div class="box">

                    <table border="1" cellspacing="0" class="table">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">ID Pesanan</th>
                                <th width="10%">Nama Pemesan</th>
                                <th width="50%">Barang</th>
                                <!-- <th>Waktu Pesanan</th> -->
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $where = "OR data_order.status = 'Gagal Diambil' ";
                            $trans = mysqli_query($conn, "SELECT * FROM data_order WHERE data_order.office_id = '" . $kantoradmin . "' AND data_order.status = 'Tidak Disetujui Admin' $where ");
                            if (mysqli_num_rows($trans) > 0) {

                                while ($fo_trans = mysqli_fetch_array($trans)) {
                            ?>
                                    <tr>
                                        <td><?php echo $no++ ?></td>
                                        <td><?php echo $fo_trans['cart_id'] ?></td>
                                        <?php
                                        $fetch_trans1 = mysqli_query($conn, "SELECT * FROM transaction_history WHERE transaction_history.cart_id = '" . $fo_trans['cart_id'] . "' ");
                                        $fa_fetch1 = mysqli_fetch_array($fetch_trans1)
                                        ?>
                                        <td><?php echo $fa_fetch1['user_name'] ?></td>
                                        <?php
                                        ?>
                                        <td>
                                            <?php
                                            $fetch_trans = mysqli_query($conn, "SELECT * FROM transaction_history WHERE transaction_history.cart_id = '" . $fo_trans['cart_id'] . "' ");
                                            if (mysqli_num_rows($fetch_trans) > 0) {
                                                $neff = 0;
                                                while ($fa_fetch = mysqli_fetch_array($fetch_trans)) {
                                                    echo $fa_fetch['product_name'], " (", $fa_fetch['quantity'], " ", $fa_fetch['unit_name'], ")";
                                                    $neff++;
                                                    if ($neff < mysqli_num_rows($fetch_trans)) {
                                                        print ", ";
                                                    }
                                                }
                                            }
                                            ?>
                                        </td>
                                        <!-- <td><?php echo $fo_trans['created'] ?></td> -->
                                        <td>
                                            <center>
                                                <button id="buttdetail"><a href="admin-order-history.php?id=<?php echo $fo_trans['cart_id'] ?>">Detail</a></button>
                                            </center>
                                        </td>
                                    </tr>
                                <?php
                                }



                                ?>


                            <?php
                            } else {
                            ?>
                                <td colspan="8">Tidak Ada Data</td>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!------------------------------------------------------SUPER-------------------------------------------------------------------->
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
                <h2>Riwayat Pesanan User</h2>
                <div class="box1">
                    <button><a href="order-table.php" style="text-decoration: none ;">Data Pesanan</a></button><br><br>
                </div><br>

                <div class="box">
                    <form action="" method="POST">
                        <h3>Perwakilan</h3>
                        <select name="perwakilan" class="input-control" value="<?php echo $_POST['perwakilan'] ?>">
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
                    $kantor_query = mysqli_query($conn, "SELECT * FROM data_office WHERE office_id = '" . $_POST['perwakilan'] . "' ");
                    $fetch_kantor = mysqli_fetch_array($kantor_query);
                    $nama_kantor = $fetch_kantor['office_name'];
                ?>
                    <center>
                        <h2><?php echo $nama_kantor ?></h2>
                        <br>
                    </center>
                    <center>
                        <h3>Pesanan Berhasil</h3>
                    </center>
                    <div class="box">

                        <table border="1" cellspacing="0" class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>ID Pesanan</th>
                                    <th>Nama Pemesan</th>
                                    <th>Barang</th>
                                    <th>Waktu Pesanan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $trans = mysqli_query($conn, "SELECT * FROM data_order WHERE data_order.status = 'Berhasil' AND data_order.office_id = '" . $_POST['perwakilan'] . "' ORDER BY times_updated DESC ");
                                if (mysqli_num_rows($trans) > 0) {
                                    while ($fo_trans = mysqli_fetch_array($trans)) {
                                ?>
                                        <tr>
                                            <td><?php echo $no++ ?></td>
                                            <td><?php echo $fo_trans['cart_id'] ?></td>
                                            <?php
                                            $fetch_trans1 = mysqli_query($conn, "SELECT * FROM transaction_history WHERE transaction_history.cart_id = '" . $fo_trans['cart_id'] . "' ");
                                            $fa_fetch1 = mysqli_fetch_array($fetch_trans1)
                                            ?>
                                            <td><?php echo $fa_fetch1['user_name'] ?></td>
                                            <?php
                                            ?>
                                            <td>
                                                <?php
                                                $fetch_trans = mysqli_query($conn, "SELECT * FROM transaction_history WHERE transaction_history.cart_id = '" . $fo_trans['cart_id'] . "' ");
                                                if (mysqli_num_rows($fetch_trans) > 0) {
                                                    $neff = 0; //nilai effisien
                                                    while ($fa_fetch = mysqli_fetch_array($fetch_trans)) {
                                                        echo $fa_fetch['product_name'], " (", $fa_fetch['quantity'], " ", $fa_fetch['unit_name'], ") ";
                                                        $neff++;
                                                        if ($neff < mysqli_num_rows($fetch_trans)) {
                                                            print ", ";
                                                        }
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $fo_trans['created'] ?></td>
                                            <td>
                                                <center>
                                                    <button id="buttdetail"><a href="admin-order-history.php?id=<?php echo $fo_trans['cart_id'] ?>">Detail</a></button>
                                                </center>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                <?php
                                } else {
                                ?>
                                    <td colspan="8">Tidak Ada Data</td>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <center>
                        <h3>Pesanan Berhasil Sebagian</h3>
                    </center>
                    <div class="box">

                        <table border="1" cellspacing="0" class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>ID Pesanan</th>
                                    <th>Nama Pemesan</th>
                                    <th>Barang</th>
                                    <th>Waktu Pesanan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $trans = mysqli_query($conn, "SELECT * FROM data_order WHERE data_order.status = 'Berhasil Sebagian' AND data_order.office_id = '" . $_POST['perwakilan'] . "' ORDER BY times_updated DESC ");
                                if (mysqli_num_rows($trans) > 0) {
                                    while ($fo_trans = mysqli_fetch_array($trans)) {
                                ?>
                                        <tr>
                                            <td><?php echo $no++ ?></td>
                                            <td><?php echo $fo_trans['cart_id'] ?></td>
                                            <?php
                                            $fetch_trans1 = mysqli_query($conn, "SELECT * FROM transaction_history WHERE transaction_history.cart_id = '" . $fo_trans['cart_id'] . "' ");
                                            $fa_fetch1 = mysqli_fetch_array($fetch_trans1)
                                            ?>
                                            <td><?php echo $fa_fetch1['user_name'] ?></td>
                                            <?php
                                            ?>
                                            <td>
                                                <?php
                                                $fetch_trans = mysqli_query($conn, "SELECT * FROM transaction_history WHERE transaction_history.cart_id = '" . $fo_trans['cart_id'] . "' ");
                                                if (mysqli_num_rows($fetch_trans) > 0) {
                                                    $neff = 0; //nilai effisien
                                                    while ($fa_fetch = mysqli_fetch_array($fetch_trans)) {
                                                        echo $fa_fetch['product_name'], " (", $fa_fetch['quantity'], " ", $fa_fetch['unit_name'], ") ";
                                                        $neff++;
                                                        if ($neff < mysqli_num_rows($fetch_trans)) {
                                                            print ", ";
                                                        }
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $fo_trans['created'] ?></td>
                                            <td>
                                                <center>
                                                    <button id="buttdetail"><a href="admin-order-history.php?id=<?php echo $fo_trans['cart_id'] ?>">Detail</a></button>
                                                </center>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                <?php
                                } else {
                                ?>
                                    <td colspan="8">Tidak Ada Data</td>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <center>
                        <h3>Pesanan Gagal</h3>
                    </center>
                    <div class="box">

                        <table border="1" cellspacing="0" class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>ID Pesanan</th>
                                    <th>Nama Pemesan</th>
                                    <th>Barang</th>
                                    <th>Waktu Pesanan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $trans = mysqli_query($conn, "SELECT * FROM data_order WHERE data_order.status = 'Gagal' AND data_order.office_id = '" . $_POST['perwakilan'] . "' ORDER BY times_updated DESC ");
                                if (mysqli_num_rows($trans) > 0) {

                                    while ($fo_trans = mysqli_fetch_array($trans)) {
                                ?>
                                        <tr>
                                            <td><?php echo $no++ ?></td>
                                            <td><?php echo $fo_trans['cart_id'] ?></td>
                                            <?php
                                            $fetch_trans1 = mysqli_query($conn, "SELECT * FROM transaction_history WHERE transaction_history.cart_id = '" . $fo_trans['cart_id'] . "' ");
                                            $fa_fetch1 = mysqli_fetch_array($fetch_trans1)
                                            ?>
                                            <td><?php echo $fa_fetch1['user_name'] ?></td>
                                            <?php
                                            ?>
                                            <td>
                                                <?php
                                                $fetch_trans = mysqli_query($conn, "SELECT * FROM transaction_history WHERE transaction_history.cart_id = '" . $fo_trans['cart_id'] . "' ");
                                                if (mysqli_num_rows($fetch_trans) > 0) {
                                                    $neff = 0; //nilai effisien
                                                    while ($fa_fetch = mysqli_fetch_array($fetch_trans)) {
                                                        echo $fa_fetch['product_name'], " (", $fa_fetch['quantity'], " ", $fa_fetch['unit_name'], ") ";
                                                        $neff++;
                                                        if ($neff < mysqli_num_rows($fetch_trans)) {
                                                            print ", ";
                                                        }
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $fo_trans['created'] ?></td>
                                            <td>
                                                <center>
                                                    <button id="buttdetail"><a href="admin-order-history.php?id=<?php echo $fo_trans['cart_id'] ?>">Detail</a></button>
                                                </center>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                <?php
                                } else {
                                ?>
                                    <td colspan="8">Tidak Ada Data</td>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                <?php
                }
                ?>


            </div>
        </div>
    <?php
    }
    ?>

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