<?php
session_start();
include 'db.php';
//Kondisi Supaya User & Non User tidak dapat akses page ini
if ($_SESSION['role_login'] == 'user') {
    echo '<script>window.location="logout.php"</script>';
} else if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}

$produk = mysqli_query($conn, "SELECT * FROM data_product WHERE product_id = '" . $_GET['id'] . "' ");
if (mysqli_num_rows($produk) == 0) {
    echo '<script>window.location="product-data.php"</script>';
}
$p = mysqli_fetch_object($produk);
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                    $keranjang = mysqli_query($conn, "SELECT * FROM data_transaction WHERE office_id = '" . $kantor_admin . "' ORDER BY cart_id");
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
                <h3>Edit Data Barang</h3>
                <div class="box">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <h3>Nama Barang</h3>
                        <input type="text" name="nama" class="input-control" placeholder="Nama Produk" value="<?php echo $p->product_name ?>" required>
                        <h4>ID Barang</h4>
                        <input type="text" name="idbarang" class="input-control" value="<?php echo $p->product_id ?>" readonly>
                        <br><br>
                        <h4>Kategori Barang</h4>
                        <select class="input-control" name="kategori" required>
                            <option value="">--Pilih--</option>
                            <?php
                            $kategori = mysqli_query($conn, "SELECT * FROM data_category ORDER BY category_id DESC");
                            while ($r = mysqli_fetch_array($kategori)) {
                            ?>
                                <option value="<?php echo $r['category_id'] ?>" <?php echo ($r['category_id'] == $p->category_id) ?
                                                                                    'selected' : ''; ?>><?php echo $r['category_name'] ?> </option>
                            <?php } ?>
                        </select>
                        <h4>Satuan Barang</h4>
                        <select name="satuan" class="input-control" required>
                            <option value="">--Pilih--</option>
                            <?php
                            $satuan = mysqli_query($conn, "SELECT * FROM data_unit ORDER BY unit_name");
                            while ($fa_satuan = mysqli_fetch_array($satuan)) {
                            ?>
                                <option value="<?php echo $fa_satuan['unit_id'] ?>" <?php echo ($fa_satuan['unit_id'] == $p->unit_id) ?
                                                                                        'selected' : ''; ?>><?php echo $fa_satuan['unit_name'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                        <h4>Harga Barang</h4>
                        <input type="text" name="harga" class="input-control" placeholder="Harga" value="<?php echo $p->product_price ?>">

                        <h4>Gambar Barang</h4>
                        <img src="produk/<?php echo $p->product_image ?>" width="100px">
                        <input type="hidden" name="gambar" value="<?php echo $p->product_image ?>">
                        <input type="file" name="gambar" class="chg-pic" value="Ubah Gambar">
                        <br><br>
                        <h4>Deskripsi Barang</h4>
                        <textarea name="deskripsi" class="input-control" placeholder="Deskripsi"><?php echo $p->product_description ?></textarea><br>
                        <h4>Status Barang</h4>
                        <select name="status" class="input-control">
                            <option value="">--Pilih--</option>
                            <option value="1" <?php echo ($p->product_status == 1) ? 'selected' : ''; ?>>Aktif</option>
                            <option value="0" <?php echo ($p->product_status == 0) ? 'selected' : ''; ?>>Tidak Aktif</option>
                        </select>
                        <input type="submit" name="submit" value="Submit" class="btn">
                    </form>
                    <?php
                    if (isset($_POST['submit'])) {
                        $idbarang = $_POST['idbarang'];
                        $kategori = $_POST['kategori'];
                        $satuan = $_POST['satuan'];
                        $nama = $_POST['nama'];
                        $harga = $_POST['harga'];
                        $deskripsi = $_POST['deskripsi'];
                        $status = $_POST['status'];

                        //menampung data file yang diupload
                        $filename = $_FILES['gambar']['name'];
                        $tmp_name = $_FILES['gambar']['tmp_name'];

                        $type1 = explode('.', $filename);
                        $type2 = $type1[1];

                        $newname = 'produk' . time() . '.' . $type2;

                        //menampung data format file yang diizinkan
                        $tipe_diizinkan = array('jpg', 'jpeg', 'png', 'gif');

                        //validasi format file
                        if (!in_array($type2, $tipe_diizinkan)) {
                            $update = mysqli_query($conn, "UPDATE data_product SET 
                            category_id = '" . $kategori . "',
                            unit_id = '" . $satuan . "',
                            product_name= '" . $nama . "',
                            product_price = '" . $harga . "',
                            product_description = '" . $deskripsi . "',
                            product_status = '" . $status . "'
                            WHERE product_id = '" . $p->product_id . "'
                    ");

                            if ($update) {
                                echo '<script>Swal.fire({
                                    title: "Berhasil Edit Barang",
                                    text: "Klik OK Untuk Lanjut",
                                    icon : "success"
                               }).then(function() {
                                    window.location = "product-data.php";
                               });
                               </script>';
                            } else {
                                echo 'gagal' . mysqli_error($conn);
                            }
                        } else {
                            move_uploaded_file($tmp_name, './produk/' . $newname);

                            $update = mysqli_query($conn, "UPDATE data_product SET 
                            category_id = '" . $kategori . "',
                            unit_id = '" . $satuan . "',
                            product_name= '" . $nama . "',
                            product_price = '" . $harga . "',
                            product_image = '" . $newname . "',
                            product_description = '" . $deskripsi . "',
                            product_status = '" . $status . "'
                            WHERE product_id = '" . $p->product_id . "'
                    ");

                            if ($update) {
                                echo '<script>Swal.fire({
                                    title: "Berhasil Edit Barang",
                                    text: "Klik OK Untuk Lanjut",
                                    icon : "success"
                               }).then(function() {
                                    window.location = "product-data.php";
                               });
                               </script>';
                            } else {
                                echo 'gagal' . mysqli_error($conn);
                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <!----------------------------------------- SUPER ADMIN --------------------------------------->
    <?php
    } else if ($_SESSION['role_login'] == 'super') {
    ?>
        <!-- header -->
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

        <!-- Content -->
        <div class="section">
            <div class="container">
                <h3>Edit Data Barang</h3>
                <div class="box">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <h3>Nama Barang</h3>
                        <input type="text" name="nama" class="input-control" placeholder="Nama Produk" value="<?php echo $p->product_name ?>" required>
                        <h4>ID Barang</h4>
                        <input type="text" name="idbarang" class="input-control" value="<?php echo $p->product_id ?>" readonly>
                        <br><br>
                        <h4>Perwakilan</h4>
                        <select name="perwakilan" class="input-control" required>
                            <option value="">--Pilih--</option>
                            <?php
                            $perwakilan = mysqli_query($conn, "SELECT * FROM data_office ORDER BY office_id");
                            while ($fa_perwakilan = mysqli_fetch_array($perwakilan)) {
                            ?>
                                <option value="<?php echo $fa_perwakilan['office_id'] ?>" <?php echo ($fa_perwakilan['office_id'] == $_SESSION['a_global']->office_id) ?
                                                                                                'selected' : ''; ?>><?php echo $fa_perwakilan['office_name'] ?> </option>
                            <?php
                            }
                            ?>
                        </select>
                        <h4>Kategori Barang</h4>
                        <select class="input-control" name="kategori" required>
                            <option value="">--Pilih--</option>
                            <?php
                            $kategori = mysqli_query($conn, "SELECT * FROM data_category ORDER BY category_name");
                            while ($r = mysqli_fetch_array($kategori)) {
                            ?>
                                <option value="<?php echo $r['category_id'] ?>" <?php echo ($r['category_id'] == $p->category_id) ?
                                                                                    'selected' : ''; ?>><?php echo $r['category_name'] ?> </option>
                            <?php } ?>
                        </select>
                        <h4>Satuan Barang</h4>
                        <select name="satuan" class="input-control" required>
                            <option value="">--Pilih--</option>
                            <?php
                            $satuan = mysqli_query($conn, "SELECT * FROM data_unit ORDER BY unit_name");
                            while ($fa_satuan = mysqli_fetch_array($satuan)) {
                            ?>
                                <option value="<?php echo $fa_satuan['unit_id'] ?>" <?php echo ($fa_satuan['unit_id'] == $p->unit_id) ?
                                                                                        'selected' : ''; ?>><?php echo $fa_satuan['unit_name'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                        <h4>Harga Barang</h4>
                        <input type="text" name="harga" class="input-control" placeholder="Harga" value="<?php echo $p->product_price ?>">

                        <h4>Gambar Barang</h4>
                        <img src="produk/<?php echo $p->product_image ?>" width="100px">
                        <input type="hidden" name="gambar" value="<?php echo $p->product_image ?>">
                        <input type="file" value="Ubah Gambar" name="gambar" class="chg-pic">
                        <br><br>
                        <h4>Deskripsi Barang</h4>
                        <textarea name="deskripsi" class="input-control" placeholder="Deskripsi"><?php echo $p->product_description ?></textarea><br>
                        <h4>Status Barang</h4>
                        <select name="status" class="input-control">
                            <option value="">--Pilih--</option>
                            <option value="1" <?php echo ($p->product_status == 1) ? 'selected' : ''; ?>>Aktif</option>
                            <option value="0" <?php echo ($p->product_status == 0) ? 'selected' : ''; ?>>Tidak Aktif</option>
                        </select>
                        <input type="submit" name="submit" value="Submit" class="btn">
                    </form>
                    <?php
                    if (isset($_POST['submit'])) {
                        $idbarang = $_POST['idbarang'];
                        $kategori = $_POST['kategori'];
                        $satuan = $_POST['satuan'];
                        $nama = $_POST['nama'];
                        $harga = $_POST['harga'];
                        $deskripsi = $_POST['deskripsi'];
                        $status = $_POST['status'];
                        $idkantor = $_POST['idkantor'];

                        //menampung data file yang diupload
                        $filename = $_FILES['gambar']['name'];
                        $tmp_name = $_FILES['gambar']['tmp_name'];

                        $type1 = explode('.', $filename);
                        $type2 = $type1[1];

                        $newname = 'produk' . time() . '.' . $type2;

                        //menampung data format file yang diizinkan
                        $tipe_diizinkan = array('jpg', 'jpeg', 'png', 'gif');

                        //validasi format file
                        if (!in_array($type2, $tipe_diizinkan)) {
                            $update = mysqli_query($conn, "UPDATE data_product SET 
                            category_id = '" . $kategori . "',
                            unit_id = '" . $satuan . "',
                            product_name= '" . $nama . "',
                            product_price = '" . $harga . "',
                            product_description = '" . $deskripsi . "',
                            product_status = '" . $status . "'
                            WHERE product_id = '" . $p->product_id . "'
                    ");

                            if ($update) {
                                echo '<script>Swal.fire({
                                    title: "Berhasil Edit Barang",
                                    text: "Klik OK Untuk Lanjut",
                                    icon : "success"
                               }).then(function() {
                                    window.location = "product-data.php";
                               });
                               </script>';
                            } else {
                                echo 'gagal' . mysqli_error($conn);
                            }
                        } else {
                            move_uploaded_file($tmp_name, './produk/' . $newname);

                            $update = mysqli_query($conn, "UPDATE data_product SET 
                            category_id = '" . $kategori . "',
                            unit_id = '" . $satuan . "',
                            product_name= '" . $nama . "',
                            product_price = '" . $harga . "',
                            product_image = '" . $newname . "',
                            product_description = '" . $deskripsi . "',
                            product_status = '" . $status . "'
                            WHERE product_id = '" . $p->product_id . "'
                    ");

                            if ($update) {
                                echo '<script>Swal.fire({
                                    title: "Berhasil Edit Barang",
                                    text: "Klik OK Untuk Lanjut",
                                    icon : "success"
                               }).then(function() {
                                    window.location = "product-data.php";
                               });
                               </script>';
                            } else {
                                echo 'gagal' . mysqli_error($conn);
                            }
                        }

                        //query update data produk

                    }
                    ?>
                </div>
            </div>
        </div>
    <?php
    }
    ?>

    <!---------------------- Footer ----------------------------------->

    <div class="footer-dark">
        <footer>
            <div class="container">
                <div class="row" style="display: flex ;">
                    <div class="col-md-6 item text" style="margin-right: 90px ;">
                        <h3>Ombudsman RI</h3>
                        <p>Kantor Pusat <br>
                            Jl. HR. Rasuna Said Kav. C-19 Kuningan, Jakarta Selatan 12920</p>
                    </div>
                    <div class="col-sm-6 col-md-3 item" style="margin-right: 90px ;">
                        <h3>Kontak</h3>
                        <ul>
                            <li><a href="#">No Telfon : (021) 2251 3737</a></li>
                            <li><a href="#">Fax : (021) 5296 0907 / 5296 0908</a></li>
                            <li><a href="#">Email : humas@ombudsman.go.id</a></li>
                        </ul>
                    </div>
                    <br>
                    <div class="col-sm-6 col-md-3 item" style="margin-right: 90px ;">
                        <h3>About</h3>
                        <ul>
                            <li><a href="https://ombudsman.go.id/">Ombudsman</a></li>
                            <li><a href="dev-team.php">Dev Team</a></li>
                        </ul>
                    </div>
                    <br>
                </div>
                <p class="copyright">Ombudsman RI © 2022</p>
                <p class="copyright">Made By Divisi HTI & <a href="dev-team.php" target="-blank">Team RJN</a></p>
                <i class="fa-regular fa-cart-shopping"></i>
            </div>
        </footer>
    </div>

    <script>
        CKEDITOR.replace('deskripsi');
    </script>

</body>

</html>