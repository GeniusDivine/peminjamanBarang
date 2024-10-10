<?php
session_start();
include 'config/config.php';

// Periksa apakah pengguna sudah login sebagai admin
if (!isset($_SESSION['nama'])){
    header("Location: controller/login.php");
    exit();
}

// Filter data transaksi berdasarkan periode tanggal
$filter_sql = '';
$periode = ''; // Inisialisasi periode
$result_transaksi = null; // Inisialisasi hasil transaksi

if (isset($_POST['filter'])) {
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);

    // Validasi tanggal
    if (!empty($start_date) && !empty($end_date)) {
        $filter_sql = " WHERE tanggal_pinjam BETWEEN '$start_date' AND '$end_date'";
        $periode = "Periode: $start_date hingga $end_date";

        // Tampilkan transaksi sesuai dengan filter
        $sql_transaksi = "SELECT transaksi.id_transaksi, barang_lab.id_barang, barang_lab.nama_barang, user.nama, transaksi.tanggal_pinjam, transaksi.tanggal_kembali 
                          FROM transaksi 
                          INNER JOIN barang_lab ON transaksi.id_barang = barang_lab.id_barang 
                          INNER JOIN user ON transaksi.id_user = user.id" . $filter_sql;
        $result_transaksi = mysqli_query($conn, $sql_transaksi);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #343a40;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            margin: 10px 0;
            font-size: 18px;
            text-align: center;
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .sidebar a:hover {
            background-color: #007bff;
        }
        .sidebar .btn-logout {
            margin-top: auto;
        }
        .sidebar .logo {
            font-size: 24px;
            font-weight: bold;
            color: white;
            margin-bottom: 40px;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
            transition: all 0.3s ease;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                padding: 10px;
            }
            .sidebar a {
                font-size: 16px;
                padding: 8px;
            }
            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #e9f2ff;
        }
        th,
        td {
            vertical-align: middle;
        }
        .form-control {
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .welcome-text {
            color: #007bff;
            text-align: center;
        }
    </style>
</head>

<body>
    <nav class="sidebar">
        <div class="logo">PinjamAja</div>
        <a href="dashboardAdmin.php">Dashboard</a>
        <a href="tambahBarang.php">Tambah Barang</a>
        <a href="rekapPeminjam.php">Rekap Peminjam</a>
        <a href="riwayatPeminjaman.php">Riwayat Peminjaman</a>
        <a class="btn btn-danger btn-logout" href="controller/logout.php">Logout</a>
    </nav>

    <div class="container-fluid main-content">
        <div class="container">
            <h3 align="center"><b>Rekap Data Peminjaman Berdasarkan Tanggal</b></h3>
            <form action="" method="POST" class="row g-3 mt-4">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Dari Tanggal</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">Hingga Tanggal</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" required>
                </div>
                <div class="col-md-4 align-self-end">
                    <button type="submit" name="filter" class="btn btn-primary">Cari</button>
                </div>
            </form>

            <?php if (!empty($periode)) { ?>
            <h4 class="mt-3 text-center"><?php echo $periode; ?></h4>
            <?php } ?>
        </div>

        <div class="container mt-5">
            <?php if ($result_transaksi && mysqli_num_rows($result_transaksi) > 0) { ?>
                <h3 align="center"><b>Rekapan Peminjaman</b></h3>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mt-3">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Nama Peminjam</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Kembali</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no_transaksi = 1; 
                            while ($row = mysqli_fetch_assoc($result_transaksi)) { ?>
                            <tr>
                                <td><?php echo $no_transaksi++; ?></td>
                                <td><?php echo htmlspecialchars($row['nama_barang']); ?></td>
                                <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                <td><?php echo htmlspecialchars($row['tanggal_pinjam']); ?></td>
                                <td><?php echo $row['tanggal_kembali'] ? htmlspecialchars($row['tanggal_kembali']) : 'Belum Dikembalikan'; ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } else if (isset($_POST['filter'])) { ?>
                <h4 class="mt-3 text-center text-danger">Tidak ada data peminjaman untuk tanggal yang dipilih.</h4>
            <?php } ?>
        </div>
    </div>

    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
