<?php
session_start();
require '../function/koneksi.php';

if (!isset($_SESSION['username'])) {
    header('Location: ../index.php');
    exit;
}

$username = $_SESSION['username'];
$nama     = $_SESSION['nama'] ?? $username;
$level    = $_SESSION['level'] ?? 'User';
$divisi   = $_SESSION['divisi'] ?? '-';
$isAdmin  = ($username === 'admin');

function hitung($connect, $sql) {
    $q = mysqli_query($connect, $sql);
    return $q ? mysqli_num_rows($q) : 0;
}

$whereUser = mysqli_real_escape_string($connect, $username);
$totalPegawai   = hitung($connect, 'SELECT * FROM karyawan');
$totalPengajuan = $isAdmin ? hitung($connect, 'SELECT * FROM pengajuancuti') : hitung($connect, "SELECT * FROM pengajuancuti WHERE nik='$whereUser'");
$totalPending   = $isAdmin ? hitung($connect, "SELECT * FROM pengajuancuti WHERE LOWER(status)='pending'") : hitung($connect, "SELECT * FROM pengajuancuti WHERE nik='$whereUser' AND LOWER(status)='pending'");
$totalApproved  = $isAdmin ? hitung($connect, "SELECT * FROM pengajuancuti WHERE LOWER(status) IN ('approved','approve','success','diterima')") : hitung($connect, "SELECT * FROM pengajuancuti WHERE nik='$whereUser' AND LOWER(status) IN ('approved','approve','success','diterima')");


$recentSql = $isAdmin
    ? "SELECT p.*, k.nama, j.jeniscuti FROM pengajuancuti p LEFT JOIN karyawan k ON p.nik=k.nik LEFT JOIN jeniscuti j ON p.idcuti=j.idcuti ORDER BY p.tanggalpengajuan DESC LIMIT 8"
    : "SELECT p.*, k.nama, j.jeniscuti FROM pengajuancuti p LEFT JOIN karyawan k ON p.nik=k.nik LEFT JOIN jeniscuti j ON p.idcuti=j.idcuti WHERE p.nik='$whereUser' ORDER BY p.tanggalpengajuan DESC LIMIT 8";
$recent = mysqli_query($connect, $recentSql);
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | E-Cuti PT Maju Bersama</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
<?php include '../function/sidebar.php'; ?>

<main class="content">
    <section class="ec-page-header">
        <h1>Dashboard</h1>
        <p>Halo, <?php echo htmlspecialchars($nama); ?>. Sistem E-Cuti PT Maju Bersama.</p>
    </section>

    <section class="ec-grid<?php echo $isAdmin ? ' ec-grid-4' : ''; ?>">
        <?php if ($isAdmin): ?>
        <div class="ec-card ec-stat">
            <h2><?php echo $totalPegawai; ?></h2>
            <p>Total Pegawai</p>
        </div>
        <?php endif; ?>
        <div class="ec-card ec-stat">
            <h2><?php echo $totalPengajuan; ?></h2>
            <p>Total Pengajuan</p>
        </div>
        <div class="ec-card ec-stat">
            <h2><?php echo $totalPending; ?></h2>
            <p>Menunggu Approval</p>
        </div>
        <div class="ec-card ec-stat">
            <h2><?php echo $totalApproved; ?></h2>
            <p>Disetujui</p>
        </div>
    </section>

    <section class="ec-card" style="padding:24px;">
        <div class="ec-toolbar">
            <div>
                <h2 style="margin:0;font-size:22px;">Pengajuan Terbaru</h2>
                <p style="margin:6px 0 0;color:#64748b;">Data terakhir yang masuk ke sistem.</p>
            </div>
            <a class="ec-btn ec-btn-primary" href="../pengajuancuti/">+ Ajukan Cuti</a>
        </div>
        <div class="ec-table-wrap">
            <table class="ec-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Jenis Cuti</th>
                        <th>Tanggal Mulai</th>
                        <th>Lama</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($recent && mysqli_num_rows($recent) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($recent)): ?>
                    <?php
                        $status = $row['status'] ?: 'Pending';
                        $class = in_array($status, ['Approved','Success','Diterima','DITERIMA'], true) ? 'success' : ($status === 'Rejected' ? 'danger' : 'warning');
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['idpengajuancuti']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama'] ?: '-'); ?></td>
                        <td><?php echo htmlspecialchars($row['jeniscuti'] ?: $row['idcuti']); ?></td>
                        <td><?php echo htmlspecialchars($row['tanggalmulai']); ?></td>
                        <td><?php echo htmlspecialchars($row['lamacuti']); ?> hari</td>
                        <td><span class="ec-badge <?php echo $class; ?>"><?php echo htmlspecialchars($status); ?></span></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" style="text-align:center;color:#64748b;">Belum ada data pengajuan.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>
</body>
</html>
