<?php
session_start();
include 'db.php';

if ($_SESSION['role_login'] == 'user' || $_SESSION['role_login'] == 'admin') {
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
            <h3>Kantor Perwakilan Ombudsman</h3>
            <div class="box">
                <!-- <p><a href="add-product.php">Tambah Data Produk</a></p><br> -->
                <!-- <button><a href="add-product.php" style="text-decoration:none ;">Tambah Data</a></button> -->
                <table border="1" cellspacing="0" class="table">
                    <thead>
                        <tr>
                            <th width="60px">No</th>
                            <th>ID</th>
                            <th>Perwakilan</th>
                            <th>Kepala Perwakilan</th>
                            <th>No Telfon</th>
                            <th>Fax</th>
                            <th>Email</th>
                            <th width="60px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $perwakilan = mysqli_query($conn, "SELECT * FROM data_office ORDER BY office_id");
                        if (mysqli_num_rows($perwakilan) > 0) {
                            while ($row = mysqli_fetch_array($perwakilan)) {
                        ?>
                                <tr>
                                    <td><?php echo $no++ ?></td>
                                    <td><?php echo $row['office_id'] ?></td>
                                    <td><?php echo $row['office_name'] ?></td>
                                    <td><?php echo $row['office_head'] ?></td>
                                    <td><?php echo $row['office_telp'] ?></td>
                                    <td><?php echo $row['office_fax'] ?></td>
                                    <td><?php echo $row['office_email'] ?></td>
                                    <td>
                                        <a href="edit-office.php?id=<?php echo $row['office_id'] ?>">Edit</a></a>
                                    </td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr>
                                <td colspan="8">Tidak Ada Data</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Footer -->
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
                            <li><a href="#">Company</a></li>
                            <li><a href="#">Team</a></li>
                        </ul>
                    </div>
                    <br>

                </div>
                <p class="copyright">Ombudsman RI © 2022</p>
                <p class="copyright">Made By Divisi HTI & Team RJN</p>
            </div>
        </footer>
    </div>

</body>

</html>