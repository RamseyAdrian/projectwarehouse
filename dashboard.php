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
    <!--------------------- Additional CSS ----------------------------->
    <style>
        .pie-chart {
            width: 700px;
            height: 500px;
            margin: 0 auto;
            border: 1px solid black;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <!--------------------------------------------------------------------------------- ADMIN ---------------------------------------------------------------------------->
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
                <h2>Dashboard</h2>
                <?php
                $idperwakilan = $_SESSION['a_global']->office_id;
                $namaperwakilan = mysqli_query($conn, "SELECT * FROM data_office WHERE office_id = '" . $idperwakilan . "' ");
                $row_np = mysqli_fetch_array($namaperwakilan);
                ?>
                <div class="box">
                    <h2>Admin <?php echo $row_np['office_name'] ?> </h2>
                    <h4><?php echo $_SESSION['a_global']->admin_name ?></h4>
                    <h4>Admin ID : <?php echo $_SESSION['a_global']->admin_id ?></h4>
                </div>
                <h2>Table & Chart</h2>
                <div class="box">
                    <div class="chart">
                        <!-- <div class="pie-chart" id="chartDiv"></div>
                        <div class="text-center"></div>
                        <script type="text/javascript">
                            window.onload = function() {
                                google.load("visualization", "1.1", {
                                    packages: ["corechart"],
                                    callback: 'drawChart'
                                });
                            };

                            function drawChart() {
                                var data = google.visualization.arrayToDataTable([
                                    ['Country', 'Popularity'],
                                    <?php
                                    while ($row = mysqli_fetch_assoc($piequeryrecords)) {
                                        echo "['" . $row['product_name'] . "', " . $row['stock'] . "],";
                                    }
                                    ?>
                                ]);

                                var options = {
                                    pieHole: 0.4,
                                    title: 'Pie Chart Data Produk',
                                };

                                var chart = new google.visualization.PieChart(document.getElementById('chartDiv'));
                                chart.draw(data, options);
                            }
                        </script> -->
                    </div>

                </div>
            </div>
        </div>
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
                <h3>Dashboard</h3>
                <?php
                $idperwakilan = $_SESSION['a_global']->office_id;
                $namaperwakilan = mysqli_query($conn, "SELECT * FROM data_office WHERE office_id = '" . $idperwakilan . "' ");
                $row_np = mysqli_fetch_array($namaperwakilan);
                ?>
                <div class="box">
                    <h2>Super Admin Ombudsman RI</h2>
                    <h4><?php echo $_SESSION['a_global']->super_name ?></h4>
                    <!-- <h4>Super Admin ID : <?php echo $_SESSION['a_global']->super_admin_id ?></h4> -->
                </div>

                <!-- <h2>Table & Chart</h2>
                <div class="box">
                    <div class="pie-chart" id="chartDiv"></div>
                    <div class="text-center"></div>
                    <script type="text/javascript">
                        window.onload = function() {
                            google.load("visualization", "1.1", {
                                packages: ["corechart"],
                                callback: 'drawChart'
                            });
                        };

                        function drawChart() {
                            var data = google.visualization.arrayToDataTable([
                                ['Country', 'Popularity'],
                                <?php
                                while ($row = mysqli_fetch_assoc($piequeryrecords)) {
                                    echo "['" . $row['product_name'] . "', " . $row['stock'] . "],";
                                }
                                ?>
                            ]);

                            var options = {
                                pieHole: 0.4,
                                title: 'Pie Chart Data Produk',
                            };

                            var chart = new google.visualization.PieChart(document.getElementById('chartDiv'));
                            chart.draw(data, options);
                        }
                    </script>
                </div> -->
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

</body>

</html>