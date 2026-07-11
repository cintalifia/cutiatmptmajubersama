<?php
session_start();
require '../function/koneksi.php';

if (!isset($_SESSION['username'])) {
    header('Location: ../index.php');
    exit;
}

function safe($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$username = $_SESSION['username'];
$divisi = $_SESSION['divisi'] ?? '-';
$isAdmin = ($username === 'admin') || strtoupper($divisi) === 'HRD';
$safeUser = mysqli_real_escape_string($connect, $username);

$sql = "
    SELECT p.*, k.nama, k.divisi, j.jeniscuti
    FROM pengajuancuti p
    LEFT JOIN karyawan k ON p.nik = k.nik
    LEFT JOIN jeniscuti j ON p.idcuti = j.idcuti
";
if (!$isAdmin) {
    $sql .= " WHERE p.nik='$safeUser'";
}
$sql .= " ORDER BY p.tanggalpengajuan DESC";
$query = mysqli_query($connect, $sql);
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Cetak Pengajuan Cuti</title>
<style>
@page{size:A4 landscape;margin:12mm;}
body{font-family:Arial,Helvetica,sans-serif;background:#f8fafc;color:#1f2937;margin:0;padding:24px;}
.page{max-width:1200px;margin:0 auto;background:#fff;border-radius:16px;padding:26px;box-shadow:0 12px 28px rgba(15,23,42,.12);}
.actions{display:flex;gap:10px;margin-bottom:18px;}
.btn{display:inline-block;border:0;border-radius:10px;padding:10px 16px;background:#1e3a6f;color:#fff;text-decoration:none;font-weight:700;cursor:pointer;}
.btn-secondary{background:#1e3a6f;}
.btn-back-fixed{position:fixed;right:24px;bottom:24px;z-index:99;box-shadow:0 10px 22px rgba(30,58,111,.25);}
h1{text-align:center;margin:6px 0 22px;color:#0f172a;}
table{width:100%;border-collapse:collapse;table-layout:auto;}
th,td{border:1px solid #cbd5e1;padding:9px;text-align:center;font-size:12px;word-break:break-word;}
th{background:#1e3a6f;color:#fff;}
tr:nth-child(even) td{background:#f8fafc;}
.badge{display:inline-block;padding:4px 8px;border-radius:999px;font-weight:700;background:#fef3c7;color:#92400e;}
.badge.success{background:#dcfce7;color:#166534;}.badge.danger{background:#fee2e2;color:#991b1b;}
@media print{body{background:#fff;padding:0}.page{box-shadow:none;border-radius:0;max-width:none}.actions{display:none}th,td{font-size:11px;padding:7px}}
</style>
</head>
<body>
<div class="page">
    <div class="actions">
        <button class="btn" onclick="window.print()">Print</button>
        <a class="btn btn-secondary btn-back-fixed" href="index.php">Kembali</a>
    </div>
    <h1>Riwayat Pengajuan Cuti</h1>
    <table>
        <thead>
            <tr><th>No</th><th>ID</th><th>NIK</th><th>Nama</th><th>Divisi</th><th>Jenis Cuti</th><th>Tanggal Pengajuan</th><th>Tanggal Mulai</th><th>Lama</th><th>Alasan</th><th>Status</th></tr>
        </thead>
        <tbody>
        <?php if ($query && mysqli_num_rows($query) > 0): ?>
            <?php $no = 1; while ($row = mysqli_fetch_assoc($query)): ?>
            <?php
                $status = $row['status'] ?: 'Pending';
                $class = in_array($status, ['Approved','Approve','Success','Diterima','DITERIMA'], true) ? 'success' : ($status === 'Rejected' ? 'danger' : '');
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo safe($row['idpengajuancuti']); ?></td>
                <td><?php echo safe($row['nik']); ?></td>
                <td><?php echo safe($row['nama'] ?? '-'); ?></td>
                <td><?php echo safe($row['divisi'] ?? '-'); ?></td>
                <td><?php echo safe($row['jeniscuti'] ?? $row['idcuti']); ?></td>
                <td><?php echo safe($row['tanggalpengajuan']); ?></td>
                <td><?php echo safe($row['tanggalmulai']); ?></td>
                <td><?php echo safe($row['lamacuti']); ?> hari</td>
                <td><?php echo safe($row['alasancuti']); ?></td>
                <td><span class="badge <?php echo $class; ?>"><?php echo safe($status); ?></span></td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="11">Belum ada pengajuan cuti.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<script>
(function(){
    var kembali = 'index.php';
    window.addEventListener('afterprint', function(){ window.location.href = kembali; });
    window.addEventListener('load', function(){ setTimeout(function(){ window.print(); }, 300); });
})();
</script>
</body>
</html>
