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

$pesan = '';
if (isset($_GET['pesan']) && $_GET['pesan'] === 'updated') {
    $pesan = 'Sisa cuti berhasil diperbarui setelah cuti di-approve.';
}

$sisaSql = "
    SELECT k.*, k.sisacuti AS sisa_aktual
    FROM karyawan k
";
if ($isAdmin) {
    $query = mysqli_query($connect, $sisaSql . " ORDER BY k.nama ASC");
} else {
    $safeUser = mysqli_real_escape_string($connect, $username);
    $query = mysqli_query($connect, $sisaSql . " WHERE k.nik='$safeUser' ORDER BY k.nama ASC");
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sisa Cuti | E-Cuti PT Maju Bersama</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>.alert{padding:13px 15px;border-radius:13px;margin-bottom:16px;font-weight:600;}.alert-success{background:#dcfce7;color:#166534;}</style>
</head>
<body>
<?php include '../function/sidebar.php'; ?>
<main class="content">
    <section class="ec-page-header">
        <h1>Sisa Cuti</h1>
        <p><?php echo $isAdmin ? 'Monitoring sisa cuti seluruh pegawai.' : 'Informasi sisa cuti Anda saat ini.'; ?></p>
    </section>
    <?php if ($pesan): ?><div class="alert alert-success"><?php echo htmlspecialchars($pesan); ?></div><?php endif; ?>

    <section class="ec-card" style="padding:24px;">
        <div class="ec-toolbar">
            <div>
                <h2 style="margin:0;font-size:22px;">Daftar Sisa Cuti</h2>
                <p style="margin:6px 0 0;color:#64748b;">Sisa cuti otomatis berkurang setelah form cuti di-approve pada menu Proses Form Cuti.</p>
            </div>
            <a class="ec-btn ec-btn-primary" href="print.php">Cetak Data</a>
        </div>
        <div class="ec-table-wrap">
            <table class="ec-table">
                <thead><tr><th>NIK</th><th>Nama</th><th>Divisi</th><th>Level</th><th>Sisa Cuti</th></tr></thead>
                <tbody>
                <?php if ($query && mysqli_num_rows($query) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($query)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nik']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama']); ?></td>
                        <td><?php echo htmlspecialchars($row['divisi']); ?></td>
                        <td><?php echo htmlspecialchars($row['level']); ?></td>
                        <td><span class="ec-badge <?php echo ((int)($row['sisa_aktual'] ?? $row['sisacuti']) > 0) ? 'success' : 'danger'; ?>"><?php echo htmlspecialchars($row['sisa_aktual'] ?? $row['sisacuti']); ?> hari</span></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5" style="text-align:center;color:#64748b;">Data sisa cuti tidak ditemukan.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>
</body>
</html>
