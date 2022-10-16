<?php
session_start();
include 'db.php';
if ($_SESSION['role_login'] != 'user') {

    echo '<script>window.location="logout.php"</script>';
} else if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}

$iduser = $_SESSION['a_global']->user_id;
$kantoruser = $_SESSION['a_global']->office_id;

// header("refresh: 3;");
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    <!-- header -->
    <header>
        <div class="container">
            <h1><a href="user-home.php">KP Ombudsman</a></h1>
            <ul>
                <li><a href="user-homepage-product.php">Produk</a></li>
                <li><a href="user-cart.php">Keranjang</a></li>
                <li><a href="user-order.php">Pesanan</a></li>
                <li><a href="user-profile.php">Profil Saya</a></li>
                <li><a href="logout.php">Log out</a></li>
            </ul>
        </div>
    </header>

    <!-- Content -->
    <div class="section">
        <div class="container">
            <h3>Pesanan Saya</h3>
            <div class="box1">
                <button><a href="user-order.php" style="text-decoration: none ;">Data Pesanan</a></button><br><br>
            </div>
            <center>
                <h3>Pesanan Berhasil</h3>
            </center>
            <div class="box">
                <table border="1" cellspacing="0" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Pesanan</th>
                            <th>Produk</th>
                            <th>Status</th>
                            <th>Waktu Dipesan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $trans = mysqli_query($conn, "SELECT * FROM data_order WHERE data_order.status = 'Berhasil' AND data_order.user_id = '" . $iduser . "' ORDER BY times_updated DESC ");
                        if (mysqli_num_rows($trans) > 0) {

                            while ($fo_trans = mysqli_fetch_array($trans)) {
                        ?>
                                <tr>
                                    <td><?php echo $no++ ?></td>
                                    <td><?php echo $fo_trans['cart_id'];
                                        $idcart = $fo_trans['cart_id']; ?></td>
                                    <td>
                                        <?php
                                        $fetch_trans = mysqli_query($conn, "SELECT * FROM transaction_history WHERE transaction_history.cart_id = '" . $idcart . "' ");
                                        if (mysqli_num_rows($fetch_trans) > 0) {
                                            while ($fa_fetch = mysqli_fetch_array($fetch_trans)) {
                                                echo $fa_fetch['product_name'], "(", $fa_fetch['quantity'], ")";
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td><?php
                                        echo $fo_trans['status']
                                        ?></td>
                                    <td><?php echo $fo_trans['created'] ?></td>
                                    <td>
                                        <center>
                                            <button id="buttdetail" class="view"><a href="view-order-history.php?id=<?php echo $fo_trans['cart_id'] ?>">Detail & Cetak Surat</a></button>
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
                            <th>Produk</th>
                            <th>Status</th>
                            <th>Waktu Dipesan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $trans = mysqli_query($conn, "SELECT * FROM data_order WHERE data_order.status = 'Berhasil Sebagian' AND data_order.user_id = '" . $iduser . "' ");
                        if (mysqli_num_rows($trans) > 0) {

                            while ($fo_trans = mysqli_fetch_array($trans)) {
                        ?>
                                <tr>
                                    <td><?php echo $no++ ?></td>
                                    <td><?php echo $fo_trans['cart_id'];
                                        $idcart = $fo_trans['cart_id']; ?></td>
                                    <td>
                                        <?php
                                        $fetch_trans = mysqli_query($conn, "SELECT * FROM transaction_history WHERE transaction_history.cart_id = '" . $idcart . "' ");
                                        if (mysqli_num_rows($fetch_trans) > 0) {
                                            while ($fa_fetch = mysqli_fetch_array($fetch_trans)) {
                                                echo $fa_fetch['product_name'], "(", $fa_fetch['quantity'], ")";
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td><?php
                                        echo $fo_trans['status']
                                        ?></td>
                                    <td><?php echo $fo_trans['created'] ?></td>
                                    <td>
                                        <center>
                                            <button id="buttdetail" class="view"><a href="view-order-history.php?id=<?php echo $fo_trans['cart_id'] ?>">Detail & Cetak Surat</a></button>
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
                            <th>Produk</th>
                            <th>Status</th>
                            <th>Waktu Dipesan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $trans = mysqli_query($conn, "SELECT * FROM data_order WHERE data_order.status = 'Gagal' AND data_order.user_id = '" . $iduser . "' ");
                        if (mysqli_num_rows($trans) > 0) {

                            while ($fo_trans = mysqli_fetch_array($trans)) {
                        ?>
                                <tr>
                                    <td><?php echo $no++ ?></td>
                                    <td><?php echo $fo_trans['cart_id'];
                                        $idcart = $fo_trans['cart_id']; ?></td>
                                    <td>
                                        <?php
                                        $fetch_trans = mysqli_query($conn, "SELECT * FROM transaction_history WHERE transaction_history.cart_id = '" . $idcart . "' ");
                                        if (mysqli_num_rows($fetch_trans) > 0) {
                                            while ($fa_fetch = mysqli_fetch_array($fetch_trans)) {
                                                echo $fa_fetch['product_name'], "(", $fa_fetch['quantity'], ") ";
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td><?php
                                        echo $fo_trans['status']
                                        ?></td>
                                    <td><?php echo $fo_trans['created'] ?></td>
                                    <td>
                                        <center>
                                            <button id="buttdetail" class="view"><a href="view-order-history.php?id=<?php echo $fo_trans['cart_id'] ?>">Detail</a></button>
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

</body>

</html>