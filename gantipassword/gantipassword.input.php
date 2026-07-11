<?php
session_start();
require '../function/koneksi.php';

if (!isset($_SESSION['username'])) {
    exit('Akses ditolak');
}

$password = mysqli_real_escape_string($connect, $_POST['passwordbaru'] ?? $_POST['password_baru'] ?? '');
$username = mysqli_real_escape_string($connect, $_SESSION['username']);

if ($password === '') {
    exit('Password tidak boleh kosong');
}

$ok = mysqli_query($connect, "UPDATE userlogin SET password='$password' WHERE username='$username'");
echo $ok ? 'success' : mysqli_error($connect);
?>
