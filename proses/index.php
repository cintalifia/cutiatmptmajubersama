<?php
session_start();
require '../function/koneksi.php';

if (!isset($_SESSION['username'])) {
    header('Location: ../index.php');
    exit;
}

$isAdmin = ($_SESSION['username'] === 'admin');
if (!$isAdmin) {
    header('Location: ../dashboard/');
    exit;
}

$pesan = '';
$error = '';

function proses_pengurangan_sisa_cuti($connect, $idPengajuan) {
    $id = mysqli_real_escape_string($connect, $idPengajuan);
    $q = mysqli_query($connect, "
        SELECT p.*, k.sisacuti
        FROM pengajuancuti p
        LEFT JOIN karyawan k ON p.nik = k.nik
        WHERE p.idpengajuancuti='$id'
        LIMIT 1
    ");
    $pengajuan = $q ? mysqli_fetch_assoc($q) : null;

    if (!$pengajuan) {
        return 'Data pengajuan tidak ditemukan.';
    }

    if ($pengajuan['status'] === 'Success') {
        return 'Pengajuan ini sudah diproses sebelumnya, jadi sisa cuti tidak dipotong lagi.';
    }

    if (!in_array($pengajuan['status'], ['Approved','Approve','Diterima','DITERIMA'], true)) {
        return 'Pengajuan belum disetujui, jadi belum bisa diproses.';
    }

    $nik = mysqli_real_escape_string($connect, $pengajuan['nik']);
    $cekPegawai = mysqli_query($connect, "SELECT nik FROM karyawan WHERE nik='$nik' LIMIT 1");
    if (!$cekPegawai || mysqli_num_rows($cekPegawai) < 1) {
        return 'Data pegawai tidak ditemukan, sisa cuti belum bisa dikurangi.';
    }

    $lamaCuti = (int) $pengajuan['lamacuti'];
    $updateSisa = mysqli_query($connect, "
        UPDATE karyawan
        SET sisacuti = GREATEST(sisacuti - $lamaCuti, 0)
        WHERE nik='$nik'
    ");
    if (!$updateSisa) {
        return 'Sisa cuti gagal dikurangi: ' . mysqli_error($connect);
    }

    $status = mysqli_query($connect, "UPDATE pengajuancuti SET status='Success' WHERE idpengajuancuti='$id'");
    if (!$status) {
        return 'Status pengajuan gagal diperbarui: ' . mysqli_error($connect);
    }

    return '';
}

if (isset($_GET['approve']) || isset($_GET['proses'])) {
    $id = $_GET['approve'] ?? $_GET['proses'];
    $error = proses_pengurangan_sisa_cuti($connect, $id);
    if ($error === '') {
        header('Location: index.php?pesan=success');
        exit;
    }
}

if (isset($_GET['pesan']) && $_GET['pesan'] === 'success') {
    $pesan = 'Cuti berhasil di-approve. Sisa cuti pegawai sudah berkurang.';
}

$query = mysqli_query($connect, "
    SELECT p.*, k.nama, k.divisi, j.jeniscuti
    FROM pengajuancuti p
    LEFT JOIN karyawan k ON p.nik = k.nik
    LEFT JOIN jeniscuti j ON p.idcuti = j.idcuti
    WHERE p.status IN ('Approved','Approve','Diterima','DITERIMA')
    ORDER BY p.tanggalmulai ASC
");
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Proses Form Cuti | E-Cuti PT Maju Bersama</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>.alert{padding:13px 15px;border-radius:13px;margin-bottom:16px;font-weight:600;}.alert-success{background:#dcfce7;color:#166534;}.alert-danger{background:#fee2e2;color:#991b1b;}</style>
</head>
<body>
<?php include '../function/sidebar.php'; ?>
<main class="content">
    <section class="ec-page-header">
        <h1>Proses Form Cuti</h1>
        <p>Approve form cuti yang sudah masuk jadwal, lalu sistem akan mengurangi sisa cuti pegawai.</p>
    </section>
    <?php if ($pesan): ?><div class="alert alert-success"><?php echo htmlspecialchars($pesan); ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

    <section class="ec-card" style="padding:24px;">
        <div class="ec-toolbar">
            <div>
                <h2 style="margin:0;font-size:22px;">Daftar Form Cuti Siap Approve</h2>
                <p style="margin:6px 0 0;color:#64748b;">Klik approve untuk mengubah status menjadi Success dan memotong sisa cuti sesuai lama cuti yang diajukan.</p>
            </div>
        </div>
        <div class="ec-table-wrap">
            <table class="ec-table">
                <thead><tr><th>ID</th><th>NIK</th><th>Nama</th><th>Jenis Cuti</th><th>Tanggal Mulai</th><th>Lama</th><th>Alasan</th><th>Aksi</th></tr></thead>
                <tbody>
                <?php if ($query && mysqli_num_rows($query) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($query)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['idpengajuancuti']); ?></td>
                        <td><?php echo htmlspecialchars($row['nik']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama'] ?? '-'); ?></td>
                        <td><?php echo htmlspecialchars($row['jeniscuti'] ?? $row['idcuti']); ?></td>
                        <td><?php echo htmlspecialchars($row['tanggalmulai']); ?></td>
                        <td><?php echo htmlspecialchars($row['lamacuti']); ?> hari</td>
                        <td><?php echo htmlspecialchars($row['alasancuti']); ?></td>
                        <td><a class="ec-btn ec-btn-primary" href="index.php?approve=<?php echo urlencode($row['idpengajuancuti']); ?>" onclick="return confirm('Approve cuti ini dan kurangi sisa cuti pegawai?')">Approve</a></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="8" style="text-align:center;color:#64748b;">Tidak ada cuti yang perlu diproses.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
    
</main>
</body>
</html>
