<?php
session_start();
require '../function/koneksi.php';

if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    exit('Akses ditolak');
}

$id = mysqli_real_escape_string($connect, $_POST['id'] ?? '');

if ($id === '') {
    exit('ID pengajuan tidak valid');
}

$query = mysqli_query($connect, "
    SELECT p.*, k.sisacuti
    FROM pengajuancuti p
    LEFT JOIN karyawan k ON p.nik = k.nik
    WHERE p.idpengajuancuti='$id'
    LIMIT 1
");

if (!$query || mysqli_num_rows($query) === 0) {
    exit('Data tidak ditemukan');
}

$data = mysqli_fetch_assoc($query);

if ($data['status'] === 'Success') {
    exit('Pengajuan ini sudah diproses sebelumnya');
}

if (!in_array($data['status'], ['Approved','Approve','Diterima','DITERIMA'], true)) {
    exit('Pengajuan belum disetujui, jadi belum bisa diproses');
}

$nik = mysqli_real_escape_string($connect, $data['nik']);
$cekPegawai = mysqli_query($connect, "SELECT nik FROM karyawan WHERE nik='$nik' LIMIT 1");
if (!$cekPegawai || mysqli_num_rows($cekPegawai) < 1) {
    exit('Data pegawai tidak ditemukan, sisa cuti belum dikurangi');
}

$lamaCuti = (int) $data['lamacuti'];
$updateSisa = mysqli_query($connect, "
    UPDATE karyawan
    SET sisacuti = GREATEST(sisacuti - $lamaCuti, 0)
    WHERE nik='$nik'
");

if (!$updateSisa) {
    exit(mysqli_error($connect));
}

$updateStatus = mysqli_query($connect, "
    UPDATE pengajuancuti
    SET status='Success'
    WHERE idpengajuancuti='$id'
");

if (!$updateStatus) {
    exit(mysqli_error($connect));
}

echo 'ok';
