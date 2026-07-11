<?php
session_start();
require '../function/koneksi.php';

if (!isset($_SESSION['username'])) {
    header('Location: ../index.php');
    exit;
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cek Pengajuan Cuti | e-Cuti</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
<?php include '../function/sidebar.php'; ?>
<main class="content">
    <section class="ec-page-header">
        <h1>Cek Pengajuan Cuti</h1>
        <p><?php echo $isAdmin ? 'Pantau seluruh pengajuan cuti.' : 'Pantau status pengajuan cuti Anda.'; ?></p>
    </section>

    <section class="ec-card" style="padding:24px;">
        <div class="ec-toolbar">
            <div>
                <h2 style="margin:0;font-size:22px;">Riwayat Pengajuan</h2>
                <p style="margin:6px 0 0;color:#64748b;">Status akan berubah setelah diproses oleh atasan/HRD.</p>
            </div>
            <a class="ec-btn ec-btn-primary" href="print.php">Cetak Data</a>
        </div>
        <div class="ec-table-wrap">
            <table class="ec-table">
                <thead><tr><th>ID</th><th>NIK</th><th>Nama</th><th>Jenis Cuti</th><th>Tanggal Pengajuan</th><th>Tanggal Mulai</th><th>Lama</th><th>Alasan</th><th>Status</th></tr></thead>
                <tbody>
                <?php if ($query && mysqli_num_rows($query) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($query)): ?>
                    <?php
                        $status = $row['status'] ?: 'Pending';
                        $class = in_array($status, ['Approved','Diterima','DITERIMA','Success'], true) ? 'success' : ($status === 'Rejected' ? 'danger' : 'warning');
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['idpengajuancuti']); ?></td>
                        <td><?php echo htmlspecialchars($row['nik']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama'] ?? '-'); ?></td>
                        <td><?php echo htmlspecialchars($row['jeniscuti'] ?? $row['idcuti']); ?></td>
                        <td><?php echo htmlspecialchars($row['tanggalpengajuan']); ?></td>
                        <td><?php echo htmlspecialchars($row['tanggalmulai']); ?></td>
                        <td><?php echo htmlspecialchars($row['lamacuti']); ?> hari</td>
                        <td><?php echo htmlspecialchars($row['alasancuti']); ?></td>
                        <td><span class="ec-badge <?php echo $class; ?>"><?php echo htmlspecialchars($status); ?></span></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="9" style="text-align:center;color:#64748b;">Belum ada pengajuan cuti.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>
</body>
</html>
