<?php
session_start();
include 'db.php';
//Kondisi Supaya Non User tidak dapat akses page ini
if ($_SESSION['status_login'] != true) {

    echo '<script>window.location="login.php"</script>';
    //Kondisi Jika User Akses Page Ini
} else if ($_SESSION['role_login'] == 'user') {

    $iduser = $_SESSION['a_global']->user_id;
    $kantoruser = $_SESSION['a_global']->office_id;
    //Kondisi Jika Super Admin Akses Page Ini
} else if ($_SESSION['role_login'] == 'super') {
    $perwakilan = $_SESSION['history'];
    $query_perwakilan = mysqli_query($conn, "SELECT * FROM data_office WHERE office_id = '" . $perwakilan . "' ");
    $fetch_perwakilan = mysqli_fetch_assoc($query_perwakilan);
    $nama_perwakilan = $fetch_perwakilan['office_name'];
}

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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <!--------------------- Sweetalert CDN ----------------------------->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!--------------------- Font Awesome ----------------------------->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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

        .section .container .box1 button a {
            font-weight: bold;
        }

        .section .container .box1 button:hover {
            background-color: black;
            color: white;
        }

        #buttdetail {
            font-size: 17px;
            background-color: lime;
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
    <!---------------------------------USER----------------------------------------->
    <?php
    if ($_SESSION['role_login'] == 'user') {
    ?>
        <!---------------------- header ----------------------------------->
        <header>
            <div class="container">
                <h1><img style="width: 80px ; margin-bottom :-10px ;" src="img/logo-ombudsman2.png" alt=""><a href="user-home.php"> Gudang Ombudsman</a></h1>
                <ul style="margin-top: 20px ;">
                    <?php
                    $isi = 0;
                    $keranjang = mysqli_query($conn, "SELECT * FROM data_cart WHERE user_id = '" . $iduser . "' AND office_id = '" . $kantoruser . "' ");
                    if (mysqli_num_rows($keranjang) > 0) {
                        while ($fetch_keranjang = mysqli_fetch_array($keranjang)) {
                            $isi++;
                        }
                    }
                    ?>
                    <li><a href="user-home.php">Home</a></li>
                    <li><a href="user-category-product.php">Kategori</a></li>
                    <li><a href="user-cart.php"><i class="fa fa-shopping-cart" aria-hidden="true" style="width:16px;"></i>(<?php echo $isi ?>)</a></li>
                    <li><a href="user-order.php">Transaksi</a></li>
                    <li><a href="user-profile.php">Profil Saya</a></li>
                    <li><a href="logout.php">Log out</a></li>
                </ul>
            </div>
        </header>

        <!---------------------- Content ----------------------------------->

        <div class="section">
            <div class="container">
                <h2>Pesanan Berhasil</h2>
                <div class="box1">
                    <button><a href="user-order-history.php" style="text-decoration: none ;">Kembali</a></button><br><br>
                </div>
                <br>
                <div class="box">
                    <table border="1" cellspacing="0" class="table">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">ID Pesanan</th>
                                <th width="50%">Barang</th>
                                <th width="15%">Waktu Diproses</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $where_office = " AND data_order.office_id = '" . $kantoruser . "' ";
                            $trans = mysqli_query($conn, "SELECT * FROM data_order WHERE data_order.status = 'Berhasil Diambil' AND data_order.user_id = '" . $iduser . "' $where_office ORDER BY times_updated DESC");
                            while ($fo_trans = mysqli_fetch_assoc($trans)) {
                            ?>
                                <tr>
                                    <td><?php echo $no++ ?></td>
                                    <td><?php echo $fo_trans['cart_id'];
                                        $idcart = $fo_trans['cart_id'] ?></td>
                                    <td>
                                        <?php
                                        $fetch_trans = mysqli_query($conn, "SELECT * FROM transaction_history WHERE transaction_history.cart_id = '" . $idcart . "' ");
                                        if (mysqli_num_rows($fetch_trans) > 0) {
                                            $neff = 0;
                                            while ($fa_fetch = mysqli_fetch_array($fetch_trans)) {
                                                echo $fa_fetch['product_name'], "(", $fa_fetch['quantity'], " ", $fa_fetch['unit_name'], ") ";
                                                $neff++;
                                                if ($neff < mysqli_num_rows($fetch_trans)) {
                                                    print ", ";
                                                }
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $fetch_trans2 = mysqli_query($conn, "SELECT * FROM transaction_history WHERE transaction_history.cart_id = '" . $idcart . "'");
                                        $fa_trans2 = mysqli_fetch_array($fetch_trans2);
                                        echo $fa_trans2['created'];
                                        ?>
                                    </td>
                                    <td>
                                        <center>
                                            <button id="buttdetail" class="view"><a href="view-order-history.php?id=<?php echo $fo_trans['cart_id'] ?>">Detail</a></button>
                                        </center>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php
    } else if ($_SESSION['role_login'] == 'admin') {
    ?>
        <!---------------------------------ADMIN----------------------------------------->

    <?php
    } else if ($_SESSION['role_login'] == 'super') {
    ?>
        <!---------------------------------SUPER----------------------------------------->
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
                <h1 style="text-align: center ;"><?php echo $nama_perwakilan ?></h1>
                <h2>Pesanan Berhasil</h2>
                <div class="box1">
                    <button><a href="order-history.php" style="text-decoration: none ;">Kembali</a></button><br><br>
                </div>
                <br>
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
                            $order = mysqli_query($conn, "SELECT * FROM data_order WHERE data_order.status = 'Berhasil Diambil' AND data_order.office_id = '" . $perwakilan . "' ORDER BY times_updated DESC");
                            while ($fo_trans = mysqli_fetch_assoc($order)) {
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