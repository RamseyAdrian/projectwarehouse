<?php
session_start();
include 'db.php';
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
        <!-- header -->
        <header>
            <div class="container">
                <h1><a href="dashboard.php">KP Ombudsman</a></h1>
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="profile.php">Profil</a></li>
                    <li><a href="product-data.php">Data Produk</a></li>
                    <li><a href="user-data.php">Data User</a></li>
                    <li><a href="order-table.php">Pesanan</a></li>
                    <li><a href="logout.php">Keluar</a></li>
                </ul>
            </div>
        </header>

        <!-- Content -->
        <div class="section">
            <div class="container">
                <h2>Pesanan User</h2>
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
                                <th>No</th>
                                <th>ID Pesanan</th>
                                <th>Nama Pemesan</th>
                                <th>Produk</th>
                                <th>Waktu</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $trans = mysqli_query($conn, "SELECT * FROM data_order WHERE data_order.office_id = '" . $kantoradmin . "' AND data_order.status = 'Berhasil' ");
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
                                                while ($fa_fetch = mysqli_fetch_array($fetch_trans)) {
                                                    echo $fa_fetch['product_name'], "(", $fa_fetch['quantity'], ")";
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo $fo_trans['created'] ?></td>
                                        <td>
                                            <center>
                                                <button id="buttdetail"><a href="admin-order-history.php?id=<?php echo $fo_trans['cart_id'] ?>">Lihat Detail Pesanan</a></button>
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
                                <th>Produk</th>
                                <th>Waktu</th>
                                <th>Aksi</th>
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
                                                while ($fa_fetch = mysqli_fetch_array($fetch_trans)) {
                                                    echo $fa_fetch['product_name'], "(", $fa_fetch['quantity'], ")";
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo $fo_trans['created'] ?></td>
                                        <td>
                                            <center>
                                                <button id="buttdetail"><a href="admin-order-history.php?id=<?php echo $fo_trans['cart_id'] ?>">Lihat Detail Pesanan</a></button>
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
                                <th>Produk</th>
                                <th>Waktu</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $trans = mysqli_query($conn, "SELECT * FROM data_order WHERE data_order.office_id = '" . $kantoradmin . "' AND data_order.status = 'Gagal' ");
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
                                                while ($fa_fetch = mysqli_fetch_array($fetch_trans)) {
                                                    echo $fa_fetch['product_name'], "(", $fa_fetch['quantity'], ")";
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo $fo_trans['created'] ?></td>
                                        <td>
                                            <center>
                                                <button id="buttdetail"><a href="admin-order-history.php?id=<?php echo $fo_trans['cart_id'] ?>">Lihat Detail Pesanan</a></button>
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
    <?php
    } else if ($_SESSION['role_login'] == 'super') {
    ?>
        <!-- header -->
        <header>
            <div class="container">
                <h1><a href="dashboard.php">KP Ombudsman</a></h1>
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="profile.php">Profil</a></li>
                    <li><a href="category-data.php">Data Kategori</a></li>
                    <li><a href="product-data.php">Data Produk</a></li>
                    <li><a href="office-data.php">Perwakilan</a></li>
                    <li><a href="admin-data.php">Data Admin</a></li>
                    <li><a href="user-data.php">Data User</a></li>
                    <li><a href="order-table.php">Pesanan</a></li>
                    <li><a href="logout.php">Keluar</a></li>
                </ul>
            </div>
        </header>

        <!-- Content -->
        <div class="section">
            <div class="container">
                <h3>Keranjang</h3>
                <div class="box">
                    <table border="1" cellspacing="0" class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Perwakilan</th>
                                <th>ID Pesanan</th>
                                <th>ID User</th>
                                <th>Nama</th>
                                <th>Barang</th>
                                <th>Waktu</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $trans = mysqli_query($conn, "SELECT * FROM data_transaction LEFT JOIN data_product USING (product_id) ");
                            if (mysqli_num_rows($trans) > 0) {

                                while ($fo_trans = mysqli_fetch_array($trans)) {
                            ?>
                                    <tr>
                                        <td><?php echo $no++ ?></td>
                                        <td><?php echo $fo_trans['office_name'] ?></td>
                                        <td><?php echo $fo_trans['order_id'] ?></td>
                                        <td><?php echo $fo_trans['user_id'] ?></td>
                                        <td><?php echo $fo_trans['user_name'] ?></td>
                                        <td><?php echo $fo_trans['product_name'] ?></td>
                                        <td><?php echo $fo_trans['created'] ?></td>
                                        <td><?php
                                            if ($fo_trans['status'] == 0) {
                                                echo "Belum disetujui";
                                            } else if ($fo_trans['status'] == 1) {
                                                echo "Disetujui";
                                            } else if ($fo_trans['status'] == 2) {
                                                echo "Tidak Disetujui";
                                            }
                                            ?></td>
                                        <td>
                                            <a href="edit-order.php?id=<?php echo $fo_trans['order_id'] ?>">Detail</a>
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
    <?php
    }
    ?>



</body>

</html>