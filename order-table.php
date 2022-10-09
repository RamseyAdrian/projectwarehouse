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
                <h3>Pesanan User</h3>
                <div class="box">
                    <table border="1" cellspacing="0" class="table">
                        <thead>
                            <tr>
                                <th>No</th>
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
                            $trans = mysqli_query($conn, "SELECT * FROM data_transaction LEFT JOIN data_product USING (product_id) WHERE data_transaction.office_id = '" . $kantoradmin . "' ");
                            if (mysqli_num_rows($trans) > 0) {

                                while ($fo_trans = mysqli_fetch_array($trans)) {
                            ?>
                                    <tr>
                                        <td><?php echo $no++ ?></td>
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