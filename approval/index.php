<?php
session_start();
require '../function/koneksi.php';

if (!isset($_SESSION['username'])) {
    header('Location: ../index.php');
    exit;
}

$username = $_SESSION['username'];
$nama  = $_SESSION['nama'] ?? $username;
$level = $_SESSION['level'] ?? 'User';
$divisi = $_SESSION['divisi'] ?? '-';
$isAdmin = ($username === 'admin');
$isApprover = $isAdmin;
if (!$isApprover) {
    header('Location: ../dashboard/');
    exit;
}

$pesan = '';
$error = '';

function buat_id_approval($connect) {
    $q = mysqli_query($connect, "SELECT MAX(idapprovecuti) AS kode FROM approvecuti");
    $d = $q ? mysqli_fetch_assoc($q) : null;
    $urut = !empty($d['kode']) ? ((int) substr($d['kode'], 2)) + 1 : 1;
    return 'AP' . str_pad($urut, 3, '0', STR_PAD_LEFT);
}

if (isset($_GET['approve']) || isset($_GET['reject'])) {
    $isApprove = isset($_GET['approve']);
    $id = mysqli_real_escape_string($connect, $isApprove ? $_GET['approve'] : $_GET['reject']);
    $q = mysqli_query($connect, "SELECT * FROM pengajuancuti WHERE idpengajuancuti='$id' LIMIT 1");
    $pengajuan = $q ? mysqli_fetch_assoc($q) : null;

    if (!$pengajuan) {
        $error = 'Data pengajuan tidak ditemukan.';
    } else {
        if ($isApprove) {
            $idApprove = buat_id_approval($connect);
            $approveBy = mysqli_real_escape_string($connect, $nama);
            $tanggal = date('Y-m-d');
            mysqli_query($connect, "INSERT INTO approvecuti (idapprovecuti,idpengajuancuti,tanggalapprove,approveby) VALUES ('$idApprove','$id','$tanggal','$approveBy')");
            $ok = mysqli_query($connect, "UPDATE pengajuancuti SET status='Approved' WHERE idpengajuancuti='$id'");
            $msg = 'Pengajuan cuti berhasil disetujui.';
        } else {
            $ok = mysqli_query($connect, "UPDATE pengajuancuti SET status='Rejected' WHERE idpengajuancuti='$id'");
            $msg = 'Pengajuan cuti berhasil ditolak.';
        }

        if ($ok) {
            header('Location: index.php?pesan=' . urlencode($msg));
            exit;
        }
        $error = 'Proses approval gagal: ' . mysqli_error($connect);
    }
}

if (isset($_GET['pesan'])) {
    $pesan = $_GET['pesan'];
}

$jmlPending = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM pengajuancuti WHERE status='Pending'"));
$jmlApprove = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM pengajuancuti WHERE status IN ('Approved','Success')"));
$jmlReject  = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM pengajuancuti WHERE status='Rejected'"));

$where = "p.status='Pending'";

$query = mysqli_query($connect, "
    SELECT p.*, k.nama, k.divisi, k.level, j.jeniscuti
    FROM pengajuancuti p
    LEFT JOIN karyawan k ON p.nik = k.nik
    LEFT JOIN jeniscuti j ON p.idcuti = j.idcuti
    WHERE $where
    ORDER BY p.tanggalpengajuan DESC
");
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Approval Cuti | e-Cuti</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>.alert{padding:13px 15px;border-radius:13px;margin-bottom:16px;font-weight:600;}.alert-success{background:#dcfce7;color:#166534;}.alert-danger{background:#fee2e2;color:#991b1b;}</style>
</head>
<body>
<?php include '../function/sidebar.php'; ?>
<main class="content">
    <section class="ec-page-header">
        <h1>Approval Cuti</h1>
        <p>Administrator · Persetujuan dan penolakan pengajuan cuti.</p>
    </section>

    <?php if ($pesan): ?><div class="alert alert-success"><?php echo htmlspecialchars($pesan); ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

    <section class="ec-grid">
        <div class="ec-card ec-stat"><h2><?php echo $jmlPending; ?></h2><p>Menunggu Approval</p></div>
        <div class="ec-card ec-stat"><h2><?php echo $jmlApprove; ?></h2><p>Disetujui</p></div>
        <div class="ec-card ec-stat"><h2><?php echo $jmlReject; ?></h2><p>Ditolak</p></div>
    </section>

    <section class="ec-card" style="padding:24px;">
        <div class="ec-toolbar">
            <div>
                <h2 style="margin:0;font-size:22px;">Pengajuan Pending</h2>
                <p style="margin:6px 0 0;color:#64748b;">Pilih approve atau reject untuk memproses data.</p>
            </div>
            <a class="ec-btn ec-btn-primary" href="approval.print.php">Cetak Data</a>
        </div>
        <div class="ec-table-wrap">
            <table class="ec-table">
                <thead><tr><th>ID</th><th>NIK</th><th>Nama</th><th>Divisi</th><th>Jenis Cuti</th><th>Tanggal Mulai</th><th>Lama</th><th>Alasan</th><th>Aksi</th></tr></thead>
                <tbody>
                <?php if ($query && mysqli_num_rows($query) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($query)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['idpengajuancuti']); ?></td>
                        <td><?php echo htmlspecialchars($row['nik']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama'] ?? '-'); ?></td>
                        <td><?php echo htmlspecialchars($row['divisi'] ?? '-'); ?></td>
                        <td><?php echo htmlspecialchars($row['jeniscuti'] ?? $row['idcuti']); ?></td>
                        <td><?php echo htmlspecialchars($row['tanggalmulai']); ?></td>
                        <td><?php echo htmlspecialchars($row['lamacuti']); ?> hari</td>
                        <td><?php echo htmlspecialchars($row['alasancuti']); ?></td>
                        <td><div class="ec-actions"><a class="ec-btn ec-btn-primary" href="index.php?approve=<?php echo urlencode($row['idpengajuancuti']); ?>" onclick="return confirm('Setujui pengajuan ini?')">Approve</a><a class="ec-btn ec-btn-danger" href="index.php?reject=<?php echo urlencode($row['idpengajuancuti']); ?>" onclick="return confirm('Tolak pengajuan ini?')">Reject</a></div></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="9" style="text-align:center;color:#64748b;">Tidak ada pengajuan pending.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>
</body>
</html>
