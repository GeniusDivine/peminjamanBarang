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
            // Insert data into the database, save only the file name
            $sql_insert = "INSERT INTO barang_lab (nama_barang, stok, kondisi, foto) VALUES ('$nama_barang', '$stok', '$kondisi', '$foto')"; // Simpan nama file

            if (mysqli_query($conn, $sql_insert)) {
                echo "<script>alert('Barang berhasil ditambahkan'); window.location='tambahBarang.php';</script>";
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
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background: url("") no-repeat fixed center;
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
        th, td {
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

    <div class="main-content">
        <div class="container mt-5">
        <h3 align='center' class="mt-5"><b>Daftar Barang Lab Komputer</b></h3>
            
            <!-- ///modals/// -->
            <div class="container mt-3">
            <button align='center' type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">Tambah Barang</button>
            </div>

            <!-- The Modal -->
            <div class="modal" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Barang Lab Computer</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                <form method="POST" action="" enctype="multipart/form-data" class="p-3 bg-light shadow-sm rounded" style="max-width: 500px; margin: auto;">
                <div class="mb-3">
                    <label for="nama_barang" class="form-label">Nama Barang</label>
                    <input type="text" class="form-control" id="nama_barang" name="nama_barang" required>
                </div>
                <div class="mb-3">
                    <label for="stok" class="form-label">Stok Barang</label>
                    <input type="number" class="form-control" id="stok" name="stok" required>
                </div>
                <div class="mb-3">
                    <label for="kondisi" class="form-label">Kondisi Barang</label>
                    <input type="text" class="form-control" id="kondisi" name="kondisi" required>
                </div>
                <div class="mb-3">
                    <label for="foto" class="form-label">Foto Barang</label>
                    <input type="file" class="form-control" id="foto" name="foto" accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-primary w-100" name="submit">Tambah Barang</button>
            </form>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>

                </div>
            </div>
            </div>

            <!-- daftar -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover mt-3">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Gambar</th>
                            <th>Nama Barang</th>
                            <th>Stok</th>
                            <th>Kondisi</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <?php 
                        $no = 1; 
                        while ($row = mysqli_fetch_assoc($result_barang)) { ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><img src="uploads/<?php echo htmlspecialchars($row['foto']); ?>" style="width: 100px;" alt="Gambar Barang"></td>
                            <td><?php echo htmlspecialchars($row['nama_barang']); ?></td>
                            <td><?php echo htmlspecialchars($row['stok']); ?></td>
                            <td><?php echo htmlspecialchars($row['kondisi']); ?></td>
                            <td>
                                <a href="editBarang.php?id_barang=<?php echo $row['id_barang']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="hapusBarang.php?id_barang=<?php echo $row['id_barang']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?');">Hapus</a>
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
