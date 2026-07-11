<?php
session_start();
require 'function/koneksi.php';

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

$username = $_SESSION['username'];
$divisi = isset($_SESSION['divisi']) ? $_SESSION['divisi'] : '-';
$isAdmin = ($username === 'admin');
$id = isset($_GET['id']) ? mysqli_real_escape_string($connect, $_GET['id']) : '';

function safe($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

if ($id !== '') {
    $whereUser = $isAdmin ? '' : " AND p.nik='" . mysqli_real_escape_string($GLOBALS['connect'], $username) . "'";
    $query = mysqli_query($connect, "
        SELECT p.*, k.nama, k.divisi, k.level, j.jeniscuti
        FROM pengajuancuti p
        LEFT JOIN karyawan k ON p.nik = k.nik
        LEFT JOIN jeniscuti j ON p.idcuti = j.idcuti
        WHERE p.idpengajuancuti='$id' $whereUser
        LIMIT 1
    ");
    $cuti = $query ? mysqli_fetch_assoc($query) : null;
    if (!$cuti) {
        die('Data cuti tidak ditemukan.');
    }
} else {
    $whereUser = $isAdmin ? '' : " WHERE p.nik='" . mysqli_real_escape_string($connect, $username) . "'";
    $list = mysqli_query($connect, "
        SELECT p.*, k.nama, k.divisi, j.jeniscuti
        FROM pengajuancuti p
        LEFT JOIN karyawan k ON p.nik = k.nik
        LEFT JOIN jeniscuti j ON p.idcuti = j.idcuti
        $whereUser
        ORDER BY p.tanggalpengajuan DESC
    ");
}
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Cetak Form Cuti | e-Cuti</title>
<style>
body{margin:0;background:#eef4ff;font-family:Arial,Helvetica,sans-serif;color:#1f2937;}
.page{max-width:900px;margin:28px auto;background:#fff;padding:34px;border-radius:18px;box-shadow:0 10px 30px rgba(15,23,42,.12);}
.header{text-align:center;border-bottom:2px solid #1d4ed8;padding-bottom:16px;margin-bottom:24px;}
.header h1{margin:0;color:#1d4ed8;font-size:26px;}.header p{margin:6px 0 0;color:#64748b;}
table{width:100%;border-collapse:collapse;margin-top:16px;}th,td{border:1px solid #d1d5db;padding:11px;text-align:left;font-size:14px;}th{background:#eff6ff;color:#1e40af;}
.info th{width:220px;background:#f8fafc;color:#334155;}.btn{display:inline-block;text-decoration:none;border:0;border-radius:10px;padding:10px 14px;background:#2563eb;color:#fff;font-weight:700;cursor:pointer;margin:0 6px 10px 0;}.btn-secondary{background:#64748b;}.btn-back-fixed{position:fixed;right:24px;bottom:24px;z-index:99;background:#1e3a6f!important;box-shadow:0 10px 22px rgba(30,58,111,.25);}.badge{display:inline-block;padding:5px 10px;border-radius:999px;font-weight:700;background:#fef3c7;color:#92400e;}.badge.ok{background:#dcfce7;color:#166534;}.badge.no{background:#fee2e2;color:#991b1b;}.signature{margin-top:56px;text-align:right;}.signature .line{margin-top:70px;font-weight:700;}
@media print{body{background:#fff}.page{box-shadow:none;margin:0;max-width:none;border-radius:0}.no-print{display:none!important}}
</style>
</head>
<body>
<div class="page">
<?php if ($id === ''): ?>
    <div class="header"><h1>Daftar Form Cuti</h1><p>Pilih data cuti yang ingin dicetak.</p></div>
    <div class="no-print"><a class="btn btn-secondary btn-back-fixed" href="dashboard/">Kembali Dashboard</a></div>
    <table>
        <thead><tr><th>ID</th><th>NIK</th><th>Nama</th><th>Jenis Cuti</th><th>Tanggal Mulai</th><th>Lama</th><th>Status</th><th class="no-print">Aksi</th></tr></thead>
        <tbody>
        <?php if ($list && mysqli_num_rows($list) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($list)): ?>
            <?php $status = $row['status'] ? $row['status'] : 'Pending'; $cls = in_array($status, array('Approved','Success','Diterima','DITERIMA'), true) ? 'ok' : ($status === 'Rejected' ? 'no' : ''); ?>
            <tr>
                <td><?php echo safe($row['idpengajuancuti']); ?></td>
                <td><?php echo safe($row['nik']); ?></td>
                <td><?php echo safe($row['nama']); ?></td>
                <td><?php echo safe($row['jeniscuti'] ? $row['jeniscuti'] : $row['idcuti']); ?></td>
                <td><?php echo safe($row['tanggalmulai']); ?></td>
                <td><?php echo safe($row['lamacuti']); ?> hari</td>
                <td><span class="badge <?php echo $cls; ?>"><?php echo safe($status); ?></span></td>
                <td class="no-print">
    <?php if ($status == 'Pending') { ?>
        <form action="proses_cuti.php" method="POST" style="display:inline;"
            onsubmit="return confirm('Yakin ingin memproses cuti ini?');">
            <input type="hidden" name="id" value="<?php echo $row['idpengajuancuti']; ?>">
            <button type="submit" class="btn" style="background:#16a34a;">
                Proses
            </button>
        </form>
    <?php } else { ?>
        <span style="color:#64748b;">-</span>
    <?php } ?>
</td></td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="8" style="text-align:center;color:#64748b;">Belum ada data cuti.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="no-print"><button class="btn" onclick="window.print()">Print</button><a class="btn btn-secondary btn-back-fixed" href="cetak.php">Kembali</a></div>
    <div class="header"><h1>SURAT KETERANGAN CUTI</h1><p>e-Cuti</p></div>
    <p>Yang bertanda tangan di bawah ini menerangkan bahwa pegawai berikut telah mengajukan cuti:</p>
    <table class="info">
        <tr><th>ID Pengajuan</th><td><?php echo safe($cuti['idpengajuancuti']); ?></td></tr>
        <tr><th>NIK</th><td><?php echo safe($cuti['nik']); ?></td></tr>
        <tr><th>Nama</th><td><?php echo safe($cuti['nama']); ?></td></tr>
        <tr><th>Divisi</th><td><?php echo safe($cuti['divisi']); ?></td></tr>
        <tr><th>Level</th><td><?php echo safe($cuti['level']); ?></td></tr>
        <tr><th>Jenis Cuti</th><td><?php echo safe($cuti['jeniscuti'] ? $cuti['jeniscuti'] : $cuti['idcuti']); ?></td></tr>
        <tr><th>Tanggal Pengajuan</th><td><?php echo safe(date('d-m-Y', strtotime($cuti['tanggalpengajuan']))); ?></td></tr>
        <tr><th>Tanggal Mulai</th><td><?php echo safe(date('d-m-Y', strtotime($cuti['tanggalmulai']))); ?></td></tr>
        <tr><th>Lama Cuti</th><td><?php echo safe($cuti['lamacuti']); ?> hari</td></tr>
        <tr><th>Alasan</th><td><?php echo safe($cuti['alasancuti']); ?></td></tr>
        <tr><th>Status</th><td><?php echo safe($cuti['status']); ?></td></tr>
    </table>
    <div class="signature">
        <p>Jakarta, <?php echo date('d-m-Y'); ?></p>
        <p>Hormat kami,</p>
        <p class="line"><?php echo safe(isset($_SESSION['nama']) ? $_SESSION['nama'] : 'Administrator'); ?></p>
    </div>
<?php endif; ?>
</div>
<script>window.addEventListener('afterprint', function(){ window.location.href = 'cetak.php'; });</script>
</body>
</html>
