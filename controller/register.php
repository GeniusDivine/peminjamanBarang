<?php
session_start();
include '../config/config.php';

if (isset($_SESSION['nama'])) {
    header("Location: ../index1.php");
    exit();
}

if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $role = $_POST['role'];

 
    if ($password === $cpassword) {
        $passwordHash = hash('sha256', $password);
        
        
        $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $stmt = $conn->prepare("INSERT INTO user (nama, username, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nama, $username, $passwordHash, $role);
            if ($stmt->execute()) {
                echo "<script>alert('Registrasi Berhasil!')</script>";
                
                $nama = "";
                $_POST['password'] = "";
                $_POST['cpassword'] = "";
            } else {
                echo "<script>alert('Terjadi Kesalahan.')</script>";
            }
        } else {
            echo "<script>alert('User sudah Terdaftar.')</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Password Tidak Sesuai.')</script>";
    }
}
session_start(); 
session_unset(); 
session_destroy();


header("Location: ../index1.php");
exit();
$conn->close();
?>
