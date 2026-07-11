<?php
session_start();
require '../function/koneksi.php';

if (!isset($_SESSION['username'])) {
    header('Location: ../index.php');
    exit;
}

$nik              = mysqli_real_escape_string($connect, $_POST['nik'] ?? $_SESSION['username']);
$idcuti           = mysqli_real_escape_string($connect, $_POST['idcuti'] ?? '');
$tanggalpengajuan = mysqli_real_escape_string($connect, $_POST['tanggalpengajuan'] ?? date('Y-m-d'));
$tanggalmulai     = mysqli_real_escape_string($connect, $_POST['tanggalmulai'] ?? '');
$lamacuti         = (int) ($_POST['lamacuti'] ?? 0);
$alasancuti       = mysqli_real_escape_string($connect, trim($_POST['alasancuti'] ?? ''));
$status           = 'Pending';

if ($nik === '' || $idcuti === '' || $tanggalmulai === '' || $lamacuti < 1 || $alasancuti === '') {
    echo "<script>alert('Data pengajuan belum lengkap'); window.location='index.php';</script>";
    exit;
}

$qKaryawan = mysqli_query($connect, "SELECT * FROM karyawan WHERE nik='$nik' LIMIT 1");
$karyawan = $qKaryawan ? mysqli_fetch_assoc($qKaryawan) : null;
$sisacuti = (int) ($karyawan['sisacuti'] ?? 0);

if ($sisacuti > 0 && $lamacuti > $sisacuti) {
    echo "<script>alert('Lama cuti melebihi sisa cuti'); window.location='index.php';</script>";
    exit;
}

$query = mysqli_query($connect, "SELECT MAX(idpengajuancuti) AS terakhir FROM pengajuancuti");
$data = $query ? mysqli_fetch_assoc($query) : null;
$nomor = !empty($data['terakhir']) ? ((int) substr($data['terakhir'], 2)) + 1 : 1;
$idpengajuan = 'PC' . sprintf('%03d', $nomor);

$sql = mysqli_query($connect, "INSERT INTO pengajuancuti
    (idpengajuancuti, nik, idcuti, tanggalpengajuan, tanggalmulai, lamacuti, alasancuti, status)
    VALUES
    ('$idpengajuan', '$nik', '$idcuti', '$tanggalpengajuan', '$tanggalmulai', '$lamacuti', '$alasancuti', '$status')");

if ($sql) {
    echo "<script>alert('Pengajuan cuti berhasil disimpan'); window.location='index.php';</script>";
} else {
    $err = addslashes(mysqli_error($connect));
    echo "<script>alert('Gagal menyimpan data: $err'); window.location='index.php';</script>";
}
?>
