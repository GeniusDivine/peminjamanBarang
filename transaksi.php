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

        .table-responsive {
            max-height: 400px; /* Tentukan tinggi maksimum untuk scroll */
            overflow-y: auto; /* Mengaktifkan scroll vertikal */
        }
    </style>
</head>
<body>
    
    <!-- Sidebar -->
    <div class="sidebar">
        <h4 align="center" class="text-white">PinjamAja</h4>
        <a href="dashboard.php">Dashboard</a>
        <a href="barang_lab.php">Daftar Barang</a>
        <a href="transaksi.php">Riwayat Peminjam</a>
        <a href="controller/logout.php" class="btn btn-danger w-100 mt-3">Logout</a>
    </div>

    <!-- Content -->
    <div class="container">
        <div class="content">

            <!-- Riwayat Transaksi -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h5 align='center' class="m-0 font-weight-bold text-primary">Riwayat Peminjam</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Barang</th>
                                    <th>Nama Peminjam</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Tanggal Kembali</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $counter_transaksi = 1; 
                                while ($row = mysqli_fetch_assoc($result_transaksi)) { ?>
                                    <tr>
                                        <td><?php echo $counter_transaksi++; ?></td>
                                        <td><?php echo htmlspecialchars($row['nama_barang']); ?></td>
                                        <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                        <td><?php echo htmlspecialchars($row['tanggal_pinjam']); ?></td>
                                        <td><?php echo $row['tanggal_kembali'] ? htmlspecialchars($row['tanggal_kembali']) : 'Belum Dikembalikan'; ?></td>
                                        <td><?php echo ucfirst(htmlspecialchars($row['status'])); ?></td>
                                        <td>
                                            <?php if ($row['status'] == 'dipinjam') { ?>
                                                <a class="btn btn-danger">Belum Kembali</a>
                                            <?php } else { ?>
                                                <span class="btn bg-success">Kembali</span>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
