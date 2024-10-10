<?php
$server = "localhost";
$user = "root";
$password = "";
$database = "peminjamanbarang1";
$conn = mysqli_connect($server, $user, $password, $database);
if (!$conn){
    die("Koneksi Database Gagal : " . mysqli_connect_error());
}
?>