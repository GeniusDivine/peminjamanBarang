<?php
session_start();
include 'config/config.php';

if (!isset($_SESSION['nama'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id_barang'])) {
    $id_barang = $_GET['id_barang'];

    // Ambil detail barang
    $sql = "SELECT * FROM barang_lab WHERE id_barang = '$id_barang'";
    $result = mysqli_query($conn, $sql);
    $barang = mysqli_fetch_assoc($result);
    
    // Peminjaman barang
    if (isset($_POST['pinjam'])) {
        $id_user = $_SESSION['id_user'];
        $tanggal_pinjam = date('Y-m-d');
        
        // Kurangi stok barang
        $sql_update_stok = "UPDATE barang_lab SET stok = stok - 1 WHERE id_barang = '$id_barang'";
        mysqli_query($conn, $sql_update_stok);
        
        // Simpan transaksi peminjaman
        $sql_insert_transaksi = "INSERT INTO transaksi (id_barang, id_user, tanggal_pinjam, status) 
                                 VALUES ('$id_barang', '$id_user', '$tanggal_pinjam', 'dipinjam')";
        mysqli_query($conn, $sql_insert_transaksi);

        // Redirect ke halaman riwayat transaksi
        header("Location: barang_lab.php"); // Pastikan ini sesuai dengan file Anda
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: grey; /* Light background for better contrast */
        }
        .container {
            background-color: white; /* White background for the container */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.9); /* Soft shadow */
            padding: 30px; /* Padding for inner spacing */
            max-width: 600px; /* Maximum width for the container */
            margin: auto; /* Center the container */
            margin-top: 100px; /* Margin top for spacing */
        }
        h2 {
            font-weight: 600; /* Slightly bolder font */
            color: #007bff; /* Blue color for the heading */
            text-align: center; /* Center the heading */
        }
        .btn-success {
            background-color: #28a745; /* Green color for success */
            border: none; /* No border */
            border-radius: 50px; /* Fully rounded corners */
            padding: 10px 20px; /* Extra padding for a larger button */
            font-size: 16px; /* Larger font size */
            transition: background-color 0.3s; /* Smooth transition */
        }
        .btn-success:hover {
            background-color: #218838; /* Darker green on hover */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Pinjam Barang: <?php echo htmlspecialchars($barang['nama_barang']); ?></h2>
        <form method="post" class="text-center">
            <button type="submit" name="pinjam" class="btn btn-secondary">Pinjam Barang</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
