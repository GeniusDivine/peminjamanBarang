<?php
session_start();
include 'config/config.php';

// Periksa apakah admin sudah login
if (!isset($_SESSION['nama'])) {
    header("Location: controller/login.php");
    exit();
}

// Inisialisasi variabel
$foto_lama = '';
$barang = null;

if (isset($_GET['id_barang'])) {
    $id_barang = mysqli_real_escape_string($conn, $_GET['id_barang']);
    $sql = "SELECT * FROM barang_lab WHERE id_barang = '$id_barang'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $barang = mysqli_fetch_assoc($result);
        // Ambil nama foto lama dari data barang
        $foto_lama = $barang['foto'];
    } else {
        echo "<script>alert('Barang tidak ditemukan'); window.location='dashboardAdmin.php';</script>";
        exit();
    }
}

// Update barang setelah form disubmit
if (isset($_POST['update'])) {
    $nama_barang = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $stok = mysqli_real_escape_string($conn, $_POST['stok']);
    $kondisi = mysqli_real_escape_string($conn, $_POST['kondisi']);

    // Cek apakah ada file yang diupload
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto_baru = $_FILES['foto']['name'];
        $target_dir = "uploads/"; // Folder untuk menyimpan gambar
        $target_file = $target_dir . basename($foto_baru);

        // Pindahkan file ke folder tujuan
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
            // Jika berhasil, update query untuk menyimpan nama gambar baru
            $sql_update = "UPDATE barang_lab SET nama_barang='$nama_barang', stok='$stok', kondisi='$kondisi', foto='$foto_baru' WHERE id_barang='$id_barang'";
            
            // Jika ada gambar lama, hapus file gambar lama (opsional)
            if ($foto_lama && file_exists($target_dir . $foto_lama)) {
                unlink($target_dir . $foto_lama);
            }
        } else {
            echo "<script>alert('Gagal mengupload gambar');</script>";
        }
    } else {
        // Jika tidak ada gambar baru, gunakan foto lama
        $sql_update = "UPDATE barang_lab SET nama_barang='$nama_barang', stok='$stok', kondisi='$kondisi' WHERE id_barang='$id_barang'";
    }

    // Eksekusi query update
    if (mysqli_query($conn, $sql_update)) {
        echo "<script>alert('Barang berhasil diupdate'); window.location='TambahBarang.php';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate barang: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa; /* Warna latar belakang yang lebih cerah */
        }

        .container {
            margin-top: 30px; /* Margin atas untuk jarak dari tepi atas */
            background: white; /* Warna latar belakang putih untuk konten */
            padding: 20px; /* Padding dalam konten */
            border-radius: 50px; /* Sudut membulat */
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.9); /* Efek bayangan */
        }

        h3 {
            margin-bottom: 20px; /* Spasi bawah judul */
            font-weight: bold; /* Judul lebih tebal */
            text-align: center; /* Rata tengah */
        }

        .form-label {
            font-weight: bold; /* Label tebal */
        }

        .btn-primary {
            background-color: #007bff; /* Warna biru untuk tombol */
            border: none; /* Menghilangkan border default */
            border-radius: 5px; /* Sudut membulat tombol */
        }

        .btn-primary:hover {
            background-color: #0056b3; /* Warna tombol saat hover */
        }

        .form-text {
            font-style: italic; /* Gaya miring untuk teks kecil */
        }
    </style>
</head>
<body>
    
    <div class="container">
        <h3>Edit Barang</h3>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nama_barang" class="form-label">Nama Barang</label>
                <input type="text" class="form-control" id="nama_barang" name="nama_barang" value="<?php echo isset($barang['nama_barang']) ? $barang['nama_barang'] : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="stok" class="form-label">Stok Barang</label>
                <input type="number" class="form-control" id="stok" name="stok" value="<?php echo isset($barang['stok']) ? $barang['stok'] : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="kondisi" class="form-label">Kondisi Barang</label>
                <input type="text" class="form-control" id="kondisi" name="kondisi" value="<?php echo isset($barang['kondisi']) ? $barang['kondisi'] : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="foto" class="form-label">Foto Barang</label>
                <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                <small class="form-text text-muted">Biarkan kosong jika tidak mengubah foto.</small>
                <?php if ($foto_lama): ?>
                    <div class="mt-2">
                        <label>Foto Saat Ini:</label>
                        <img src="uploads/<?php echo $foto_lama; ?>" alt="Foto Barang" class="img-fluid" style="max-width: 150px; border: 1px solid #dee2e6; border-radius: 5px;">
                    </div>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary" name="update">Update Barang</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
