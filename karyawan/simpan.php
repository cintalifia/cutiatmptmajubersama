<?php
include "../function/koneksi.php";

$nik      = $_POST['nik'];
$nama     = $_POST['nama'];
$divisi   = $_POST['divisi'];
$level    = $_POST['level'];
$sisacuti = $_POST['sisacuti'];

mysqli_query($connect,"
INSERT INTO karyawan(nik,nama,divisi,level,sisacuti)
VALUES('$nik','$nama','$divisi','$level','$sisacuti')
");

header("Location:index.php");
exit;
?>