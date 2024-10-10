<?php
session_start();
include '../config/config.php';

if (isset($_SESSION['nama'])) {
    // Jika pengguna sudah login, arahkan sesuai role
    if ($_SESSION['role'] == 'Admin') {
        header("Location: ../dashboardAdmin.php");
        exit();
    } else if ($_SESSION['role'] == 'siswa') {
        header("Location: ../dashboard.php");
        exit();
    }
}

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = hash('sha256', $_POST['password']); // Enkripsi password

    // Query untuk memeriksa username dan password
    $sql = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if ($result && $result->num_rows > 0) {
        $row = mysqli_fetch_assoc($result);

        $_SESSION['nama'] = $row['nama'];
        $_SESSION['role'] = $row['role']; 
        $_SESSION['id_user'] = $row['id'];  

        // Cek role dan arahkan ke halaman yang sesuai
        if ($row['role'] == 'Admin') {
            header("Location: ../dashboardAdmin.php");
        } else if ($row['role'] == 'siswa') {
            header("Location: ../dashboard.php");
        }
        exit();
    } else {
        echo "<script>alert('Login gagal, Coba Lagi') </script>";
    }
}
?>
