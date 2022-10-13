<?php
session_start();
include 'db.php';
if ($_SESSION['role_login'] == 'user' || $_SESSION['role_login'] == 'admin') {

    echo '<script>window.location="logout.php"</script>';
} else if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}

$kantor = mysqli_query($conn, "SELECT * FROM data_office WHERE office_id = '" . $_GET['id'] . "' ");
if (mysqli_num_rows($kantor) == 0) {
    echo '<script>window.location="office-data.php"</script>';
}
$k = mysqli_fetch_object($kantor);

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
    <script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>
</head>

<body>
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
            <h3>Edit Data Perwakilan</h3>
            <div class="box">
                <form action="" method="POST">
                    <h4>ID Perwakilan</h4>
                    <input type="text" name="id" placeholder="ID Kantor" class="input-control" value="<?php echo $k->office_id ?>" required>
                    <h4>Perwakilan</h4>
                    <input type="text" name="nama" placeholder="Nama Perwakilan" class="input-control" value="<?php echo $k->office_name ?>" required>
                    <h4>Alamat Perwakilan</h4>
                    <textarea name="alamat" class="input-control" placeholder="Alamat Perwakilan"><?php echo $k->office_address ?></textarea required><br>
                    <h4>Nomor Telfon</h4>
                    <input type="text" name="telfon" class="input-control" placeholder="Telfon Perwakilan" value="<?php echo $k->office_telp ?>" required>
                    <h4>Fax</h4>
                    <input type="text" name="fax" class="input-control" placeholder="Fax" value="<?php echo $k->office_fax ?>" required>
                    <h4>Email Perwakilan</h4>
                    <input type="text" name="email" class="input-control" placeholder="Fax" value="<?php echo $k->office_email ?>" required>
                    <h4>Kepala Perwakilan</h4>
                    <input type="text" name="kepala" class="input-control" placeholder="Kepala Perwakilan" value="<?php echo $k->office_head ?>" required>
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
                            title: "Berhasil Update Data Perwakilan !",
                            text: "Klik OK Untuk Lanjut.",
                            icon: "success"
                          },
                          function(){
                            window.location="office-data.php"
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

    <!-- Footer -->
    <footer>
        <div class="container">
            <small>Copyright &copy; 2022 - Universitas Pertamina</small>
        </div>
    </footer>

</body>

</html>