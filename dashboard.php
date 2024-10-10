<?php
session_start(); // Memulai sesi sebelum menggunakan $_SESSION

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['nama'])) {
    header("Location: controller/login.php");
    exit();
}

// Ambil daftar barang dengan urutan berdasarkan ID Barang
include 'config/config.php';
$sql_barang = "SELECT * FROM barang_lab ORDER BY id_barang ASC";
$result_barang = mysqli_query($conn, $sql_barang);

// Hitung jumlah barang
$sql_jumlah_barang = "SELECT COUNT(*) AS jumlah_barang FROM barang_lab";
$result_jumlah_barang = mysqli_query($conn, $sql_jumlah_barang);
$row_jumlah_barang = mysqli_fetch_assoc($result_jumlah_barang);
$jumlah_barang = $row_jumlah_barang['jumlah_barang'];

// Ambil riwayat transaksi dengan urutan berdasarkan ID Transaksi
$sql_transaksi = "SELECT barang_lab.nama_barang, user.nama, 
                         transaksi.tanggal_pinjam, transaksi.tanggal_kembali, transaksi.status,
                         transaksi.id_barang
                  FROM transaksi 
                  INNER JOIN barang_lab ON transaksi.id_barang = barang_lab.id_barang 
                  INNER JOIN user ON transaksi.id_user = user.id
                  ORDER BY transaksi.id_transaksi ASC";
$result_transaksi = mysqli_query($conn, $sql_transaksi);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Peminjaman</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #adb5bd;
            display: flex;
            align-items: center; /* Menyatukan secara vertikal */
            justify-content: center; /* Menyatukan secara horizontal */
            min-height: 100vh;
            margin: 0;
        }

        .container {
            width: 100%;
            max-width: 1200px; /* Batas lebar maksimum */
            margin: auto;
        }

        .sidebar {
            width: 250px;
            background-color: #343a40;
            min-height: 100vh;
            padding: 15px;
            position: fixed; /* Agar sidebar tetap di kiri */
            left: 0;
            top: 0;
            bottom: 0;
        }

        .sidebar a {
            color: #ffffff;
            text-decoration: none;
            display: block;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 5px;
        }

        .sidebar a:hover {
            background-color: #495057;
        }

        .content {
            margin-left: 260px; /* Menjaga jarak dari sidebar */
            padding: 20px;
        }

        .table thead {
            background-color: #343a40;
            color: #fff;
        }

        .table img {
            display: block;
            max-width: 80px;
            height: auto;
            margin: 0 auto;
        }

        .card {
            margin-top: 20px;
        }

        .text-center-custom {
            text-align: center;
        }
    </style>
</head>
<body>
    
    <!-- Sidebar -->
    <div class="sidebar">
        <h4 align="center" class="text-white">PinjamAja</h4>
        <a href="dashboard.php">Dashboard</a>
        <a href="barang_lab.php">Daftar Barang</a>
        <a href="transaksi.php">Riwayat peminjam</a>
        <a href="controller/logout.php" class="btn btn-danger w-100 mt-3">Logout</a>
    </div>

    <!-- Content -->
    <div class="container">
        <div class="content">
            <div class="d-flex flex-column align-items-center mb-4">
                <h2 class="text-center">Selamat Datang, <?php echo htmlspecialchars($_SESSION['nama']); ?></h2>
                <div class="card shadow mb-4 mt-3">
                    <div class="card-body">
                        <h5 class="m-0 font-weight-bold text-primary text-center">Jumlah Barang Tersedia: <?php echo $jumlah_barang; ?></h5>
                    </div>
                </div>
            </div>

            
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
