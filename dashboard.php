<?php
session_start();
include 'db.php';
if ($_SESSION['role_login'] == 'user') {

    echo '<script>window.location="logout.php"</script>';
} else if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}

$piequery = "SELECT * FROM data_product";
$piequeryrecords = mysqli_query($conn, $piequery);


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
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <script src="https://www.google.com/jsapi"></script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css" />
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
                <h3>Dashboard</h3>
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
                    <li><a href="dashboard.php">Dashboard </a></li>
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

        <!-- Footer -->
        <footer>
            <div class="container">
                <small>Copyright &copy; 2022 - Universitas Pertamina</small>
            </div>
        </footer>
    <?php
    }
    ?>


</body>

</html>