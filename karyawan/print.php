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

$sql = mysqli_query($connect, "SELECT * FROM karyawan ORDER BY nama ASC");
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Cetak Data Pegawai</title>
<style>
@page{size:A4 landscape;margin:12mm;}
body{font-family:Arial,Helvetica,sans-serif;background:#f8fafc;color:#1f2937;margin:0;padding:24px;}
.page{max-width:1000px;margin:0 auto;background:#fff;border-radius:16px;padding:26px;box-shadow:0 12px 28px rgba(15,23,42,.12);}
.actions{display:flex;gap:10px;margin-bottom:18px;}
.btn{display:inline-block;border:0;border-radius:10px;padding:10px 16px;background:#1e3a6f;color:#fff;text-decoration:none;font-weight:700;cursor:pointer;}
.btn-secondary{background:#1e3a6f;}
.btn-back-fixed{position:fixed;right:24px;bottom:24px;z-index:99;box-shadow:0 10px 22px rgba(30,58,111,.25);}
h1{text-align:center;margin:6px 0 22px;color:#0f172a;}
table{width:100%;border-collapse:collapse;table-layout:auto;}
th,td{border:1px solid #cbd5e1;padding:10px;text-align:center;font-size:13px;word-break:break-word;}
th{background:#1e3a6f;color:#fff;}
tr:nth-child(even) td{background:#f8fafc;}
@media print{body{background:#fff;padding:0}.page{box-shadow:none;border-radius:0;max-width:none}.actions{display:none}}
</style>
</head>
<body>
<div class="page">
    <div class="actions">
        <button class="btn" onclick="window.print()">Print</button>
        <a class="btn btn-secondary btn-back-fixed" href="index.php">Kembali</a>
    </div>
    <h1>Data Pegawai</h1>
    <table>
        <thead>
            <tr><th>No</th><th>NIK</th><th>Nama</th><th>Divisi</th><th>Level Pegawai</th><th>Sisa Cuti</th></tr>
        </thead>
        <tbody>
        <?php if ($sql && mysqli_num_rows($sql) > 0): ?>
            <?php $no = 1; while ($data = mysqli_fetch_assoc($sql)): ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo safe($data['nik']); ?></td>
                <td><?php echo safe($data['nama']); ?></td>
                <td><?php echo safe($data['divisi']); ?></td>
                <td><?php echo safe($data['level']); ?></td>
                <td><?php echo safe($data['sisacuti']); ?> hari</td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">Data tidak ada</td></tr>
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
