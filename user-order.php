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
            <div class="box">
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
                        $trans = mysqli_query($conn, "SELECT * FROM data_transaction LEFT JOIN data_product USING (product_id) WHERE data_transaction.user_id = '" . $iduser . "' AND data_transaction.office_id = '" . $kantoruser . "' ");
                        if (mysqli_num_rows($trans) > 0) {

                            while ($fo_trans = mysqli_fetch_array($trans)) {
                        ?>
                                <tr>
                                    <td><?php echo $no++ ?></td>
                                    <td><?php echo $fo_trans['order_id'] ?></td>
                                    <td><?php echo $fo_trans['product_name'] ?></td>
                                    <td>
                                        <a href="view-order.php?id=<?php echo $fo_trans['order_id'] ?>">Detail</a>
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

    <div class="section">
        <div class="container">
            <a href="checkout.php" class="btn">Checkout</a>
        </div>
    </div>

</body>

</html>