<?php
session_start();
include 'db.php';
//Kondisi Supaya User & Non User tidak dapat akses page ini
if ($_SESSION['role_login'] == 'user') {
    echo '<script>window.location="logout.php"</script>';
} else if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}

$piequery = "SELECT * FROM data_product";
$piequeryrecords = mysqli_query($conn, $piequery);
$kantor_admin = $_SESSION['a_global']->office_id;
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
    <!--------------------- jQuery ----------------------------->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <script src="https://www.google.com/jsapi"></script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css" />
    <!--------------------- Font Awesome ----------------------------->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!--------------------- Additional CSS ----------------------------->
    <style>
        #buttdetail {
            font-size: 17px;
            background-color: white;
            color: black;
            border-radius: 5px;
            padding: 2px;
        }

        #buttdetail a {
            text-decoration: none;
            font-weight: bold;
        }

        #buttdetail:hover {
            background-color: black;
            color: white;
            transition-duration: 0.3s;
        }
    </style>
</head>

<body>
    <!--------------------------------------------------------------------------------- ADMIN ---------------------------------------------------------------------------->
    <?php
    if ($_SESSION['role_login'] == 'admin') {
    ?>
        <header>
            <div class="container">
                <h1><a href="dashboard.php"><img style="width: 80px ; margin-bottom :-10px ;" src="img/logo-ombudsman2.png" alt=""> Gudang Ombudsman</a></h1>
                <ul style="margin-top: 20px ;">
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="profile.php">Profil</a></li>
                    <li><a href="product-data.php">Data Barang</a></li>
                    <li><a href="user-data.php">Data User</a></li>
                    <li><a href="order-table.php">Pesanan (<?php echo $_SESSION['jumlah_pesanan']; ?>)</a></li>
                    <li><a href="logout.php">Keluar</a></li>
                </ul>
            </div>
        </header>

        <!--------------------------------------------------------------------------------- SUPER ADMIN ---------------------------------------------------------------------------->
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
                <h3>List Barang yang Perlu Restock</h3>
                <br>
                <table border="1" cellspacing="0" class="table">
                    <thead>
                        <tr>
                            <th width="25%">Perwakilan</th>
                            <th width="12%">ID Barang</th>
                            <th>Kategori</th>
                            <th>Nama Barang</th>
                            <th>Stock</th>
                            <th>Batas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $leftjoin_office = "LEFT JOIN data_office USING (office_id)";
                        $stock_query = mysqli_query($conn, "SELECT * FROM data_product LEFT JOIN data_category USING (category_id) LEFT JOIN data_unit USING (unit_id) $leftjoin_office WHERE stock_point >= stock ");
                        if (mysqli_num_rows($stock_query)) {
                            while ($fetch_stock = mysqli_fetch_array($stock_query)) {
                        ?>
                                <tr>
                                    <td><?php echo $fetch_stock['office_name'] ?></td>
                                    <td><?php echo $fetch_stock['product_id'] ?></td>
                                    <td><?php echo $fetch_stock['category_name'] ?></td>
                                    <td><?php echo $fetch_stock['product_name'] ?></td>
                                    <td><?php echo $fetch_stock['stock'] ?></td>
                                    <td><?php echo $fetch_stock['stock_point'] ?></td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php
    }
    ?>
</body>

</html>