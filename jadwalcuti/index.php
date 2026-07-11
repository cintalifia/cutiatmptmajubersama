<?php
session_start();
require '../function/koneksi.php';

if (!isset($_SESSION['username'])) {
    header('Location: ../index.php');
    exit;
}

$query = mysqli_query($connect, "
    SELECT p.*, k.nama, k.divisi, j.jeniscuti
    FROM pengajuancuti p
    LEFT JOIN karyawan k ON p.nik = k.nik
    LEFT JOIN jeniscuti j ON p.idcuti = j.idcuti
    WHERE p.status NOT IN ('Rejected','Reject','Ditolak','DITOLAK')
    ORDER BY p.tanggalmulai ASC
");
$total = $query ? mysqli_num_rows($query) : 0;

function tampil_status_jadwal($status) {
    $status = trim((string) $status);
    if (in_array($status, ['Approved','Approve','Success','Diterima','DITERIMA'], true)) {
        return ['Approved', 'success'];
    }
    return ['Pending', 'warning'];
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jadwal Cuti | e-Cuti</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
<?php include '../function/sidebar.php'; ?>
<main class="content">
    <section class="ec-page-header">
        <h1>Jadwal Cuti</h1>
        <p>Daftar cuti yang diajukan beserta status approval dari admin.</p>
    </section>

    <section class="ec-grid">
        <div class="ec-card ec-stat"><h2><?php echo $total; ?></h2><p>Total Jadwal Cuti</p></div>
    </section>

    <section class="ec-card" style="padding:24px;">
        <div class="ec-toolbar">
            <div>
                <h2 style="margin:0;font-size:22px;">Daftar Jadwal</h2>
                <p style="margin:6px 0 0;color:#64748b;">Cuti diurutkan berdasarkan tanggal mulai. Jika belum disetujui admin, status tetap Pending.</p>
            </div>
            <a class="ec-btn ec-btn-primary" href="print.php">Cetak Jadwal</a>
        </div>
        <div class="ec-table-wrap">
            <table class="ec-table">
                <thead><tr><th>No</th><th>NIK</th><th>Nama</th><th>Divisi</th><th>Jenis Cuti</th><th>Tanggal Mulai</th><th>Lama</th><th>Status</th></tr></thead>
                <tbody>
                <?php if ($query && mysqli_num_rows($query) > 0): $no=1; ?>
                    <?php while ($d = mysqli_fetch_assoc($query)): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($d['nik']); ?></td>
                        <td><?php echo htmlspecialchars($d['nama'] ?? '-'); ?></td>
                        <td><?php echo htmlspecialchars($d['divisi'] ?? '-'); ?></td>
                        <td><?php echo htmlspecialchars($d['jeniscuti'] ?? $d['idcuti']); ?></td>
                        <td><?php echo htmlspecialchars(date('d-m-Y', strtotime($d['tanggalmulai']))); ?></td>
                        <td><?php echo htmlspecialchars($d['lamacuti']); ?> hari</td>
                        <?php list($labelStatus, $badgeStatus) = tampil_status_jadwal($d['status']); ?>
                        <td><span class="ec-badge <?php echo $badgeStatus; ?>"><?php echo $labelStatus; ?></span></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="8" style="text-align:center;color:#64748b;">Belum ada data jadwal cuti.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>
</body>
</html>
