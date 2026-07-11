<?php
session_start();
require '../function/koneksi.php';

if (!isset($_SESSION['username'])) {
    exit('Akses ditolak');
}

if (isset($_POST['hapus'])) {
    $nik = mysqli_real_escape_string($connect, $_POST['hapus']);
    mysqli_query($connect, "DELETE FROM karyawan WHERE nik='$nik'");
    mysqli_query($connect, "DELETE FROM userlogin WHERE username='$nik'");
    echo 'success';
    exit;
}

$nikLama  = mysqli_real_escape_string($connect, $_POST['id'] ?? '');
$nik      = mysqli_real_escape_string($connect, trim($_POST['nik'] ?? ''));
$nama     = mysqli_real_escape_string($connect, trim($_POST['nama'] ?? ''));
$divisi   = mysqli_real_escape_string($connect, trim($_POST['divisi'] ?? ''));
$level    = mysqli_real_escape_string($connect, trim($_POST['levelkaryawan'] ?? $_POST['level'] ?? 'Staff'));
$sisacuti = (int) ($_POST['sisacuti'] ?? 0);

if ($nik === '' || $nama === '' || $divisi === '') {
    exit('Data belum lengkap');
}

if ($nikLama === '' || $nikLama === '0') {
    $cek = mysqli_query($connect, "SELECT nik FROM karyawan WHERE nik='$nik' LIMIT 1");
    if ($cek && mysqli_num_rows($cek) > 0) {
        exit('NIK sudah terdaftar');
    }
    $ok = mysqli_query($connect, "INSERT INTO karyawan (nik,nama,divisi,level,sisacuti) VALUES ('$nik','$nama','$divisi','$level','$sisacuti')");
    if ($ok) {
        mysqli_query($connect, "INSERT INTO userlogin (username,password) VALUES ('$nik','123456')");
    }
} else {
    $ok = mysqli_query($connect, "UPDATE karyawan SET nik='$nik', nama='$nama', divisi='$divisi', level='$level', sisacuti='$sisacuti' WHERE nik='$nikLama'");
    if ($ok && $nikLama !== $nik) {
        mysqli_query($connect, "UPDATE userlogin SET username='$nik' WHERE username='$nikLama'");
    }
}

echo $ok ? 'success' : mysqli_error($connect);
?>
