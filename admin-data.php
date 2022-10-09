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
    <script src="js/sweetalert.min.js"></script>
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
            <h3>Data Admin Tiap Perwakilan</h3>
            <div class="box">
                <p><a href="add-admin.php">Tambah Data Admin</a></p><br>
                <!-- <button><a href="add-product.php" style="text-decoration:none ;">Tambah Data</a></button> -->
                <table border="1" cellspacing="0" class="table">
                    <thead>
                        <tr>
                            <th width="60px">No</th>
                            <th>ID User</th>
                            <th>Nama User</th>
                            <th>Username Akun</th>
                            <th>Telpon User</th>
                            <th>Email User</th>
                            <th>Alamat User</th>
                            <th width="150px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $admin = mysqli_query($conn, "SELECT * FROM data_admin ORDER BY admin_id DESC ");
                        if (mysqli_num_rows($admin) > 0) {
                            while ($row = mysqli_fetch_array($admin)) {
                        ?>
                                <tr>
                                    <td><?php echo $no++ ?></td>
                                    <td><?php echo $row['admin_id'] ?></td>
                                    <td><?php echo $row['admin_name'] ?></td>
                                    <td><?php echo $row['admin_username'] ?></td>
                                    <td><?php echo $row['admin_telp'] ?></td>
                                    <td><?php echo $row['admin_email'] ?></td>
                                    <td><?php echo $row['admin_address'] ?></td>
                                    <td>
                                        <a href="edit-admin.php?id=<?php echo $row['admin_id'] ?>">Edit</a> || <a href="delete-data.php?idu=<?php echo $row['admin_id'] ?>" onclick="return confirm('R U Sure about dat ?') ">Hapus</a>
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
    <footer>
        <div class="container">
            <small>Copyright &copy; 2022 - Universitas Pertamina</small>
        </div>
    </footer>

</body>

</html>