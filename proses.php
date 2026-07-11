<?php
session_start();
require_once 'function/koneksi.php';

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

if (isset($_POST['proses'])) {
    $nik              = mysqli_real_escape_string($connect, $_POST['nik'] ?? '');
    $idcuti           = mysqli_real_escape_string($connect, $_POST['jenis_cuti'] ?? ($_POST['idcuti'] ?? ''));
    $tanggalpengajuan = mysqli_real_escape_string($connect, $_POST['tgl_entry'] ?? date('Y-m-d'));
    $tanggalmulai     = mysqli_real_escape_string($connect, $_POST['tgl_cuti'] ?? '');
    $lamacuti         = mysqli_real_escape_string($connect, $_POST['jml_cuti'] ?? '1');
    $alasancuti       = mysqli_real_escape_string($connect, $_POST['keterangan'] ?? '');

    $q = mysqli_query($connect, "SELECT MAX(idpengajuancuti) AS terakhir FROM pengajuancuti");
    $d = $q ? mysqli_fetch_assoc($q) : null;
    $urut = !empty($d['terakhir']) ? ((int) substr($d['terakhir'], 2)) + 1 : 1;
    $idpengajuan = 'PC' . str_pad($urut, 3, '0', STR_PAD_LEFT);

    $simpan = mysqli_query($connect, "INSERT INTO pengajuancuti
        (idpengajuancuti, nik, idcuti, tanggalpengajuan, tanggalmulai, lamacuti, alasancuti, status)
        VALUES ('$idpengajuan', '$nik', '$idcuti', '$tanggalpengajuan', '$tanggalmulai', '$lamacuti', '$alasancuti', 'Pending')");

    if ($simpan) {
        echo "<script>alert('Data berhasil disimpan'); window.location='pengajuancuti/index.php';</script>";
        exit;
    }

    echo 'Gagal menyimpan data: ' . mysqli_error($connect);
    exit;
}

if (isset($_POST['update_cuti'])) {
    $nik = mysqli_real_escape_string($connect, $_SESSION['username']);
    $keterangan = mysqli_real_escape_string($connect, $_POST['keterangan'] ?? '');
    mysqli_query($connect, "UPDATE pengajuancuti SET alasancuti='$keterangan' WHERE nik='$nik' ORDER BY tanggalpengajuan DESC LIMIT 1");
    header('Location: dashboard/index.php');
    exit;
}

header('Location: dashboard/index.php');
exit;
?>
