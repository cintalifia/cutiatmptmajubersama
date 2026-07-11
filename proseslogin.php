<?php
session_start();
require 'function/koneksi.php';

$username = mysqli_real_escape_string($connect, trim($_POST['username'] ?? ''));
$password = mysqli_real_escape_string($connect, trim($_POST['password'] ?? ''));

$query = mysqli_query(
    $connect,
    "SELECT
        userlogin.username,
        karyawan.nik,
        karyawan.nama,
        karyawan.divisi,
        karyawan.level
     FROM userlogin
     LEFT JOIN karyawan ON userlogin.username = karyawan.nik
     WHERE userlogin.username='$username'
     AND userlogin.password='$password'
     LIMIT 1"
);

if ($query && mysqli_num_rows($query) > 0) {
    $data = mysqli_fetch_assoc($query);

    $_SESSION['username'] = $data['username'];
    $_SESSION['status']   = 'login';
    $_SESSION['nama']     = $data['nama'] ?: ($username === 'admin' ? 'Administrator' : $username);
    $_SESSION['level']    = $data['level'] ?: ($username === 'admin' ? 'Administrator' : 'User');
    $_SESSION['divisi']   = $data['divisi'] ?: ($username === 'admin' ? 'HRD' : '-');

    header('Location: dashboard/index.php');
    exit;
}

echo "
<script>
    alert('Username atau Password salah');
    window.location='index.php';
</script>
";
?>
