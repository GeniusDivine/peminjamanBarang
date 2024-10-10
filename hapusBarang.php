<?php
session_start();
include 'config/config.php';

// Ambil ID barang dari query string
$id_barang = $_GET['id_barang'];

// Hapus semua transaksi yang terkait dengan barang ini
$sql_hapus_transaksi = "DELETE FROM transaksi WHERE id_barang = $id_barang";
mysqli_query($conn, $sql_hapus_transaksi);

// Hapus barang dari tabel barang_lab
$sql_hapus_barang = "DELETE FROM barang_lab WHERE id_barang = $id_barang";
if (mysqli_query($conn, $sql_hapus_barang)) {
    echo "<script>alert('Barang berhasil dihapus'); window.location='TambahBarang.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus barang'); window.location='TambahBarang.php';</script>";
}
?>
