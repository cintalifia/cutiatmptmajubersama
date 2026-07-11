<?php
session_start();
require '../function/koneksi.php';

if (!isset($_SESSION['username'])) {
    exit('Akses ditolak');
}

if (isset($_POST['hapus'])) {
    $id = mysqli_real_escape_string($connect, $_POST['hapus']);
    mysqli_query($connect, "DELETE FROM pengajuancuti WHERE idpengajuancuti='$id'");
    echo 'success';
    exit;
}

if (isset($_POST['id'], $_POST['status'])) {
    $id = mysqli_real_escape_string($connect, $_POST['id']);
    $status = mysqli_real_escape_string($connect, $_POST['status']);
    mysqli_query($connect, "UPDATE pengajuancuti SET status='$status' WHERE idpengajuancuti='$id'");
    echo 'success';
    exit;
}

echo 'gagal';
?>
