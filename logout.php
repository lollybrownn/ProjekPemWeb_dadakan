<?php
include"connection.php";

// Hapus semua session variables
$_SESSION = array();

// Hapus session cookie jika ada
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}

// Destroy session
session_destroy();

// Hapus cookie "ingat saya" jika ada
if (isset($_COOKIE['ingat'])) {
    setcookie('ingat', '', time()-3600, '/');
}

// Redirect ke halaman login dengan pesan
header("Location: login.php?logout=success");
exit();
?>