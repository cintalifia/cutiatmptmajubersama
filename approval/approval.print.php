<?php
session_start();

require '../function/koneksi.php';

koneksi_buka();

if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    exit('Akses ditolak. Laporan approval hanya dapat dibuka oleh admin.');
}

$sql = mysqli_query($connect,"
SELECT
    p.idpengajuancuti,
    p.nik,
    IFNULL(k.nama,p.nik) AS nama,
    IFNULL(k.divisi,'-') AS divisi,
    j.jeniscuti,
    p.tanggalmulai,
    p.lamacuti,
    p.status
FROM pengajuancuti p
LEFT JOIN karyawan k
    ON p.nik = k.nik
LEFT JOIN jeniscuti j
    ON p.idcuti = j.idcuti
ORDER BY p.idpengajuancuti DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Laporan Approval Cuti</title>

<style>
@page{size:A4 landscape;margin:12mm;}

body{
    font-family:Arial, sans-serif;
    margin:0;
    padding:0;
}

.container{
    width:100%;
    padding:20px;
}

h2{
    text-align:center;
    margin-bottom:5px;
}

p{
    text-align:center;
    margin-top:0;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}

table th{
    background:#1e3a6f;
    color:white;
    border:1px solid #000;
    padding:10px;
    text-align:center;
}

table td{
    border:1px solid #000;
    padding:8px;
    text-align:center;
}

.pending{
    color:#f59e0b;
    font-weight:bold;
}

.approved{
    color:#22c55e;
    font-weight:bold;
}

.rejected{
    color:#ef4444;
    font-weight:bold;
}

.ttd{
    margin-top:40px;
    text-align:right;
}

.btn-print{display:inline-block;margin:0 8px 15px 0;padding:10px 14px;border:0;border-radius:8px;background:#1e3a6f;color:#fff;text-decoration:none;font-weight:bold;cursor:pointer}.btn-back{position:fixed;right:24px;bottom:24px;z-index:99;background:#1e3a6f;box-shadow:0 10px 22px rgba(30,58,111,.25)}

@media print{
    .btn-print{
        display:none;
    }
}

</style>
</head>

<body>

<div class="container">

<button class="btn-print" onclick="window.print()">Print</button>
<a class="btn-print btn-back" href="index.php">Kembali</a>

<h2>PT MAJU BERSAMA</h2>
<p>Laporan Approval Cuti</p>

<table>

<tr>
    <th>No</th>
    <th>ID Pengajuan</th>
    <th>NIK</th>
    <th>Nama</th>
    <th>Divisi</th>
    <th>Jenis Cuti</th>
    <th>Tanggal Mulai</th>
    <th>Lama Cuti</th>
    <th>Status</th>
</tr>

<?php
$no = 1;

while($d = mysqli_fetch_assoc($sql))
{
?>
<tr>

<td><?php echo $no++; ?></td>

<td><?php echo $d['idpengajuancuti']; ?></td>

<td><?php echo $d['nik']; ?></td>

<td><?php echo $d['nama']; ?></td>

<td><?php echo $d['divisi']; ?></td>

<td><?php echo $d['jeniscuti']; ?></td>

<td><?php echo date('d-m-Y',strtotime($d['tanggalmulai'])); ?></td>

<td><?php echo $d['lamacuti']; ?> Hari</td>

<td>
<?php
if($d['status']=='Approved')
{
    echo "<span class='approved'>Approved</span>";
}
elseif($d['status']=='Rejected')
{
    echo "<span class='rejected'>Rejected</span>";
}
else
{
    echo "<span class='pending'>Pending</span>";
}
?>
</td>

</tr>
<?php
}
?>

</table>

<div class="ttd">

<p>
Jakarta,
<?php echo date('d-m-Y'); ?>
</p>

<br><br><br>

<b>
<?php
echo $_SESSION['nama'] ?? 'Administrator';
?>
</b>

</div>

</div>

<script>window.addEventListener('afterprint', function(){ window.location.href='index.php'; });</script>
</body>
</html>

<?php
koneksi_tutup();
?>
