<?php
session_start();
include 'db.php';
if ($_SESSION['role_login'] == 'user') {

    echo '<script>window.location="logout.php"</script>';
} else if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}

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

        .section .container .box1 button:hover {
            background-color: black;
            color: white;
        }
    </style>
</head>

<body>

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
                <h2>Tambah Data Produk</h2>
                <div class="box1">
                    <button><a href="product-data.php" style="text-decoration: none ;">Kembali</a></button><br><br>
                </div>
                <div class="box">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <h4>Pilih Kategori</h4>
                        <select class="input-control" name="kategori" required>
                            <option value="">--Pilih--</option>
                            <?php
                            $kategori = mysqli_query($conn, "SELECT * FROM data_category ORDER BY category_id DESC");
                            while ($r = mysqli_fetch_array($kategori)) {
                            ?>
                                <option value="<?php echo $r['category_id'] ?>"><?php echo $r['category_name'] ?> </option>
                            <?php } ?>
                        </select>
                        <h4>ID Barang</h4>
                        <input type="text" name="idbarang" class="input-control" placeholder="ID Produk" required>
                        <h4>Nama Barang</h4>
                        <input type="text" name="nama" class="input-control" placeholder="Nama Produk" required>
                        <h4>Harga Barang</h4>
                        <input type="text" name="harga" class="input-control" placeholder="Harga">
                        <h4>Gambar Barang</h4>
                        <input type="file" name="gambar" class="input-control" required>
                        <h4>Deskripsi Barang</h4>
                        <textarea name="deskripsi" class="input-control" placeholder="Deskripsi"></textarea><br>
                        <h4>ID Kantor</h4>
                        <input type="text" name="idkantor" class="input-control" readonly>
                        <h4>Jumlah Stok</h4>
                        <input type="text" name="stok" class="input-control" placeholder="Jumlah Stock" required>
                        <h4>Status Barang</h4>
                        <select name="status" class="input-control">
                            <option value="">--Pilih--</option>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                        <input type="submit" name="submit" value="Submit" class="btn">

                    </form>
                    <?php
                    if (isset($_POST['submit'])) {
                        // print_r($_FILES['gambar']);
                        //menampung input dari form
                        $idbarang = $_POST['idbarang'];
                        $kategori = $_POST['kategori'];
                        $nama = $_POST['nama'];
                        $harga = $_POST['harga'];
                        $deskripsi = $_POST['deskripsi'];
                        $status = $_POST['status'];
                        $stok = $_POST['stok'];
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
                            echo '<script>alert("Format file tidak diizinkan")</script>';
                        } else {
                            move_uploaded_file($tmp_name, './produk/' . $newname);
                            // echo '<script>alert("Berhasil Upload")</script>';

                            $insert = mysqli_query($conn, "INSERT INTO data_product VALUES (
                             '" . $idbarang . "', 
                             '" . $kategori . "',
                             '" . $idkantor . "',
                             '" . $nama . "',
                             '" . $harga . "',
                             '" . $deskripsi . "',
                             '" . $newname . "',
                             '" . $status . "',
                             '" . $stok . "',
                             null 
                        ) ");
                            if ($insert) {
                                echo '<script>alert("Tambah Data Produk Berhasil")</script>';
                                echo '<script>window.location="product-data.php"</script>';
                            }
                            // else if ($nama = ) {

                            // } 

                            else {
                                echo 'gagal' . mysqli_error($conn);
                            }
                        }

                        //proses upload file sekaligus insert ke database
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
                <h2>Tambah Data Produk</h2>
                <div class="box1">
                    <button><a href="product-data.php" style="text-decoration: none ;">Kembali</a></button><br><br>
                </div>
                <div class="box">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <h4>Pilih Kategori</h4>
                        <select class="input-control" name="kategori" required>
                            <option value="">--Pilih--</option>
                            <?php
                            $kategori = mysqli_query($conn, "SELECT * FROM data_category ORDER BY category_id DESC");
                            while ($r = mysqli_fetch_array($kategori)) {
                            ?>
                                <option value="<?php echo $r['category_id'] ?>"><?php echo $r['category_name'] ?> </option>
                            <?php } ?>
                        </select>
                        <h4>ID Barang</h4>
                        <?php $barang_id = rand() ?>
                        <input type="text" name="idbarang" class="input-control" value="<?php echo $barang_id ?>" placeholder="ID Produk" required>
                        <h4>Nama Barang</h4>
                        <input type="text" name="nama" class="input-control" placeholder="Nama Produk" required>
                        <h4>Harga Barang</h4>
                        <input type="text" name="harga" class="input-control" placeholder="Harga">
                        <h4>Gambar Barang</h4>
                        <input type="file" name="gambar" class="input-control" required>
                        <h4>Deskripsi Barang</h4>
                        <textarea name="deskripsi" class="input-control" placeholder="Deskripsi"></textarea><br>
                        <h4>Perwakilan</h4>
                        <select name="idkantor" class="input-control" required>
                            <option value="">--Pilih Perwakilan--</option>
                            <?php
                            $kantor = mysqli_query($conn, "SELECT * FROM data_office ORDER BY office_id ");
                            while ($row_kantor = mysqli_fetch_array($kantor)) {
                            ?>
                                <option value="<?php echo $row_kantor['office_id'] ?>"><?php echo $row_kantor['office_name'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                        <h4>Jumlah Stok</h4>
                        <input type="text" name="stok" class="input-control" placeholder="Jumlah Stock" required>
                        <h4>Status Barang</h4>
                        <select name="status" class="input-control">
                            <option value="">--Pilih--</option>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                        <input type="submit" name="submit" value="Submit" class="btn">

                    </form>
                    <?php
                    if (isset($_POST['submit'])) {
                        // print_r($_FILES['gambar']);
                        //menampung input dari form
                        $idbarang = $_POST['idbarang'];
                        $kategori = $_POST['kategori'];
                        $nama = $_POST['nama'];
                        $harga = $_POST['harga'];
                        $deskripsi = $_POST['deskripsi'];
                        $status = $_POST['status'];
                        $stok = $_POST['stok'];
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
                            echo '<script>alert("Format file tidak diizinkan")</script>';
                        } else {
                            move_uploaded_file($tmp_name, './produk/' . $newname);
                            // echo '<script>alert("Berhasil Upload")</script>';

                            $insert = mysqli_query($conn, "INSERT INTO data_product VALUES (
                             '" . $idbarang . "', 
                             '" . $kategori . "',
                             '" . $idkantor . "',
                             '" . $nama . "',
                             '" . $harga . "',
                             '" . $deskripsi . "',
                             '" . $newname . "',
                             '" . $status . "',
                             '" . $stok . "',
                             null 
                        ) ");
                            if ($insert) {
                                echo '<script>alert("Tambah Data Produk Berhasil")</script>';
                                echo '<script>window.location="product-data.php"</script>';
                            }
                            // else if ($nama = ) {

                            // } 

                            else {
                                echo 'gagal' . mysqli_error($conn);
                            }
                        }

                        //proses upload file sekaligus insert ke database
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
    <?php
    }
    ?>


</body>

</html>