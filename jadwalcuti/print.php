<?php
session_start();

require '../function/koneksi.php';

koneksi_buka();

$query = mysqli_query($connect,"
SELECT
    p.*,
    k.nama,
    k.divisi,
    j.jeniscuti
FROM pengajuancuti p
LEFT JOIN karyawan k
    ON p.nik = k.nik
LEFT JOIN jeniscuti j
    ON p.idcuti = j.idcuti
WHERE p.status NOT IN ('Rejected','Reject','Ditolak','DITOLAK')
ORDER BY p.tanggalmulai ASC
");

function tampil_status_jadwal_print($status) {
    $status = trim((string) $status);
    if (in_array($status, ['Approved','Approve','Success','Diterima','DITERIMA'], true)) {
        return 'Approved';
    }
    return 'Pending';
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Cetak Jadwal Cuti</title>

<style>
@page{size:A4 landscape;margin:12mm;}

body{
font-family:Arial,sans-serif;
}

h2{
text-align:center;
margin-bottom:5px;
}

h3{
text-align:center;
margin-top:0;
margin-bottom:20px;
}

table{
width:100%;
border-collapse:collapse;
}

table th,
table td{
border:1px solid #000;
padding:8px;
font-size:13px;
text-align:center;
}

table th{
background:#f0f0f0;
}

.ttd{
margin-top:50px;
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

<button class="btn-print" onclick="window.print()">Print</button>
<a class="btn-print btn-back" href="index.php">Kembali</a>

<h2>PT MAJU BERSAMA</h2>
<h3>JADWAL CUTI PEGAWAI</h3>

<table>

<tr>
<th>No</th>
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

while($d = mysqli_fetch_assoc($query))
{
?>

<tr>

<td><?php echo $no++; ?></td>

<td><?php echo $d['nik']; ?></td>

<td><?php echo $d['nama']; ?></td>

<td><?php echo $d['divisi']; ?></td>

<td><?php echo $d['jeniscuti']; ?></td>

<td>
<?php echo date('d-m-Y',strtotime($d['tanggalmulai'])); ?>
</td>

<td>
<?php echo $d['lamacuti']; ?> Hari
</td>

<td>
<?php echo tampil_status_jadwal_print($d['status']); ?>
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

<script>window.addEventListener('afterprint', function(){ window.location.href='index.php'; });</script>
</body>
</html>

<?php
koneksi_tutup();
?>
