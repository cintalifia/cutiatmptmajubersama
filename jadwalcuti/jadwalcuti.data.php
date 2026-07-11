<?php
session_start();
require '../function/koneksi.php';

if (!isset($_SESSION['username'])) {
    exit('Akses ditolak');
}

$query = mysqli_query($connect, "
    SELECT p.*, k.nama, k.divisi, j.jeniscuti
    FROM pengajuancuti p
    LEFT JOIN karyawan k ON p.nik = k.nik
    LEFT JOIN jeniscuti j ON p.idcuti = j.idcuti
    WHERE p.status NOT IN ('Rejected','Reject','Ditolak','DITOLAK')
    ORDER BY p.tanggalmulai ASC
");

function tampil_status_jadwal($status) {
    $status = trim((string) $status);
    if (in_array($status, ['Approved','Approve','Success','Diterima','DITERIMA'], true)) {
        return ['Approved', 'success'];
    }
    return ['Pending', 'warning'];
}
?>
<div class="ec-table-wrap">
<table class="ec-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>NIK</th>
            <th>Nama</th>
            <th>Divisi</th>
            <th>Jenis Cuti</th>
            <th>Tanggal Mulai</th>
            <th>Lama</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    <?php if ($query && mysqli_num_rows($query) > 0): ?>
        <?php while ($d = mysqli_fetch_assoc($query)): ?>
        <tr>
            <td><?php echo htmlspecialchars($d['idpengajuancuti']); ?></td>
            <td><?php echo htmlspecialchars($d['nik']); ?></td>
            <td><?php echo htmlspecialchars($d['nama'] ?? '-'); ?></td>
            <td><?php echo htmlspecialchars($d['divisi'] ?? '-'); ?></td>
            <td><?php echo htmlspecialchars($d['jeniscuti'] ?? $d['idcuti']); ?></td>
            <td><?php echo htmlspecialchars($d['tanggalmulai']); ?></td>
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
