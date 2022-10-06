<?php

$host = "localhost"; /* Host name */
$user = "root"; /* User */
$password = ""; /* Password */
$dbname = "chart"; /* Database name */

$con = mysqli_connect($host, $user, $password, $dbname);
// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$chartQuery = "SELECT * FROM donut";
$chartQueryRecords = mysqli_query($con, $chartQuery);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <script src="https://www.google.com/jsapi"></script>
    <style>
        .pie-chart {
            width: 700px;
            height: 500px;
            margin: 0 auto;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>

    <h2 class="text-center">Generate Donut Chart in PHP</h2>

    <div id="chartDiv" class="pie-chart"></div>

    <div class="text-center">

    </div>

    ?>
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
                while ($row = mysqli_fetch_assoc($chartQueryRecords)) {
                    echo "['" . $row['Name'] . "', " . $row['Number'] . "],";
                }
                ?>
            ]);

            var options = {
                pieHole: 0.4,
                title: 'Popularity of Types of Framework',
            };

            var chart = new google.visualization.PieChart(document.getElementById('chartDiv'));
            chart.draw(data, options);
        }
    </script>

</body>

</html>