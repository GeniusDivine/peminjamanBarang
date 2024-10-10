<?php
session_start();

// Hapus semua session
$_SESSION = [];

// Hapus cookie session jika ada
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"], $params["secure"], $params["httponly"]
    );
}

// Hancurkan session
session_destroy();

// Pastikan tidak ada pengiriman header tambahan
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

// Arahkan ke halaman login
header("Location: ../index1.php");
exit();
?>
