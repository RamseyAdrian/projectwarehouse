<?php
session_start();
include 'db.php';
// Kondisi Supaya User, Admin, & Non User tidak dapat akses page ini
if ($_SESSION['role_login'] == 'user') {
    echo '<script>window.location="logout.php"</script>';
} else if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}

$trans = mysqli_query($conn, "SELECT * FROM data_transaction LEFT JOIN data_product USING (product_id) WHERE cart_id = '" . $_GET['id'] . "' ");
if (mysqli_num_rows($trans) == 0) {
    echo '<script>window.location="order-table.php"</script>';
}

$trans2 = mysqli_query($conn, "SELECT * FROM data_transaction LEFT JOIN data_product USING (product_id) WHERE cart_id = '" . $_GET['id'] . "' ");
$idcart = $_GET['id'];
$idkantoradmin = $_SESSION['a_global']->office_id;
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
    <!--------------------- CK Editor CDN ----------------------------->
    <script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>
    <!--------------------- Sweet Alert CDN ----------------------------->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css" rel="stylesheet" />
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!--------------------- Additional CSS ----------------------------->
    <style>
        #h2produk {
            color: red;
            font-weight: bold;
        }

        .section .container .box form #button-restock {
            background-color: red;
            color: white;
            font-weight: bold;
            padding: 5px;
            border-radius: 5px;
            margin-top: 5px;
            font-size: 15px;
        }

        .section .container .box form #button-restock:hover {
            background-color: black;
            color: white;
        }

        .section .container .box form #button-restock a {
            text-decoration: none;
        }
    </style>
</head>


