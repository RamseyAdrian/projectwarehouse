<?php
session_start();
include 'db.php';
if ($_SESSION['role_login'] == 'user') {

    echo '<script>window.location="logout.php"</script>';
} else if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}

$trans = mysqli_query($conn, "SELECT * FROM data_transaction LEFT JOIN data_product USING (product_id) WHERE cart_id = '" . $_GET['id'] . "' ");
if (mysqli_num_rows($trans) == 0) {
    echo '<script>window.location="order-table.php"</script>';
}
$idcart = $_GET['id'];

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
    <script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
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
            <h3>Detail Pesanan</h3>
            <style>
                #h2produk {
                    color: red;
                    font-weight: bold;
                }
            </style>
            <div class="box">
                <form action="" method="POST" enctype="multipart/form-data">
                    <?php
                    $no = 1;
                    $stock_ready = 0;
                    if (mysqli_num_rows($trans) > 0) {
                        while ($fo_trans = mysqli_fetch_object($trans)) {
                    ?>

                            <h1>Produk ke <?php echo $no ?></h1><br>
                            <!-- <h1><?php echo $fo_trans->user_id ?></h1> -->
                            <h2 id="h2produk"><?php echo $fo_trans->product_name ?></h2><br><br>
                            <img src="produk/<?php echo $fo_trans->product_image ?>" width="100px">
                            <br><br>
                            <h4>Jumlah Pesanan</h4>
                            <input type="text" name="quantity" class="input-control" value="<?php echo $fo_trans->quantity ?>" readonly>
                            <h4>Waktu Pesanan Dibuat</h4>
                            <input type="text" name="waktu" class="input-control" value="<?php echo $fo_trans->created ?>" readonly>
                            <h3>Ketersediaan Barang</h3>
                            <h4>Stock : <?php echo $fo_trans->stock ?></h4>
                            <?php
                            if ($fo_trans->stock >= $fo_trans->quantity) {
                                $stock_ready++;
                            ?>
                                <h4 style="color: green ;">Stock Ready</h4>
                            <?php
                            } else if ($fo_trans->stock < $fo_trans->quantity) {
                            ?>
                                <h4 style="color: red ;">Stock tidak mencukupi</h4>
                            <?php
                            }
                            ?>
                            <br>
                            <h4>Catatan Dari Admin</h4>
                            <textarea name="notes" class="input-control"><?php echo $fo_trans->notes ?></textarea><br>

                    <?php
                            $no++;
                        }
                    }
                    ?>
                    <br><br>
                    <h2>Status Pemesanan Barang</h2>
                    <select name="status" class="input-control" required>
                        <option value="" style="font-weight: bold ;">--Pilih--</option>
                        <option value="Diproses" style="font-weight: bold ;">Hold</option>
                        <option value="Berhasil" style="font-weight: bold ;">Approved</option>
                        <option value="Gagal" style="font-weight: bold ;">Disapproved</option>
                        <option value="Berhasil sebagian" style="font-weight: bold ;">Sebagian Disetujui</option>
                    </select>
                    <br><br>
                    <center>
                        <input type="submit" name="submit" class="btn" value="Submit">
                    </center>
                </form>


                <?php
                if (isset($_POST['submit'])) {
                    if ($_POST['status'] == "Berhasil" && $stock_ready == mysqli_num_rows($trans)) {
                        $update_data_order = mysqli_query($conn, "UPDATE data_order SET 
                            status = '" . $_POST['status'] . "',
                            items_approved = '" . $stock_ready . "',
                            times_updated = NOW()
                            WHERE cart_id = '" . $idcart . "'
                        ");
                        $trans2 = mysqli_query($conn, "SELECT * FROM data_transaction LEFT JOIN data_product USING (product_id) WHERE cart_id = '" . $idcart . "' ");
                        if (mysqli_num_rows($trans2) > 0) {
                            while ($fo_trans2 = mysqli_fetch_array($trans2)) {
                                $orderid = $fo_trans2['order_id'];
                                $keranjang = $fo_trans2['cart_id'];
                                $iduser = $fo_trans2['user_id'];
                                $namauser = $fo_trans2['user_name'];
                                $emailuser = $fo_trans2['user_email'];
                                $telpuser = $fo_trans2['user_telp'];
                                $namakantor = $fo_trans2['office_name'];
                                $idkantor = $fo_trans2['office_id'];
                                $productname = $fo_trans2['product_name'];
                                $idkategori = $fo_trans2['category_id'];
                                $namakategori = $fo_trans2['category_name'];
                                $idproduk = $fo_trans2['product_id'];
                                $namaproduk = $fo_trans2['product_name'];
                                $kuantitas = $fo_trans2['quantity'];
                                $notes = $_POST['notes'];

                                $stokbarang = $fo_trans2['stock'];
                                $stokbarang_after = $stokbarang - $kuantitas;

                                $update_stock = mysqli_query($conn, "UPDATE data_product SET
                                        stock = '" . $stokbarang_after . "'
                                        WHERE product_id = '" . $idproduk . "'
                                 ");

                                $delete_data_order = mysqli_query($conn, "DELETE FROM data_order WHERE data_order.cart_id = '" . $idcart . "' ");


                                $insert_transaction_history = mysqli_query($conn, "INSERT INTO transaction_history VALUES (
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
                                    '" . $_POST['status'] . "',
                                    '" . $notes . "'

                                )");
                                $delete_data_transaction = mysqli_query($conn, "DELETE FROM data_transaction WHERE data_transaction.order_id = '" . $orderid . "' ");
                            }
                        }
                    } else if ($_POST['status'] == "Diproses" && $stock_ready == mysqli_num_rows($trans)) {
                        echo '<script>window.location="order-table.php"</script>';
                    } else if ($_POST['status'] == "Gagal" && $stock_ready == mysqli_num_rows($trans)) {
                    }

                    // if ($update) {
                    //     echo '<script>Swal.fire({
                    //     title: "Update Pesanan Berhasil",
                    //     text: "Klik OK Untuk Lanjut.",
                    //     icon: "success"
                    //   },
                    //   function(){
                    //     window.location="order-table.php"
                    //   });
                    // </script>';
                    // } else {
                    //     echo 'gagal' . mysqli_error($conn);
                    // }
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <small>Copyright &copy; 2022 - Ramsey Adrian</small>
        </div>
    </footer>
    <script>
        CKEDITOR.replace('deskripsi');
    </script>

</body>

</html>