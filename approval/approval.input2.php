<?php
session_start();

require '../function/koneksi.php';

koneksi_buka();

if(!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin')
{
    exit("Akses ditolak. Approval hanya dapat dilakukan oleh admin.");
}

$idpengajuan = mysqli_real_escape_string($connect, $_POST['id'] ?? '');

if($idpengajuan == '')
{
    exit("ID Pengajuan tidak ditemukan");
}

/*
|--------------------------------------------------------------------------
| Cek Data Pengajuan
|--------------------------------------------------------------------------
*/

$cek = mysqli_query(
    $connect,
    "SELECT *
     FROM pengajuancuti
     WHERE idpengajuancuti='$idpengajuan'"
);

if(mysqli_num_rows($cek) == 0)
{
    exit("Data pengajuan tidak ditemukan");
}

$data = mysqli_fetch_assoc($cek);

/*
|--------------------------------------------------------------------------
| Jangan reject dua kali
|--------------------------------------------------------------------------
*/

if($data['status'] == 'Rejected')
{
    exit("Pengajuan sudah ditolak");
}

/*
|--------------------------------------------------------------------------
| Update Status Menjadi Rejected
|--------------------------------------------------------------------------
*/

$update = mysqli_query(
    $connect,
    "UPDATE pengajuancuti
     SET status='Rejected'
     WHERE idpengajuancuti='$idpengajuan'"
);

if($update)
{
    echo "success";
}
else
{
    echo "gagal";
}

koneksi_tutup();
?>
