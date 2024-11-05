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

        .section .container #checkout {
            text-decoration: none;
            border-radius: 5px;
            margin-left: 30px;
            transition: 0.5s;
        }

        .section .container #kembali {
            text-decoration: none;
            background-color: white;
            color: black;
            font-weight: bold;
            border: 1px solid;
            padding: 8px;
            border-radius: 5px;
            transition: 0.5s;
        }

        .section .container #checkout:hover {
            border: 1px solid red;
            background-color: white;
            color: red;
            border-radius: 5px;
        }

        .section .container #kembali:hover {
            border: 1px solid white;
            background-color: black;
            color: white;
            padding: 8px;
            border-radius: 5px;
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

    <!---------------------- Content ----------------------------------->
    <div class="section">
        <div class="container">
            <h3>Keranjang</h3>
            <div class="box">
                <table border="1" cellspacing="0" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Gambar</th>
                            <th>ID Barang</th>
                            <th>Kategori</th>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Ubah Jumlah</th>
                            <th>Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $keranjang = mysqli_query($conn, "SELECT * FROM data_cart LEFT JOIN data_category USING (category_id) LEFT JOIN data_product USING (product_id) WHERE data_cart.user_id = '" . $iduser . "' AND data_cart.office_id = '" . $kantoruser . "' ");
                        if (mysqli_num_rows($keranjang) > 0) {
                            while ($fo_keranjang = mysqli_fetch_array($keranjang)) {
                        ?>
                                <tr>
                                    <td><?php echo $no++ ?></td>
                                    <td><a href="produk/<?php echo $fo_keranjang['product_image'] ?>">
                                            <center><img src="produk/<?php echo $fo_keranjang['product_image'] ?>" width="50px"></center>
                                        </a></td>
                                    <td><?php echo $fo_keranjang['product_id'] ?></td>
                                    <td><?php echo $fo_keranjang['category_name'] ?></td>
                                    <td><?php echo $fo_keranjang['product_name'] ?></td>
                                    <?php
                                    $satuan = mysqli_query($conn, "SELECT * FROM data_unit WHERE unit_id = '" . $fo_keranjang['unit_id'] . "' ");
                                    while ($fa_satuan = mysqli_fetch_array($satuan)) {
                                    ?>
                                        <td style="text-align:center ;"><?php echo $fo_keranjang['quantity'], " ", $fa_satuan['unit_name'] ?></td>
                                    <?php
                                    }
                                    ?>
                                    <td style="text-align:center ;">
                                        <a class="abutt" href="edit-user-cart.php?id=<?php echo $fo_keranjang['product_id'] ?>&iduser=<?php echo $iduser ?>">Edit</a>
                                    </td>
                                    <td style="text-align:center ;">
                                        <a class="abutt" href="delete-data.php?idc=<?php echo $fo_keranjang['product_id'] ?>&iduser=<?php echo $iduser ?>" onclick="return confirm('Lanjut Hapus Barang ?') ">Hapus</a>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        <?php
                        } else {
                        ?>
                            <td colspan="8" style="text-align:center ;">Tidak Ada Data</td>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="section" style="margin-top: -40px; margin-bottom: 40px; ">
        <div class="container">
            <a id="kembali" href="user-home.php">Kembali Pesan</a>
            <?php
            $keranjang1 = mysqli_query($conn, "SELECT * FROM data_cart LEFT JOIN data_category USING (category_id) LEFT JOIN data_product USING (product_id) WHERE data_cart.user_id = '" . $iduser . "' AND data_cart.office_id = '" . $kantoruser . "' ");
            if (mysqli_num_rows($keranjang1) > 0) {
            ?>
                <a id="checkout" href="checkout.php" class="btn">Checkout</a>
            <?php
            }
            ?>
        </div>
    </div>

</body>

</html>