<body>
    <?php
    if ($_SESSION['role_login'] == 'admin') {
    ?>
        <!---------------------- header ----------------------------------->
        <header>
            <div class="container">
                <h1><a href="dashboard.php"><img style="width: 80px ; margin-bottom :-10px ;" src="img/logo-ombudsman2.png" alt=""> Gudang Ombudsman</a></h1>
                <ul style="margin-top: 20px ;">
                    <?php
                    //Deklarasi 2 variabel untuk menampung cart_id dari table data_transaction
                    $idk_1 = "";
                    $idk_2 = "";
                    //jml_produk digunakan untuk menampung berapa banyak barang yang di-query
                    $jml_produk = 0;
                    //jml_keranjang digunakan untuk menampung berapa banyak jumlah keranjang yang ada
                    $jml_keranjang = 0;
                    //query database
                    $keranjang = mysqli_query($conn, "SELECT * FROM data_transaction WHERE office_id = '" . $idkantoradmin . "' AND status = 'Diproses Admin' ORDER BY cart_id");
                    if (mysqli_num_rows($keranjang) > 0) {
                        while ($fetch_keranjang = mysqli_fetch_array($keranjang)) {
                            $jml_produk++;
                            $idk_1 = $fetch_keranjang['cart_id'];
                            if ($idk_2 == $idk_1) { //Kondisi jika barang yang di fetch memiliki cart_id yang sama dengan barang sebelumnya
                                $jml_keranjang = $jml_keranjang * 1;
                            } else { //Jika cart_id barang yang di-fetch berbeda (dengan barang sebelumnya), maka jml_keranjang akan bertambah
                                $jml_keranjang++;
                            }
                            //cart_id barang yang di fetch, ditampung di idk_2 untuk looping (pengecekan) selanjutnya
                            $idk_2 = $fetch_keranjang['cart_id'];
                        }
                    }
                    //Hasilnya (jml_keranjang) akan ditampilkan dibagian navbar (sebelah pesanan)
                    ?>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="profile.php">Profil</a></li>
                    <li><a href="product-data.php">Data Barang</a></li>
                    <li><a href="user-data.php">Data User</a></li>
                    <li><a href="order-table.php">Pesanan (<?php echo $jml_keranjang; ?>)</a></li>
                    <li><a href="logout.php">Keluar</a></li>
                </ul>
            </div>
        </header>

        <!---------------------- Content ----------------------------------->

        <div class="section">
            <div class="container">
                <h3>Detail Pesanan</h3>
                <div class="box">
                    <?php $fetch_trans = mysqli_fetch_assoc($trans2);
                    $id_pemesan = $fetch_trans['user_id'];
                    $nama_pemesan = $fetch_trans['user_name'];
                    $id_pesanan = $fetch_trans['cart_id'];
                    ?>
                    <h2>Dipesan Oleh : <?php echo $nama_pemesan ?>/<?php echo $id_pemesan ?></h2>
                    <h4 style="color: #ccc ;">ID Pesanan : <?php echo $id_pesanan ?></h4><br>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div id="order">


                            <?php
                            $no = 1;
                            $stock_ready = 0;
                            if (mysqli_num_rows($trans) > 0) {
                                while ($fo_trans = mysqli_fetch_object($trans)) {

                            ?>
                                    <div id="items-order">
                                        <!-- <h1>Produk ke <?php echo $no ?></h1><br> -->
                                        <h2 id="h2produk"><?php echo $fo_trans->product_name ?></h2><br><br>
                                        <img src="produk/<?php echo $fo_trans->product_image ?>" width="100px">
                                        <br><br>
                                        <h3>Jumlah Pesanan</h3>
                                        <?php
                                        $satuan = mysqli_query($conn, "SELECT * FROM data_unit WHERE unit_id = '" . $fo_trans->unit_id . "' ");
                                        while ($fa_satuan = mysqli_fetch_array($satuan)) {
                                            $waktu_dipesan = $fo_trans->created;
                                        ?>
                                            <h3 style="color: red ;"><?php echo $fo_trans->quantity, " ", $fa_satuan['unit_name'] ?></h3><br>
                                            <h3>Ketersediaan Barang</h3>
                                            <h4>Stock : <?php echo $fo_trans->stock, " ", $fa_satuan['unit_name'] ?></h4>
                                        <?php
                                        }
                                        ?>
                                        <br>

                                        <?php
                                        if ($fo_trans->stock >= $fo_trans->quantity) {
                                            $stock_ready++;
                                            $update_dt = mysqli_query($conn, "UPDATE data_transaction SET 
                                    red_flag = 'not red'
                                    WHERE order_id = '" . $fo_trans->order_id . "'
                                ");
                                        ?>
                                            <h4 style="color: green ;">Stock Ready</h4>
                                        <?php
                                        } else if ($fo_trans->stock < $fo_trans->quantity) {
                                            $quantity_update = $fo_trans->stock;
                                            $update_dt = mysqli_query($conn, "UPDATE data_transaction SET 
                                    red_flag = 'red'
                                    WHERE order_id = '" . $fo_trans->order_id . "'
                                ");
                                        ?>
                                            <h4 style="color: red ;">Stock tidak mencukupi</h4>
                                            <button id="button-restock"><a href="edit-stocking-product.php?id=<?php echo $fo_trans->product_id ?>">Restock</a></button>
                                        <?php
                                        }
                                        ?>
                                        <br><br>
                                    </div>

                            <?php
                                    $no++;
                                }
                            }
                            ?>
                        </div><br>
                        <h3>Waktu Pesanan Dibuat</h3>
                        <h2 id="h2produk"><?php echo $waktu_dipesan ?></h2><br>
                        <h3>Jumlah pesanan ingin diubah ?</h3>
                        <button id="button-restock"><a href="choose-edit-product.php?id=<?php echo $idcart ?>">Edit Disini</a></button>
                        <br><br>
                        <h3>Catatan Dari Admin</h3>
                        <textarea name="notes" class="input-control"></textarea><br>
                        <br>
                        <div id="status-button">
                            <input type="submit" name="disapprove" class="disapprove-btn" value="Tidak Setuju">
                            <input type="submit" name="approve" class="approve-btn" value="Setuju">
                        </div>
                        <br>
                        <center>
                            <input style="cursor: pointer ;" type="submit" name="kembali" class="input-control" value="Kembali">
                        </center>
                    </form>

                    <?php
                    //------------------------------Kondisi Jika Semua Stock Aman & Admin pilih Setuju------------------------------------------
                    if (isset($_POST['approve']) && $stock_ready == mysqli_num_rows($trans)) {
                        $update_data_order = mysqli_query($conn, "UPDATE data_order SET 
                            status = 'Akan Diambil',
                            items_approved = '" . $stock_ready . "',
                            times_updated = NOW()
                            WHERE cart_id = '" . $idcart . "'
                        ");

                        $trans2 = mysqli_query($conn, "SELECT * FROM data_transaction LEFT JOIN data_product USING (product_id) WHERE cart_id = '" . $idcart . "' ");
                        if (mysqli_num_rows($trans2) > 0) {
                            while ($fo_trans2 = mysqli_fetch_array($trans2)) {
                                $unit = mysqli_query($conn, "SELECT * FROM data_unit WHERE unit_id = '" . $fo_trans2['unit_id'] . "' ");
                                $fa_unit = mysqli_fetch_array($unit);

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
                                $idsatuan = $fo_trans2['unit_id'];
                                $namasatuan = $fa_unit['unit_name'];
                                $idproduk = $fo_trans2['product_id'];
                                $namaproduk = $fo_trans2['product_name'];
                                $kuantitas = $fo_trans2['quantity'];
                                $notes = $_POST['notes'];

                                $update_data_transaction = mysqli_query($conn, "UPDATE data_transaction SET
                                                status = '" . $_POST['status'] . "',
                                                notes = '" . $notes . "'
                                                WHERE cart_id = '" . $idcart . "'
                                    ");

                                echo '<script>Swal.fire({
                                    title: "Pesanan User Berhasil Diproses ",
                                    text: "Klik OK Untuk Lanjut",
                                    icon : "success"
                               }).then(function() {
                                    window.location = "order-table.php";
                               });
                               </script>';
                            }
                        }
                    } else if (isset($_POST['approve']) && $stock_ready < mysqli_num_rows($trans)) {
                        echo '<script>Swal.fire({
                            title: "Stock Tidak Mencukupi!",
                            text: "Pilih Tidak Setuju atau Ubah Jumlah Pesanan !",
                            icon : "error"
                       });
                       </script>';
                    } else if (isset($_POST['disapprove']) && $stock_ready == mysqli_num_rows($trans)) {
                        $update_data_order = mysqli_query($conn, "UPDATE data_order SET 
                        status = 'Tidak Disetujui Admin',
                        items_approved = '" . $stock_ready . "',
                        times_updated = NOW()
                        WHERE cart_id = '" . $idcart . "' ");

                        $trans2 = mysqli_query($conn, "SELECT * FROM data_transaction LEFT JOIN data_product USING (product_id) WHERE cart_id = '" . $idcart . "' ");
                        if (mysqli_num_rows($trans2) > 0) {
                            while ($fo_trans2 = mysqli_fetch_array($trans2)) {
                                $unit = mysqli_query($conn, "SELECT * FROM data_unit WHERE unit_id = '" . $fo_trans2['unit_id'] . "' ");
                                $fa_unit = mysqli_fetch_array($unit);
                                if ($fo_trans2['red_flag'] == 'red') {
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
                                    $idsatuan = $fo_trans2['unit_id'];
                                    $namasatuan = $fa_unit['unit_name'];
                                    $idproduk = $fo_trans2['product_id'];
                                    $namaproduk = $fo_trans2['product_name'];
                                    $kuantitas = $fo_trans2['quantity'];
                                    $notes = $_POST['notes'];

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
                                        '" . $idsatuan . "',
                                        '" . $namasatuan . "',
                                        '" . $kuantitas . "',
                                        NOW(),
                                        '" . $fo_trans2['red_flag'] . "',
                                        'Tidak Disetujui Admin',
                                        '" . $notes . "'
    
                                    )");
                                    $delete_data_transaction = mysqli_query($conn, "DELETE FROM data_transaction WHERE data_transaction.order_id = '" . $orderid . "' ");
                                } else if ($fo_trans2['red_flag'] == 'not red') {
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
                                    $idsatuan = $fo_trans2['unit_id'];
                                    $namasatuan = $fa_unit['unit_name'];
                                    $idproduk = $fo_trans2['product_id'];
                                    $namaproduk = $fo_trans2['product_name'];
                                    $kuantitas = $fo_trans2['quantity'];
                                    $notes = $_POST['notes'];

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
                                        '" . $idsatuan . "',
                                        '" . $namasatuan . "',
                                        '" . $kuantitas . "',
                                        NOW(),
                                        '" . $fo_trans2['red_flag'] . "',
                                        'Tidak Disetujui Admin',
                                        '" . $notes . "',
                                        0
    
                                    )");
                                    $delete_data_transaction = mysqli_query($conn, "DELETE FROM data_transaction WHERE data_transaction.order_id = '" . $orderid . "' ");
                                }
                                echo '<script>Swal.fire({
                                    title: "Pesanan User Tidak Disetujui",
                                    text: "Klik OK Untuk Lanjut",
                                    icon : "success"
                               }).then(function() {
                                    window.location = "order-table.php";
                               });
                               </script>';
                            }
                        }
                    } else if (isset($_POST['disapprove']) && $stock_ready < mysqli_num_rows($trans)) {
                        $update_data_order = mysqli_query($conn, "UPDATE data_order SET 
                        status = 'Tidak Disetujui Admin',
                        items_approved = '" . $stock_ready . "',
                        times_updated = NOW()
                        WHERE cart_id = '" . $idcart . "' ");

                        $trans2 = mysqli_query($conn, "SELECT * FROM data_transaction LEFT JOIN data_product USING (product_id) WHERE cart_id = '" . $idcart . "' ");
                        if (mysqli_num_rows($trans2) > 0) {
                            while ($fo_trans2 = mysqli_fetch_array($trans2)) {
                                $unit = mysqli_query($conn, "SELECT * FROM data_unit WHERE unit_id = '" . $fo_trans2['unit_id'] . "' ");
                                $fa_unit = mysqli_fetch_array($unit);
                                if ($fo_trans2['red_flag'] == 'red') {
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
                                    $idsatuan = $fo_trans2['unit_id'];
                                    $namasatuan = $fa_unit['unit_name'];
                                    $idproduk = $fo_trans2['product_id'];
                                    $namaproduk = $fo_trans2['product_name'];
                                    $kuantitas = $fo_trans2['quantity'];
                                    $notes = $_POST['notes'];

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
                                    '" . $idsatuan . "',
                                    '" . $namasatuan . "',
                                    '" . $kuantitas . "',
                                    NOW(),
                                    '" . $fo_trans2['red_flag'] . "',
                                    '" . $_POST['status'] . "',
                                    '" . $notes . "'

                                )");
                                    $delete_data_transaction = mysqli_query($conn, "DELETE FROM data_transaction WHERE data_transaction.order_id = '" . $orderid . "' ");
                                } else if ($fo_trans2['red_flag'] == 'not red') {
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
                                    $idsatuan = $fo_trans2['unit_id'];
                                    $namasatuan = $fa_unit['unit_name'];
                                    $idproduk = $fo_trans2['product_id'];
                                    $namaproduk = $fo_trans2['product_name'];
                                    $kuantitas = $fo_trans2['quantity'];
                                    $notes = $_POST['notes'];

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
                                    '" . $idsatuan . "',
                                    '" . $namasatuan . "',
                                    '" . $kuantitas . "',
                                    NOW(),
                                    '" . $fo_trans2['red_flag'] . "',
                                    '" . $_POST['status'] . "',
                                    '" . $notes . "'

                                )");
                                    $delete_data_transaction = mysqli_query($conn, "DELETE FROM data_transaction WHERE data_transaction.order_id = '" . $orderid . "' ");
                                }
                            }
                        }
                    } else if (isset($_POST['kembali'])) {
                        echo '<script>window.location="order-table.php"</script>';
                    }
                    ?>
                </div>
            </div>
        </div>

        <script>
            CKEDITOR.replace('notes');
        </script>
    <?php
    } else if ($_SESSION['role_login'] == 'super') {
    ?>
        <!---------------------- header ----------------------------------->

        <header>
            <div class="container">
                <h1><a href="dashboard.php"><img style="width: 70px ; margin-bottom :-10px ;" src="img/logo-ombudsman2.png" alt=""> Gudang Ombudsman</a></h1>
                <ul style="margin-top: 20px ;">
                    <li><a href="dashboard.php">Dashboard </a></li>
                    <li><a href="profile.php">Profil</a></li>
                    <li><a href="category-data.php">Kategori</a></li>
                    <li><a href="product-data.php">Barang</a></li>
                    <li><a href="unit-data.php">Satuan</a></li>
                    <li><a href="office-data.php">Perwakilan</a></li>
                    <li><a href="admin-data.php">Admin</a></li>
                    <li><a href="user-data.php">User</a></li>
                    <li><a href="order-table.php">Pesanan</a></li>
                    <li><a href="logout.php">Keluar</a></li>
                </ul>
            </div>
        </header>

        <!---------------------- Content ----------------------------------->

        <div class="section">
            <div class="container">
                <h3>Detail Pesanan</h3>
                <style>
                    #h2produk {
                        color: red;
                        font-weight: bold;
                    }

                    .section .container .box form #button-restock {
                        background-color: red;
                        color: white;
                        font-weight: bold;
                        padding: 5px;
                        border-radius: 5px;
                        margin-top: 5px;
                        font-size: 15px;
                    }

                    .section .container .box form #button-restock:hover {
                        background-color: black;
                        color: white;
                    }

                    .section .container .box form #button-restock a {
                        text-decoration: none;
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
                                <h3>Jumlah Pesanan</h3>
                                <?php
                                $satuan = mysqli_query($conn, "SELECT * FROM data_unit WHERE unit_id = '" . $fo_trans->unit_id . "' ");
                                while ($fa_satuan = mysqli_fetch_array($satuan)) {
                                ?>
                                    <h3 style="color: red ;"><?php echo $fo_trans->quantity, " ", $fa_satuan['unit_name'] ?></h3><br>
                                    <h3>Waktu Pesanan Dibuat</h3>
                                    <h3 style="color: red ;"><?php echo $fo_trans->created ?></h3><br>
                                    <h3>Ketersediaan Barang</h3>
                                    <h4>Stock : <?php echo $fo_trans->stock, " ", $fa_satuan['unit_name'] ?></h4>
                                <?php
                                }
                                ?>
                                <br>

                                <?php
                                if ($fo_trans->stock >= $fo_trans->quantity) {
                                    $stock_ready++;
                                    $update_dt = mysqli_query($conn, "UPDATE data_transaction SET 
                                    red_flag = 'not red'
                                    WHERE order_id = '" . $fo_trans->order_id . "'
                                ");
                                ?>
                                    <h4 style="color: green ;">Stock Ready</h4>
                                <?php
                                } else if ($fo_trans->stock < $fo_trans->quantity) {
                                    $quantity_update = $fo_trans->stock;
                                    $update_dt = mysqli_query($conn, "UPDATE data_transaction SET 
                                    red_flag = 'red'
                                    WHERE order_id = '" . $fo_trans->order_id . "'
                                ");
                                ?>
                                    <h4 style="color: red ;">Stock tidak mencukupi</h4>
                                    <button id="button-restock"><a href="edit-stocking-product.php?id=<?php echo $fo_trans->product_id ?>">Restock</a></button>
                                <?php
                                }
                                ?>
                                <br><br>
                        <?php
                                $no++;
                            }
                        }
                        ?>
                        <center>
                            <input type="submit" name="submit" value="Kembali" class="btn">
                        </center>
                    </form>
                    <?php
                    if (isset($_POST['submit'])) {
                        echo '<script>window.location="order-table.php"</script>';
                    }
                    ?>
                </div>
            </div>
        </div>



        <script>
            CKEDITOR.replace('notes');
        </script>
    <?php
    }
    ?>


</body>

</html>