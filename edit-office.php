<?php
session_start();
include 'db.php';
// Kondisi Supaya User, Admin, & Non User tidak dapat akses page ini
if ($_SESSION['role_login'] == 'user' || $_SESSION['role_login'] == 'admin') {
    echo '<script>window.location="logout.php"</script>';
} else if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}

$kantor = mysqli_query($conn, "SELECT * FROM data_office WHERE office_id = '" . $_GET['id'] . "' ");
if (mysqli_num_rows($kantor) == 0) {
    echo '<script>window.location="office-data.php"</script>';
}
$row_kantor = mysqli_fetch_object($kantor);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gudang Ombudsman</title>
    <!--------------------- CSS ------------------------------------->
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>
</head>

<body>
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
            <h3>Edit Data Perwakilan</h3>
            <div class="box">
                <form action="" method="POST">
                    <h4>ID Perwakilan</h4>
                    <input type="text" name="id" placeholder="ID Kantor" class="input-control" value="<?php echo $row_kantor->office_id ?>" required>
                    <h4>Perwakilan</h4>
                    <input type="text" name="nama" placeholder="Nama Perwakilan" class="input-control" value="<?php echo $row_kantor->office_name ?>" required>
                    <h4>Alamat Perwakilan</h4>
                    <textarea name="alamat" class="input-control" placeholder="Alamat Perwakilan"><?php echo $row_kantor->office_address ?></textarea required><br>
                    <h4>Nomor Telfon</h4>
                    <input type="text" name="telfon" class="input-control" placeholder="Telfon Perwakilan" value="<?php echo $row_kantor->office_telp ?>" required>
                    <h4>Fax</h4>
                    <input type="text" name="fax" class="input-control" placeholder="Fax" value="<?php echo $row_kantor->office_fax ?>" required>
                    <h4>Email Perwakilan</h4>
                    <input type="text" name="email" class="input-control" placeholder="Fax" value="<?php echo $row_kantor->office_email ?>" required>
                    <h4>Kepala Perwakilan</h4>
                    <input type="text" name="kepala" class="input-control" placeholder="Kepala Perwakilan" value="<?php echo $row_kantor->office_head ?>" required>
                    <input type="submit" name="submit" value="Submit" class="btn">
                </form>
                <?php
                if (isset($_POST['submit'])) {
                    $nama = ucwords($_POST['nama']);
                    $id = $_POST['id'];
                    $alamat = $_POST['alamat'];
                    $telfon = $_POST['telfon'];
                    $fax = $_POST['fax'];
                    $email = $_POST['email'];
                    $kepala = $_POST['kepala'];

                    $update = mysqli_query($conn, "UPDATE data_office SET  
                                           office_id = '" . $id . "', 
                                           office_name = '" . $nama . "',
                                           office_address = '" . $alamat . "',
                                           office_telp = '" . $telfon . "',
                                           office_fax = '" . $fax . "',
                                           office_email = '" . $email . "',
                                           office_head = '" . $kepala . "'
                                           WHERE office_id = '" . $k->office_id . "'
                                           ");
                    if ($update) {
                        echo '<script>Swal.fire({
                            title: "Berhasil Update Data Perwakilan",
                            text: "Klik OK Untuk Lanjut",
                            icon: "success"
                          }).then(function() {
                            window.location = "office-data.php";
                          });
                        </script>';
                    } else {
                        echo 'gagal' . mysqli_error($conn);
                    }
                }
                ?>
            </div>
        </div>
    </div>

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

</body>

</html>