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

$namaperwakilan = mysqli_query($conn, "SELECT * FROM data_office WHERE office_id = '" . $kantoruser . "' ");
$row_np = mysqli_fetch_array($namaperwakilan);

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
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css' />
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css' />
</head>

<body>


    <!-- Content -->
    <div class="section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 px-4 pb-4" id="order">
                    <h4 class="text-center text-info p-2" style="font-weight:bolder ;">Selesaikan Pesanan Anda</h4>
                    <div class="jumbotron p-3 mb-2 text-center">
                        <h6 class="lead" style="font-weight:bolder ;"><b>Produk : </b>
                            <?php
                            $no = 1;

                            $keranjang = mysqli_query($conn, "SELECT * FROM data_cart LEFT JOIN data_category USING (category_id) LEFT JOIN data_product USING (product_id) WHERE data_cart.user_id = '" . $iduser . "' AND data_cart.office_id = '" . $kantoruser . "' ");
                            $cart_id = rand();
                            if (mysqli_num_rows($keranjang) > 0) {
                                while ($fo_keranjang = mysqli_fetch_array($keranjang)) {
                                    echo '<br>';
                                    echo $no++, '. ';
                                    echo $fo_keranjang['product_name'], " (", $fo_keranjang['quantity'], ")";

                            ?>
                                <?php
                                }
                                ?>


                            <?php
                            }
                            ?>
                        </h6>
                    </div>
                    <form action="" method="post" id="placeOrder">

                        <div class="form-group">
                            <h4>Cart ID</h4>
                            <input type="text" name="cart" class="form-control" value="<?php echo $cart_id ?>" readonly>
                        </div>
                        <div class="form-group">
                            <h4>ID User</h4>
                            <input type="text" name="id" class="form-control" value="<?php echo $_SESSION['a_global']->user_id ?>" readonly>
                        </div>
                        <div class="form-group">
                            <h4>Nama</h4>
                            <input type="text" name="name" class="form-control" value="<?php echo $_SESSION['a_global']->user_name ?>" readonly </div>
                            <div class="form-group">
                                <h4>Email</h4>
                                <input type="email" name="email" class="form-control" value="<?php echo $_SESSION['a_global']->user_email ?>" required>
                            </div>
                            <div class="form-group">
                                <h4>Nomor Telfon</h4>
                                <input type="tel" name="phone" class="form-control" value="<?php echo $_SESSION['a_global']->user_telp ?>" required>
                            </div>
                            <div class="form-group">
                                <h4>Perwakilan</h4>
                                <input type="tel" name="office" class="form-control" value="<?php echo $row_np['office_name'] ?>" readonly>
                            </div>
                            <div class="form-group">
                                <input type="submit" name="submit" value="Pesan Sekarang" class="btn btn-danger btn-block">
                            </div>
                    </form>
                    <?php
                    if (isset($_POST['submit'])) {

                        $keranjang1 = mysqli_query($conn, "SELECT * FROM data_cart LEFT JOIN data_category USING (category_id) LEFT JOIN data_product USING (product_id) WHERE data_cart.user_id = '" . $iduser . "' AND data_cart.office_id = '" . $kantoruser . "' ");
                        if (mysqli_num_rows($keranjang1) > 0) {
                            while ($fo_keranjang1 = mysqli_fetch_array($keranjang1)) {
                                $orderid = rand();
                                $keranjang = $_POST['cart'];
                                $iduser = $_POST['id'];
                                $namauser = $_POST['name'];
                                $emailuser = $_POST['email'];
                                $telpuser = $_POST['phone'];
                                $namakantor = $_POST['office'];
                                $idkantor = $kantoruser;
                                $productname = $fo_keranjang1['product_name'];
                                $idkategori = $fo_keranjang1['category_id'];
                                $namakategori = $fo_keranjang1['category_name'];
                                $idproduk = $fo_keranjang1['product_id'];
                                $namaproduk = $fo_keranjang1['product_name'];
                                $kuantitas = $fo_keranjang1["quantity"];
                                $insert = true;

                                if ($insert) {
                                    $insert = mysqli_query($conn, "INSERT INTO data_transaction VALUES (
                                        '" . $orderid . "',
                                        '" . $keranjang . "',
                                        '" . $iduser . "',
                                        '" . $namauser . "',
                                        '" . $emailuser . "',
                                        '" . $telpuser . "',
                                        '" . $idkantor . "',
                                        '" . $namakantor . "',
                                        '" . $idproduk . "',
                                        '" . $namaproduk . "',
                                        '" . $idkategori . "',
                                        '" . $namakategori . "',
                                        '" . $kuantitas . "',
                                        NOW(),
                                        '0',
                                        null
                                    )");

                                    $delete = mysqli_query($conn, "DELETE FROM data_cart WHERE data_cart.user_id = '" . $iduser . "' AND data_cart.office_id = '" . $kantoruser . "' ");
                                } else {
                                    echo 'gagal' . mysqli_error($conn);
                                }
                            }
                        }
                        $masuk = mysqli_query($conn, "INSERT INTO data_order VALUES (
                            '" . $keranjang . "',
                            '" . $iduser . "',
                            '" . $idkantor . "',
                            NOW(),
                            'Hold'
                            
                        )");
                        echo '<script>alert("Berhasil Order")</script>';
                        echo '<script>window.location="user-home.php"</script>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

</body>

</html>