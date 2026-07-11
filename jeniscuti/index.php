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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idLama = mysqli_real_escape_string($connect, $_POST['id_lama'] ?? '');
    $idcuti = mysqli_real_escape_string($connect, trim($_POST['idcuti'] ?? ''));
    $nama   = mysqli_real_escape_string($connect, trim($_POST['jeniscuti'] ?? ''));

    if ($idcuti === '' || $nama === '') {
        $error = 'ID cuti dan nama jenis cuti wajib diisi.';
    } else {
        if ($idLama !== '') {
            $ok = mysqli_query($connect, "UPDATE jeniscuti SET idcuti='$idcuti', jeniscuti='$nama' WHERE idcuti='$idLama'");
        } else {
            $cek = mysqli_query($connect, "SELECT idcuti FROM jeniscuti WHERE idcuti='$idcuti' LIMIT 1");
            if ($cek && mysqli_num_rows($cek) > 0) {
                $ok = false;
                $error = 'ID cuti sudah terdaftar.';
            } else {
                $ok = mysqli_query($connect, "INSERT INTO jeniscuti (idcuti, jeniscuti) VALUES ('$idcuti', '$nama')");
            }
        }

        if (isset($ok) && $ok) {
            header('Location: index.php?pesan=simpan');
            exit;
        } elseif ($error === '') {
            $error = 'Data gagal disimpan: ' . mysqli_error($connect);
        }
    }
}

if (isset($_GET['hapus'])) {
    $hapus = mysqli_real_escape_string($connect, $_GET['hapus']);
    mysqli_query($connect, "DELETE FROM jeniscuti WHERE idcuti='$hapus'");
    header('Location: index.php?pesan=hapus');
    exit;
}

if (isset($_GET['pesan'])) {
    $pesan = $_GET['pesan'] === 'hapus' ? 'Jenis cuti berhasil dihapus.' : 'Jenis cuti berhasil disimpan.';
}

$edit = null;
if (isset($_GET['edit'])) {
    $idEdit = mysqli_real_escape_string($connect, $_GET['edit']);
    $qEdit = mysqli_query($connect, "SELECT * FROM jeniscuti WHERE idcuti='$idEdit' LIMIT 1");
    $edit = $qEdit ? mysqli_fetch_assoc($qEdit) : null;
}
$showForm = $edit || isset($_GET['show_form']) || $error !== '';

$qKode = mysqli_query($connect, "SELECT MAX(idcuti) AS kode FROM jeniscuti WHERE idcuti LIKE 'CT%'");
$dKode = $qKode ? mysqli_fetch_assoc($qKode) : null;
$nextKode = 'CT001';
if (!$edit && !empty($dKode['kode'])) {
    $nextKode = 'CT' . str_pad(((int) substr($dKode['kode'], 2)) + 1, 3, '0', STR_PAD_LEFT);
}

$query = mysqli_query($connect, "SELECT * FROM jeniscuti ORDER BY idcuti ASC");
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jenis Cuti | E-Cuti PT Maju Bersama</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .jenis-page{display:flex;flex-direction:column;gap:22px;}
        .jenis-form-wrap{position:fixed;top:0;right:0;bottom:0;left:310px;display:flex;align-items:center;justify-content:center;padding:24px;background:rgba(15,23,42,.38);z-index:2000;overflow:auto;}
        .form-card{width:min(520px,100%);padding:26px;max-height:calc(100vh - 60px);overflow:auto;}
        .table-card{padding:24px;}
        .form-card h2{margin:0 0 16px;font-size:22px;text-align:center;}
        .form-group{margin-bottom:14px;}
        .form-group label{display:block;margin-bottom:6px;font-weight:700;color:#334155;font-size:13px;}
        .form-group input{width:100%;}
        .alert{padding:13px 15px;border-radius:13px;margin-bottom:16px;font-weight:600;}
        .alert-success{background:#dcfce7;color:#166534;}
        .alert-danger{background:#fee2e2;color:#991b1b;}
        .form-button-row{justify-content:flex-end;gap:10px;flex-wrap:nowrap;margin-top:16px;}
        .form-button-row .ec-btn{min-width:110px;height:42px;font-weight:800;padding:0 18px;}
    @media(max-width:900px){.jenis-form-wrap{left:0;}}
    </style>
</head>
<body>
<?php include '../function/sidebar.php'; ?>
<main class="content">
    <section class="ec-page-header">
        <h1>Jenis Cuti</h1>
        <p>Kelola master jenis cuti yang digunakan pada form pengajuan.</p>
    </section>
    <?php if ($pesan): ?><div class="alert alert-success"><?php echo htmlspecialchars($pesan); ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

    <section class="jenis-page">
        <?php if ($showForm): ?>
        <div class="jenis-form-wrap">
            <div class="ec-card form-card" id="formJenisCuti">
                <h2><?php echo $edit ? 'Edit Jenis Cuti' : 'Tambah Jenis Cuti'; ?></h2>
                <form method="post" action="index.php<?php echo $edit ? '?edit='.urlencode($edit['idcuti']) : '?show_form=1'; ?>">
                    <input type="hidden" name="id_lama" value="<?php echo htmlspecialchars($edit['idcuti'] ?? ''); ?>">
                    <div class="form-group">
                        <label>ID Cuti</label>
                        <input class="ec-input" type="text" name="idcuti" required value="<?php echo htmlspecialchars($edit['idcuti'] ?? ($_POST['idcuti'] ?? $nextKode)); ?>">
                    </div>
                    <div class="form-group">
                        <label>Nama Jenis Cuti</label>
                        <input class="ec-input" type="text" name="jeniscuti" required value="<?php echo htmlspecialchars($edit['jeniscuti'] ?? ($_POST['jeniscuti'] ?? '')); ?>">
                    </div>
                    <div class="ec-actions form-button-row">
                        <button class="ec-btn ec-btn-primary" type="submit">Simpan</button>
                        <a class="ec-btn ec-btn-secondary" href="index.php">Batal</a>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>
        <div class="ec-card table-card">
            <div class="ec-toolbar">
                <div>
                    <h2 style="margin:0;font-size:22px;">Daftar Jenis Cuti</h2>
                    <p style="margin:6px 0 0;color:#64748b;">Data master yang tersedia.</p>
                </div>
                <div class="ec-actions">
                    <a class="ec-btn ec-btn-primary" href="print.php">Cetak Data</a>
                    <a class="ec-btn ec-btn-primary" href="index.php?show_form=1">+ Tambah Jenis Cuti</a>
                </div>
            </div>
            <div class="ec-table-wrap">
                <table class="ec-table">
                    <thead><tr><th>ID Cuti</th><th>Jenis Cuti</th><th>Aksi</th></tr></thead>
                    <tbody>
                    <?php if ($query && mysqli_num_rows($query) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($query)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['idcuti']); ?></td>
                            <td><?php echo htmlspecialchars($row['jeniscuti']); ?></td>
                            <td><div class="ec-actions"><a class="ec-btn ec-btn-warning" href="index.php?edit=<?php echo urlencode($row['idcuti']); ?>">Edit</a><a class="ec-btn ec-btn-danger" href="index.php?hapus=<?php echo urlencode($row['idcuti']); ?>" onclick="return confirm('Yakin ingin menghapus jenis cuti ini?')">Hapus</a></div></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="3" style="text-align:center;color:#64748b;">Belum ada data jenis cuti.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>
</body>
</html>
