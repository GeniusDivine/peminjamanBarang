<?php
session_start();
include 'config/config.php';

if (!isset($_SESSION['nama'])) {
    header("Location: controller/login.php");
    exit();
}

if (isset($_GET['id_transaksi'])) {
    $id_transaksi = $_GET['id_transaksi'];

    // Ambil detail transaksi
    $sql = "SELECT * FROM transaksi WHERE id_transaksi = '$id_transaksi'";
    $result = mysqli_query($conn, $sql);
    $transaksi = mysqli_fetch_assoc($result);

    // Pengembalian barang
    if (isset($_POST['kembali'])) {
        $tanggal_kembali = date('Y-m-d');

        // Perbarui status transaksi
        $sql_update_transaksi = "UPDATE transaksi SET tanggal_kembali = '$tanggal_kembali', status = 'dikembalikan' 
                                 WHERE id_transaksi = '$id_transaksi'";
        mysqli_query($conn, $sql_update_transaksi);
        
        // Tambah stok barang
        $id_barang = $transaksi['id_barang'];
        $sql_update_stok = "UPDATE barang_lab SET stok = stok + 1 WHERE id_barang = '$id_barang'";
        mysqli_query($conn, $sql_update_stok);

        header("Location: dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengembalian Barang</title>
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Kembalikan Barang</h2>
        <form method="post">
            <button type="submit" name="kembali" class="btn btn-success">Kembalikan Barang</button>
        </form>
    </div>

    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
