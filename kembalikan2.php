<?php
session_start();
include 'config/config.php';

if (isset($_GET['id_transaksi']) && isset($_GET['id_barang'])) {
    $id_transaksi = $_GET['id_transaksi'];
    $id_barang = $_GET['id_barang'];

    // Update status transaksi menjadi 'dikembalikan' dan tambahkan tanggal kembali
    $sql_update_transaksi = "UPDATE transaksi SET status='dikembalikan', tanggal_kembali=NOW() WHERE id_transaksi='$id_transaksi'";
    $result_update_transaksi = mysqli_query($conn, $sql_update_transaksi);

    // Tambah stok barang yang dikembalikan
    $sql_update_barang = "UPDATE barang_lab SET stok = stok + 1 WHERE id_barang='$id_barang'";
    $result_update_barang = mysqli_query($conn, $sql_update_barang);

    if ($result_update_transaksi && $result_update_barang) {
        echo "<script>alert('Barang berhasil dikembalikan'); window.location='dashboardAdmin.php';</script>";
    } else {
        echo "<script>alert('Gagal mengembalikan barang'); window.location='dashboardAdmin.php';</script>";
    }
} else {
    echo "<script>alert('Data transaksi atau barang tidak valid'); window.location='dashboardAdmin.php';</script>";
}
?>
