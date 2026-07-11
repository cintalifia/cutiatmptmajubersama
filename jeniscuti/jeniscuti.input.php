<?php
session_start();
require '../function/koneksi.php';

if (!isset($_SESSION['username'])) {
    exit('Akses ditolak');
}

if (isset($_POST['hapus'])) {
    $id = mysqli_real_escape_string($connect, $_POST['hapus']);
    mysqli_query($connect, "DELETE FROM jeniscuti WHERE idcuti='$id'");
    echo 'success';
    exit;
}

$idLama = mysqli_real_escape_string($connect, $_POST['id'] ?? '');
$idcuti = mysqli_real_escape_string($connect, trim($_POST['idcuti'] ?? ''));
$nama   = mysqli_real_escape_string($connect, trim($_POST['jeniscuti'] ?? ''));

if ($idcuti === '' || $nama === '') {
    exit('Data belum lengkap');
}

if ($idLama === '' || $idLama === 'test') {
    $ok = mysqli_query($connect, "INSERT INTO jeniscuti (idcuti, jeniscuti) VALUES ('$idcuti', '$nama')");
} else {
    $ok = mysqli_query($connect, "UPDATE jeniscuti SET idcuti='$idcuti', jeniscuti='$nama' WHERE idcuti='$idLama'");
}

echo $ok ? 'success' : mysqli_error($connect);
?>
