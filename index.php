<?php
include 'home.php';
include 'config.php';

// Cek apakah pengguna sudah login
if (isset($_SESSION['username'])) {

} else {
    // Jika belum login, arahkan ke halaman login
    header("Location: login.php");
    exit();
}

?>
