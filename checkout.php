<?php
session_start();
include 'db.php';
// Kondisi Supaya Super Admin, Admin, & Non User tidak dapat akses page ini
if ($_SESSION['role_login'] != 'user') {
    echo '<script>window.location="logout.php"</script>';
} else if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}
$iduser = $_SESSION['a_global']->user_id;
$kantoruser = $_SESSION['a_global']->office_id;

$keranjang = mysqli_query($conn, "SELECT * FROM data_cart WHERE office_id = '" . $kantoruser . "' AND user_id = '" . $iduser . "' ");
if (mysqli_num_rows($keranjang) == 0) {
    echo '<script>window.location="logout.php"</script>';
}

$namaperwakilan = mysqli_query($conn, "SELECT * FROM data_office WHERE office_id = '" . $kantoruser . "' ");
$row_np = mysqli_fetch_array($namaperwakilan);
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
    <!--------------------- Bootstrap  ----------------------------->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css' />
    <!--------------------- Font Awesome ----------------------------->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css' />
</head>

<body>
    <!---------------------- Content ----------------------------------->
    <div class="section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 px-4 pb-4" id="order">
                    <div class="section">
                        <div class="container">
                            <div class="box">
                                <h4 class="text-center text-info p-2" style="font-weight:bolder ;">Selesaikan Pesanan Anda</h4>
                                <div class="jumbotron p-3 mb-2 text-center">
                                    <h6 class="lead" style="font-weight:bolder ;"><b>Produk : </b>
                                        <?php
                                        $no = 1;

                                        $keranjang = mysqli_query($conn, "SELECT * FROM data_cart LEFT JOIN data_category USING (category_id) LEFT JOIN data_product USING (product_id) WHERE data_cart.user_id = '" . $iduser . "' AND data_cart.office_id = '" . $kantoruser . "' ");
                                        $cart_id = rand();
                                        if (mysqli_num_rows($keranjang) > 0) {
                                            while ($fo_keranjang = mysqli_fetch_array($keranjang)) {
                                                $namasatuan = mysqli_query($conn, "SELECT * FROM data_unit WHERE unit_id = '" . $fo_keranjang['unit_id'] . "' ");
                                                $fa_satuan = mysqli_fetch_array($namasatuan);
                                                echo '<br>';
                                                echo $no++, '. ';
                                                echo $fo_keranjang['product_name'], " (", $fo_keranjang['quantity'], " ", $fa_satuan['unit_name'], ")";

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
                                        </div><br>
                                        <div class="form-group">
                                            <input type="submit" name="submit" value="Pesan Sekarang" class="btn btn-danger btn-block">
                                        </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <?php
                    if (isset($_POST['submit'])) {

                        $keranjang1 = mysqli_query($conn, "SELECT * FROM data_cart LEFT JOIN data_category USING (category_id) LEFT JOIN data_product USING (product_id) WHERE data_cart.user_id = '" . $iduser . "' AND data_cart.office_id = '" . $kantoruser . "' ");
                        if (mysqli_num_rows($keranjang1) > 0) {
                            while ($fo_keranjang1 = mysqli_fetch_array($keranjang1)) {
                                $namasatuan2 = mysqli_query($conn, "SELECT * FROM data_unit WHERE unit_id = '" . $fo_keranjang1['unit_id'] . "' ");
                                $fa_satuan2 = mysqli_fetch_array($namasatuan2);

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
                                $idsatuan = $fo_keranjang1['unit_id'];
                                $satuannama = $fa_satuan2['unit_name'];
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
                                        '" . $idsatuan . "',
                                        '" . $satuannama . "',
                                        '" . $kuantitas . "',
                                        NOW(),
                                        null,
                                        'Diproses Admin',
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
                            'Diproses Admin',
                            null,
                            null
                            
                        )");
                        echo '<script>Swal.fire({
                            title: "Berhasil Order Barang !",
                            text: "Klik OK Untuk Lanjut",
                            icon : "success"
                       }).then(function() {
                            window.location = "user-order.php";
                       });
                       </script>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

</body>

</html>