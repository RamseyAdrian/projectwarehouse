<?php
session_start();
include 'db.php';
if ($_SESSION['role_login'] == 'user') {

    echo '<script>window.location="logout.php"</script>';
} else if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}

$admin_office = $_SESSION['a_global']->office_id;
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
</head>

<body>
    <!--------------------------------------------------------------------------------- ADMIN ---------------------------------------------------------------------------->
    <?php
    if ($_SESSION['role_login'] == 'admin') {
    ?>
        <!-- header -->
        <header>
            <div class="container">
                <h1><a href="dashboard.php"><img style="width: 80px ; margin-bottom :-10px ;" src="img/logo-ombudsman2.png" alt=""> Gudang Ombudsman</a></h1>
                <ul style="margin-top: 20px ;">
                    <?php
                    $idk_1 = "";
                    $idk_2 = "";
                    $jml_produk = 0;
                    $jml_keranjang = 0;
                    $keranjang = mysqli_query($conn, "SELECT * FROM data_transaction WHERE office_id = '" . $admin_office . "' ORDER BY cart_id");
                    if (mysqli_num_rows($keranjang) > 0) {
                        while ($fetch_keranjang = mysqli_fetch_array($keranjang)) {
                            $jml_produk++;
                            $idk_1 = $fetch_keranjang['cart_id'];
                            if ($idk_2 == $idk_1) {
                                $jml_keranjang = $jml_keranjang * 1;
                            } else {
                                $jml_keranjang++;
                            }
                            $idk_2 = $fetch_keranjang['cart_id'];
                        }
                    }
                    ?>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="profile.php">Profil</a></li>
                    <li><a href="product-data.php">Data Produk</a></li>
                    <li><a href="user-data.php">Data User</a></li>
                    <li><a href="order-table.php">Pesanan (<?php echo $jml_keranjang; ?>)</a></li>
                    <li><a href="logout.php">Keluar</a></li>
                </ul>
            </div>
        </header>

        <!-- Content -->
        <div class="section">
            <div class="container">
                <h3>Data User</h3>
                <div class="box">
                    <!-- <p><a href="add-user.php">Tambah Data</a></p><br> -->
                    <table border="1" cellspacing="0" class="table">
                        <thead>
                            <tr>
                                <th width="60px">No</th>
                                <!-- <th>Perwakilan</th> -->
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
                            $user = mysqli_query($conn, "SELECT * FROM data_user WHERE office_id = '" . $admin_office . "'  ORDER BY user_id DESC ");
                            if (mysqli_num_rows($user) > 0) {
                                while ($row = mysqli_fetch_array($user)) {
                            ?>
                                    <tr>
                                        <td><?php echo $no++ ?></td>
                                        <td><?php echo $row['user_id'] ?></td>
                                        <td><?php echo $row['user_name'] ?></td>
                                        <td><?php echo $row['user_username'] ?></td>
                                        <td><?php echo $row['user_telp'] ?></td>
                                        <td><?php echo $row['user_email'] ?></td>
                                        <td><?php echo $row['user_address'] ?></td>
                                        <td>
                                            <a href="edit-user.php?id=<?php echo $row['user_id'] ?>">Edit</a> || <a href="delete-data.php?idu=<?php echo $row['user_id'] ?>" onclick="return confirm('R U Sure about dat ?') ">Hapus</a>
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
                <small>Copyright &copy; 2022 - Ramsey Adrian</small>
            </div>
        </footer>
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
                <h3>Data User</h3>
                <div class="box">
                    <p><a href="add-user.php">Tambah Data</a></p><br>
                    <!-- <button><a href="add-product.php" style="text-decoration:none ;">Tambah Data</a></button> -->
                    <table border="1" cellspacing="0" class="table">
                        <thead>
                            <tr>
                                <th width="60px">No</th>
                                <!-- <th>Perwakilan</th> -->
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
                            $user = mysqli_query($conn, "SELECT * FROM data_user ORDER BY user_id DESC ");
                            if (mysqli_num_rows($user) > 0) {
                                while ($row = mysqli_fetch_array($user)) {
                            ?>
                                    <tr>
                                        <td><?php echo $no++ ?></td>
                                        <td><?php echo $row['user_id'] ?></td>
                                        <td><?php echo $row['user_name'] ?></td>
                                        <td><?php echo $row['user_username'] ?></td>
                                        <td><?php echo $row['user_telp'] ?></td>
                                        <td><?php echo $row['user_email'] ?></td>
                                        <td><?php echo $row['user_address'] ?></td>
                                        <td>
                                            <a href="edit-user.php?id=<?php echo $row['user_id'] ?>">Edit</a> || <a href="delete-data.php?idu=<?php echo $row['user_id'] ?>" onclick="return confirm('R U Sure about dat ?') ">Hapus</a>
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
                <small>Copyright &copy; 2022 - Ramsey Adrian</small>
            </div>
        </footer>
    <?php
    }
    ?>

</body>

</html>