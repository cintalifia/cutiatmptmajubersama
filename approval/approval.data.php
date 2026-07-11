<?php
session_start();

require '../function/koneksi.php';

koneksi_buka();

$level  = $_SESSION['level'] ?? 'User';
$divisi = $_SESSION['divisi'] ?? '-';

if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    exit("Akses ditolak. Approval hanya dapat dilakukan oleh admin.");
}

$sql = "
SELECT pengajuancuti.*, karyawan.nama, karyawan.divisi, karyawan.level
FROM pengajuancuti
INNER JOIN karyawan
ON pengajuancuti.nik = karyawan.nik
WHERE pengajuancuti.status='Pending'
";

$query = mysqli_query($connect,$sql);
?>

<style>

.table-modern{
width:100%;
border-collapse:collapse;
overflow:hidden;
border-radius:15px;
}

.table-modern thead th{
background:linear-gradient(135deg,#3b82f6,#2563eb);
color:white;
padding:14px;
text-align:left;
}

.table-modern tbody td{
padding:14px;
border-bottom:1px solid #e2e8f0;
}

.table-modern tbody tr:hover{
background:#f8fafc;
}

.badge{
padding:6px 12px;
border-radius:20px;
font-size:12px;
font-weight:600;
color:white;
}

.badge-pending{
background:#f59e0b;
}

.btn-approve{
background:#22c55e;
color:white;
padding:8px 14px;
border-radius:10px;
text-decoration:none;
}

.btn-reject{
background:#ef4444;
color:white;
padding:8px 14px;
border-radius:10px;
text-decoration:none;
margin-left:5px;
}

.empty{
padding:40px;
text-align:center;
font-size:16px;
color:#64748b;
}

</style>

<?php
if(mysqli_num_rows($query) == 0)
{
    echo "
    <div class='empty'>
        Tidak ada pengajuan cuti yang menunggu approval.
    </div>
    ";
}
else
{
?>

<table class="table-modern">

<thead>
<tr>
<th>No</th>
<th>NIK</th>
<th>Nama</th>
<th>Divisi</th>
<th>Level</th>
<th>Tanggal Mulai</th>
<th>Lama Cuti</th>
<th>Alasan</th>
<th>Status</th>
<th>Aksi</th>
</tr>
</thead>

<tbody>

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

<td><?php echo $d['level']; ?></td>

<td><?php echo date('d-m-Y',strtotime($d['tanggalmulai'])); ?></td>

<td><?php echo $d['lamacuti']; ?> Hari</td>

<td><?php echo $d['alasancuti']; ?></td>

<td>
<span class="badge badge-pending">
Pending
</span>
</td>

<td>

<a href="approval.approve.php?id=<?php echo $d['idpengajuancuti']; ?>"
class="btn-approve">
Approve
</a>

<a href="approval.reject.php?id=<?php echo $d['idpengajuancuti']; ?>"
class="btn-reject">
Reject
</a>

</td>

</tr>

<?php
}
?>

</tbody>
</table>

<?php
}

koneksi_tutup();
?>