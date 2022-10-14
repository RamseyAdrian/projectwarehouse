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
            <h3>Keranjang</h3>
            <style>
                .box table tbody tr td .abutt {
                    margin-right: 10px;
                    padding: 2px;
                    border: 1px solid;
                    background-color: white;
                    border-radius: 5px;
                    text-decoration: none;
                    font-weight: bold;
                }

                .box table tbody tr td .abutt:hover {
                    color: white;
                    background-color: black;
                }
            </style>
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
                            <th>Edit/Hapus</th>
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
                                    <td><a href="produk/<?php echo $fo_keranjang['product_image'] ?>"> <img src="produk/<?php echo $fo_keranjang['product_image'] ?>" width="50px"></a></td>
                                    <td><?php echo $fo_keranjang['product_id'] ?></td>
                                    <td><?php echo $fo_keranjang['category_name'] ?></td>
                                    <td><?php echo $fo_keranjang['product_name'] ?></td>
                                    <td style="text-align:center ;"><?php echo $fo_keranjang['quantity'] ?></td>
                                    <td style="text-align:center ;">
                                        <a class="abutt" href="edit-user-cart.php?id=<?php echo $fo_keranjang['product_id'] ?>">Edit Jumlah</a> <a class="abutt" href="delete-data.php?idc=<?php echo $fo_keranjang['product_id'] ?>" onclick="return confirm('R U Sure about dat ?') ">Hapus</a>
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