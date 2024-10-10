<?php
session_start();
include 'config/config.php';

// Periksa apakah pengguna sudah login sebagai admin
if (!isset($_SESSION['nama'])) {
    header("Location: controller/login.php");
    exit();
}

// Ambil daftar barang
$sql_barang = "SELECT * FROM barang_lab";
$result_barang = mysqli_query($conn, $sql_barang);

// Ambil riwayat transaksi
$sql_transaksi = "SELECT transaksi.id_transaksi, barang_lab.id_barang, barang_lab.nama_barang, user.nama, transaksi.tanggal_pinjam, transaksi.tanggal_kembali, transaksi.status FROM transaksi INNER JOIN barang_lab ON transaksi.id_barang = barang_lab.id_barang INNER JOIN user ON transaksi.id_user = user.id";
$result_transaksi = mysqli_query($conn, $sql_transaksi);

// Hitung total peminjaman
$sql_total_peminjaman = "SELECT COUNT(*) AS total_peminjaman FROM transaksi";
$result_total_peminjaman = mysqli_query($conn, $sql_total_peminjaman);
$total_peminjaman = mysqli_fetch_assoc($result_total_peminjaman)['total_peminjaman'];

// Hitung total barang yang dikembalikan
$sql_total_dikembalikan = "SELECT COUNT(*) AS total_dikembalikan FROM transaksi WHERE status = 'dikembalikan'";
$result_total_dikembalikan = mysqli_query($conn, $sql_total_dikembalikan);
$total_dikembalikan = mysqli_fetch_assoc($result_total_dikembalikan)['total_dikembalikan'];

// Hitung total barang yang masih dipinjam
$sql_total_dipinjam = "SELECT COUNT(*) AS total_dipinjam FROM transaksi WHERE status = 'dipinjam'";
$result_total_dipinjam = mysqli_query($conn, $sql_total_dipinjam);
$total_dipinjam = mysqli_fetch_assoc($result_total_dipinjam)['total_dipinjam'];

// Tambah barang baru
if (isset($_POST['submit'])) {
    $nama_barang = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $stok = mysqli_real_escape_string($conn, $_POST['stok']);
    $kondisi = mysqli_real_escape_string($conn, $_POST['kondisi']);

    // Handle the file upload
    $foto = $_FILES['foto']['name'];
    $target_dir = "uploads/"; // Ensure this directory exists
    $target_file = $target_dir . basename($foto);
    $uploadOk = 1;

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES['foto']['tmp_name']);
    if ($check === false) {
        echo "<script>alert('File is not an image.');</script>";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES['foto']['size'] > 500000) { // Limit to 500KB
        echo "<script>alert('Sorry, your file is too large.');</script>";
        $uploadOk = 0;
    }

    // Allow certain file formats
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');</script>";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "<script>alert('Sorry, your file was not uploaded.');</script>";
    } else {
        // Try to upload file
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
            // Insert data into the database
            $sql_insert = "INSERT INTO barang_lab (nama_barang, stok, kondisi, foto) VALUES ('$nama_barang', '$stok', '$kondisi', '$target_file')";

            if (mysqli_query($conn, $sql_insert)) {
                echo "<script>alert('Barang berhasil ditambahkan'); window.location='riwayatPeminjaman.php';</script>";
            } else {
                echo "<script>alert('Terjadi kesalahan saat menambahkan barang');</script>";
            }
        } else {
            echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <!-- Menghubungkan dengan file CSS -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url("") no-repeat fixed center;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #343a40; /* Dark background */
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
            background-color: #007bff; /* Blue on hover */
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

        /* Main content */
        .main-content {
            margin-left: 250px; /* Move content to the right to make space for sidebar */
            padding: 20px;
            width: calc(100% - 250px);
            transition: all 0.3s ease;
        }

        /* Responsiveness for smaller screens */
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

        /* Card Styling */
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Table Styling */
        .table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #e9f2ff; /* Light blue background */
        }

        th,
        td {
            vertical-align: middle;
        }

        /* Input Styling */
        .form-control {
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        /* Welcome Text */
        .welcome-text {
            color: #007bff;
            text-align: center;
        }

        /* Custom styling for dikembalikan */
        .status-dikembalikan {
            background-color: #28a745; /* Green background */
            color: white;
            padding: 5px;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="logo">PinjamAja</div>
        <a href="dashboardAdmin.php">Dashboard</a>
        <a href="tambahBarang.php">Tambah Barang</a>
        <a href="rekapPeminjam.php">Rekap Peminjam</a>
        <a href="riwayatPeminjaman.php">Riwayat Peminjaman</a>
        <a class="btn btn-danger btn-logout" href="controller/logout.php">Logout</a>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container mt-5">

            <!-- Riwayat Transaksi -->
            <h3 align='center' class="mt-5"><b>Riwayat Peminjam</b></h3>
            <div class="table-responsive">
                <table class="table table-bordered table-hover mt-3">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
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
                        $no_transaksi = 1; 
                        while ($row = mysqli_fetch_assoc($result_transaksi)) { ?>
                        <tr>
                            <td><?php echo $no_transaksi++; ?></td>
                            <td><?php echo $row['nama_barang']; ?></td>
                            <td><?php echo $row['nama']; ?></td>
                            <td><?php echo $row['tanggal_pinjam']; ?></td>
                            <td><?php echo $row['tanggal_kembali'] ? $row['tanggal_kembali'] : 'Belum Dikembalikan'; ?></td>
                            <td><?php echo ucfirst($row['status']); ?></td>
                            <td>
                                <?php if ($row['status'] == 'dipinjam') { ?>
                                <a href="kembalikan2.php?id_transaksi=<?php echo $row['id_transaksi']; ?>&id_barang=<?php echo $row['id_barang']; ?>" class="btn btn-danger">Kembalikan</a>
                                <?php } else { ?>
                                <span class="status-dikembalikan">Kembali</span>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
