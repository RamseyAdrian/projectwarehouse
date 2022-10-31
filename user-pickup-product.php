<?php
session_start();
include 'db.php';

//Kondisi Supaya Non User tidak dapat akses page ini
if ($_SESSION['role_login'] != 'user') {
    echo '<script>window.location="logout.php"</script>';
} else if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}

$iduser = $_SESSION['a_global']->user_id;
$kantoruser = $_SESSION['a_global']->office_id;
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
            background-color: #fff;
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

        #buttprint {
            font-size: 17px;
            background-color: lime;
            color: black;
            border-radius: 5px;
            padding: 2px;
        }

        #buttprint a {
            text-decoration: none;
            font-weight: bold;
        }

        #buttprint:hover {
            background-color: black;
            color: white;
            transition-duration: 0.3s;
        }
    </style>
</head>

<body>
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

    <div class="section">
        <div class="container">
            <h2>Pengambilan Barang</h2>
            <div class="box1">
                <button><a href="user-order.php" style="text-decoration: none ;">Data Pesanan</a></button><br><br>
            </div>
            <div class="box">
                <table border="1" cellspacing="0" class="table">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="20%">ID Pesanan</th>
                            <th>Barang</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $where_status = "AND data_order.status = 'Akan Diambil' ";
                        $trans = mysqli_query($conn, "SELECT * FROM data_order WHERE data_order.office_id = '" . $kantoruser . "' AND data_order.user_id = '" . $iduser . "' $where_status ORDER BY times_updated ");
                        if (mysqli_num_rows($trans) > 0) {
                            while ($fetch_trans = mysqli_fetch_array($trans)) {
                        ?>
                                <tr>
                                    <td><?php echo $no++ ?></td>
                                    <td><?php echo $fetch_trans['cart_id'] ?></td>
                                    <td>
                                        <?php
                                        $trans_2 = mysqli_query($conn, "SELECT * FROM data_transaction WHERE data_transaction.cart_id= '" . $fetch_trans['cart_id'] . "' ");
                                        $jumlah_item = mysqli_num_rows($trans_2);
                                        if ($jumlah_item > 0) {
                                            $neff = 0;
                                            while ($fetch_trans_2 = mysqli_fetch_array($trans_2)) {
                                                echo $fetch_trans_2['product_name'], " (", $fetch_trans_2['quantity'], " ", $fetch_trans_2['unit_name'], ")";
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
                                            <button id="buttdetail"><a href="user-pickup-detail.php?id=<?php echo $fetch_trans['cart_id'] ?>">Detail & Print Surat</a></button>
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