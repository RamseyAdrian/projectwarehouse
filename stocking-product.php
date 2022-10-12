<?php
session_start();
include 'db.php';
if ($_SESSION['role_login'] == 'user') {

    echo '<script>window.location="logout.php"</script>';
} else if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}
$idkantoradmin = $_SESSION['a_global']->office_id;
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
    <!--------------------------------------------------------------------------------- ADMIN ---------------------------------------------------------------------------->
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
                <h2>Stocking Barang</h2>
                <!-- <div class="box1">
                    <button><a href="add-product.php" style="text-decoration: none ;">Tambah Data Produk</a></button><br><br>
                    <button><a href="stocking-product.php" style="text-decoration: none ;">Stocking Barang</a></button>
                    <button><a href="stocking-history.php" style="text-decoration: none ;">Riwayat Stocking</a></button>
                </div> -->
                <div class="box">

                    <table border="1" cellspacing="0" class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <!-- <th>Perwakilan</th> -->
                                <th>Kategori</th>
                                <!-- <th>ID Produk</th> -->
                                <th>Nama Produk</th>
                                <!-- <th>Harga</th> -->
                                <!-- <th>Deskripsi</th> -->
                                <th>Gambar</th>
                                <th>Status</th>
                                <th>Stock</th>
                                <th width="150px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $produk = mysqli_query($conn, "SELECT * FROM data_product LEFT JOIN data_category  USING (category_id) WHERE office_id = '" . $idkantoradmin . "' ORDER BY product_id DESC ");
                            if (mysqli_num_rows($produk) > 0) {
                                while ($row = mysqli_fetch_array($produk)) {
                                    $idperwakilan = $row['office_id'];
                                    $namaperwakilan = mysqli_query($conn, "SELECT * FROM data_office WHERE office_id = '" . $idperwakilan . "' ");
                                    $row_np = mysqli_fetch_array($namaperwakilan);
                            ?>
                                    <tr>
                                        <td><?php echo $no++ ?></td>
                                        <!-- <td><?php echo $row_np['office_name'] ?></td> -->
                                        <td><?php echo $row['category_name'] ?></td>
                                        <!-- <td><?php echo $row['product_id'] ?></td> -->
                                        <td><?php echo $row['product_name'] ?></td>
                                        <!-- <td>Rp. <?php echo number_format($row['product_price']) ?></td> -->
                                        <!-- <td><?php echo $row['product_description'] ?></td> -->
                                        <td><a href="produk/<?php echo $row['product_image'] ?>"> <img src="produk/<?php echo $row['product_image'] ?>" width="50px"></a></td>
                                        <td><?php echo ($row['product_status'] == 0) ? 'Tidak AKtif' : 'Aktif' ?></td>
                                        <td><?php echo ($row['stock']) ?></td>
                                        <td>
                                            <style>
                                                #stocking {
                                                    text-decoration: none;
                                                }

                                                #stocking:hover {
                                                    background-color: black;
                                                    color: white;
                                                    margin: 4px 2px;
                                                    border-radius: 5px;
                                                }
                                            </style>
                                            <a id="stocking" href="edit-stocking-product.php?id=<?php echo $row['product_id'] ?>">Stock Produk</a>
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
        <!--------------------------------------------------------------------------------- SUPER ADMIN ---------------------------------------------------------------------------->
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
                <h3>Data Produk</h3>
                <div class="box1">
                    <button><a href="add-product.php" style="text-decoration: none ;">Tambah Data Produk</a></button><br><br>
                    <button><a href="stocking-product.php" style="text-decoration: none ;">Stocking Barang</a></button>
                    <button><a href="stocking-history.php" style="text-decoration: none ;">Riwayat Stocking</a></button>
                </div>
                <div class="box">
                    <p><a href="add-product.php">Tambah Data Produk</a></p><br>
                    <!-- <button><a href="add-product.php" style="text-decoration:none ;">Tambah Data</a></button> -->
                    <table border="1" cellspacing="0" class="table">
                        <thead>
                            <tr>
                                <th width="60px">No</th>
                                <th>Perwakilan</th>
                                <th>ID Kategori</th>
                                <th>Kategori</th>
                                <th>ID Produk</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <!-- <th>Deskripsi</th> -->
                                <th>Gambar</th>
                                <th>Status</th>
                                <th>Stock</th>
                                <th width="150px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $produk = mysqli_query($conn, "SELECT * FROM data_product LEFT JOIN data_category  USING (category_id) ORDER BY product_id DESC ");
                            if (mysqli_num_rows($produk) > 0) {
                                while ($row = mysqli_fetch_array($produk)) {
                                    $idperwakilan = $row['office_id'];
                                    $namaperwakilan = mysqli_query($conn, "SELECT * FROM data_office WHERE office_id = '" . $idperwakilan . "' ");
                                    $row_np = mysqli_fetch_array($namaperwakilan);
                            ?>
                                    <tr>
                                        <td><?php echo $no++ ?></td>
                                        <td><?php echo $row_np['office_name'] ?></td>
                                        <td><?php echo $row['category_id'] ?></td>
                                        <td><?php echo $row['category_name'] ?></td>
                                        <td><?php echo $row['product_id'] ?></td>
                                        <td><?php echo $row['product_name'] ?></td>
                                        <td>Rp. <?php echo number_format($row['product_price']) ?></td>
                                        <!-- <td><?php echo $row['product_description'] ?></td> -->
                                        <td><a href="produk/<?php echo $row['product_image'] ?>"> <img src="produk/<?php echo $row['product_image'] ?>" width="50px"></a></td>
                                        <td><?php echo ($row['product_status'] == 0) ? 'Tidak AKtif' : 'Aktif' ?></td>
                                        <td><?php echo ($row['stock']) ?></td>
                                        <td>
                                            <a href="edit-product.php?id=<?php echo $row['product_id'] ?>">Edit</a> || <a href="delete-data.php?idp=<?php echo $row['product_id'] ?>" onclick="return confirm('R U Sure about dat ?') ">Hapus</a>
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