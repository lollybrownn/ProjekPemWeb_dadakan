<?php

$hostname = "localhost";
$port = 3306;
$username = "root";
$password = "";
$db = "nba";

try {
    $conn = new mysqli($hostname, $username, $password, $db);
} catch (Exception $e) {
    echo "Koneksi Gagal";
}

?>