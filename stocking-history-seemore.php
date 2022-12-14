<?php
session_start();
include 'db.php';

//Kondisi Supaya User & Non User tidak dapat akses page ini
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
    <title>Gudang Ombudsman</title>
    <!--------------------- CSS ------------------------------------->
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <!--------------------- Font Used ----------------------------->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
    <!--------------------- Additional CSS ----------------------------->
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

        .pagination {
            display: inline-block;
        }

        .pagination a {
            font-weight: bold;
            font-size: 18px;
            color: black;
            float: left;
            padding: 8px 16px;
            text-decoration: none;
            border: 1px solid black;
        }

        .pagination a.active {
            background-color: pink;
        }

        .pagination a:hover:not(.active) {
            background-color: skyblue;
        }
    </style>

</head>

<body>
    <?php
    if ($_SESSION['role_login'] == 'admin') {
        $per_page_record = 12;
        if (isset($_GET["page"])) {
            $page  = $_GET["page"];
        } else {
            $page = 1;
        }

        $start_from = ($page - 1) * $per_page_record;
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
                    $keranjang = mysqli_query($conn, "SELECT * FROM data_transaction WHERE office_id = '" . $idkantoradmin . "' AND status = 'Diproses Admin' ORDER BY cart_id");
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
                <h2>Riwayat Stocking Barang</h2>
                <div class="box1">
                    <button><a href="product-data.php" style="text-decoration: none ;">Kembali</a></button><br><br>
                </div>
                <br>
                <div class="box">
                    <table border="1" cellspacing="0" class="table">
                        <thead>
                            <tr>
                                <th>Kategori</th>
                                <th>Nama Produk</th>
                                <th>Stok Sebelum</th>
                                <th>Stok Setelah</th>
                                <th>Jumlah Stocking</th>
                                <th>Waktu Stocking</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $history = mysqli_query($conn, "SELECT * FROM stocking_item WHERE office_id = '" . $idkantoradmin . "' ORDER BY modified DESC LIMIT $start_from, $per_page_record");
                            $produk = mysqli_query($conn, "SELECT * FROM data_product LEFT JOIN data_category  USING (category_id) WHERE office_id = '" . $idkantoradmin . "' ORDER BY product_id DESC ");
                            while ($row = mysqli_fetch_array($history)) {
                                $idperwakilan = $row['office_id'];
                                $namaperwakilan = mysqli_query($conn, "SELECT * FROM data_office WHERE office_id = '" . $idperwakilan . "' ");
                                $row_np = mysqli_fetch_array($namaperwakilan);
                            ?>
                                <tr>
                                    <td><?php echo $row['category_name'] ?></td>
                                    <td><?php echo $row['product_name'] ?></td>
                                    <td><?php echo ($row['stocking_before']) ?></td>
                                    <td><?php echo ($row['stocking_after']) ?></td>
                                    <td><?php echo ($row['quantity']) ?></td>
                                    <td><?php echo ($row['modified']) ?></td>
                                </tr>
                            <?php }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <center>
            <div class="pagination">
                <?php
                $query = "SELECT COUNT(*) FROM stocking_item WHERE office_id = '" . $idkantoradmin . "' ";
                $rs_result = mysqli_query($conn, $query);
                $row = mysqli_fetch_row($rs_result);
                $total_records = $row[0];

                echo "</br>";
                $total_pages = ceil($total_records / $per_page_record);
                $pagLink = "";

                if ($page >= 2) {
                    echo "<a href='stocking-history-seemore.php?page=" . ($page - 1) . "'>  Prev </a>";
                }

                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($i == $page) {
                        $pagLink .= "<a class = 'active' href='stocking-history-seemore.php?page="
                            . $i . "'>" . $i . " </a>";
                    } else {
                        $pagLink .= "<a href='stocking-history-seemore.php?page=" . $i . "'>   
                                        " . $i . " </a>";
                    }
                };
                echo $pagLink;

                if ($page < $total_pages) {
                    echo "<a href='stocking-history-seemore.php?page=" . ($page + 1) . "'>  Next </a>";
                }
                ?>
            </div><br><br><br><br>

        </center>

        <script>
            function go2Page() {
                var page = document.getElementById("page").value;
                page = ((page > <?php echo $total_pages; ?>) ? <?php echo $total_pages; ?> : ((page < 1) ? 1 : page));
                window.location.href = 'stocking-history-seemore.php?page=' + page;
            }
        </script>
    <?php
    } else if ($_SESSION['role_login'] == 'super') {
    ?>
    <?php
    }
    ?>

</body>

</html>