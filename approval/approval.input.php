<?php
session_start();

require '../function/koneksi.php';

koneksi_buka();

if(!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin')
{
    exit("Akses ditolak. Approval hanya dapat dilakukan oleh admin.");
}

$idpengajuan = mysqli_real_escape_string($connect, $_POST['id'] ?? '');

if(empty($idpengajuan))
{
    exit("ID Pengajuan tidak ditemukan");
}

/* CEK DATA PENGAJUAN */

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

if($data['status'] == 'Approved')
{
    exit("Pengajuan sudah diapprove");
}

/* GENERATE ID APPROVAL */

$q = mysqli_query(
    $connect,
    "SELECT MAX(idapprovecuti) AS kode
     FROM approvecuti"
);

$d = mysqli_fetch_assoc($q);

if(!empty($d['kode']))
{
    $urut = (int) substr($d['kode'],2);
    $urut++;
}
else
{
    $urut = 1;
}

$idapprove = "AP".str_pad($urut,3,"0",STR_PAD_LEFT);

/* DATA APPROVER */

$approveby      = $_SESSION['nama'] ?? $_SESSION['username'];
$tanggalapprove = date('Y-m-d');

/* SIMPAN KE APPROVECUTI */

$simpan = mysqli_query(
    $connect,
    "INSERT INTO approvecuti
    (
        idapprovecuti,
        idpengajuancuti,
        tanggalapprove,
        approveby
    )
    VALUES
    (
        '$idapprove',
        '$idpengajuan',
        '$tanggalapprove',
        '$approveby'
    )"
);

if(!$simpan)
{
    exit(mysqli_error($connect));
}

/* UPDATE STATUS */

$update = mysqli_query(
    $connect,
    "UPDATE pengajuancuti
     SET status='Approved'
     WHERE idpengajuancuti='$idpengajuan'"
);

if(!$update)
{
    exit(mysqli_error($connect));
}

echo "Approval berhasil";

koneksi_tutup();
?>