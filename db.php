<?php
$hostname = 'localhost';
$username = 'root';
$password = '';
$dbname = 'datamaster_warehouse';

$conn = mysqli_connect($hostname, $username, $password, $dbname) or die('Gagal terhubung dengan database');
