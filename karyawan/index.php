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
    $aksi       = $_POST['aksi'] ?? '';
    $nikLama    = mysqli_real_escape_string($connect, $_POST['nik_lama'] ?? '');
    $nik        = mysqli_real_escape_string($connect, trim($_POST['nik'] ?? ''));
    $nama       = mysqli_real_escape_string($connect, trim($_POST['nama'] ?? ''));
    $divisi     = mysqli_real_escape_string($connect, trim($_POST['divisi'] ?? ''));
    $level      = mysqli_real_escape_string($connect, trim($_POST['level'] ?? 'Staff'));
    $sisacuti   = (int) ($_POST['sisacuti'] ?? 0);

    if ($nik === '' || $nama === '' || $divisi === '') {
        $error = 'NIK, nama, dan divisi wajib diisi.';
    } elseif ($aksi === 'simpan') {
        if ($nikLama !== '') {
            $sql = "UPDATE karyawan SET nik='$nik', nama='$nama', divisi='$divisi', level='$level', sisacuti='$sisacuti' WHERE nik='$nikLama'";
            $ok = mysqli_query($connect, $sql);
            if ($ok && $nikLama !== $nik) {
                mysqli_query($connect, "UPDATE userlogin SET username='$nik' WHERE username='$nikLama'");
            }
        } else {
            $cek = mysqli_query($connect, "SELECT nik FROM karyawan WHERE nik='$nik' LIMIT 1");
            if ($cek && mysqli_num_rows($cek) > 0) {
                $ok = false;
                $error = 'NIK sudah terdaftar.';
            } else {
                $ok = mysqli_query($connect, "INSERT INTO karyawan (nik,nama,divisi,level,sisacuti) VALUES ('$nik','$nama','$divisi','$level','$sisacuti')");
                if ($ok) {
                    mysqli_query($connect, "INSERT INTO userlogin (username,password) VALUES ('$nik','123456')");
                }
            }
        }

        if (!isset($ok) || !$ok) {
            if ($error === '') {
                $error = 'Data gagal disimpan: ' . mysqli_error($connect);
            }
        } else {
            header('Location: index.php?pesan=simpan');
            exit;
        }
    }
}

if (isset($_GET['hapus'])) {
    $hapus = mysqli_real_escape_string($connect, $_GET['hapus']);
    mysqli_query($connect, "DELETE FROM karyawan WHERE nik='$hapus'");
    mysqli_query($connect, "DELETE FROM userlogin WHERE username='$hapus'");
    header('Location: index.php?pesan=hapus');
    exit;
}

if (isset($_GET['pesan'])) {
    $pesan = $_GET['pesan'] === 'hapus' ? 'Data pegawai berhasil dihapus.' : 'Data pegawai berhasil disimpan.';
}

$edit = null;
if (isset($_GET['edit'])) {
    $idEdit = mysqli_real_escape_string($connect, $_GET['edit']);
    $qEdit = mysqli_query($connect, "SELECT * FROM karyawan WHERE nik='$idEdit' LIMIT 1");
    $edit = $qEdit ? mysqli_fetch_assoc($qEdit) : null;
}
$showForm = $edit || isset($_GET['show_form']) || $error !== '';

$keyword = mysqli_real_escape_string($connect, trim($_GET['keyword'] ?? ''));
$where = $keyword !== '' ? "WHERE k.nik LIKE '%$keyword%' OR k.nama LIKE '%$keyword%' OR k.divisi LIKE '%$keyword%' OR k.level LIKE '%$keyword%'" : '';
$query = mysqli_query($connect, "
    SELECT k.*, k.sisacuti AS sisa_aktual
    FROM karyawan k
    $where
    ORDER BY k.nama ASC
");
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Pegawai | E-Cuti PT Maju Bersama</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .pegawai-page{display:flex;flex-direction:column;gap:22px;}
        .pegawai-form-wrap{position:fixed;top:0;right:0;bottom:0;left:310px;display:flex;align-items:center;justify-content:center;padding:24px;background:rgba(15,23,42,.38);z-index:2000;overflow:auto;}
        .pegawai-form{width:min(560px,100%);padding:26px;max-height:calc(100vh - 60px);overflow:auto;}
        .pegawai-form h2{margin:0 0 16px;font-size:22px;text-align:center;}
        .form-group{margin-bottom:14px;}
        .form-group label{display:block;margin-bottom:6px;font-weight:700;color:#334155;font-size:13px;}
        .form-group input,.form-group select{width:100%;}
        .alert{padding:13px 15px;border-radius:13px;margin-bottom:16px;font-weight:600;}
        .alert-success{background:#dcfce7;color:#166534;}
        .alert-danger{background:#fee2e2;color:#991b1b;}
        .table-section{padding:24px;}
        .pegawai-search{min-width:340px;width:min(420px,100%);height:46px;}
        .search-controls .ec-btn{min-width:112px;height:46px;padding:0 18px;font-weight:800;font-size:14px;font-family:'Poppins','Segoe UI',Arial,sans-serif;}
        .toolbar-right .ec-btn{height:46px;padding:0 18px;}
        .form-button-row{justify-content:flex-end;gap:10px;flex-wrap:nowrap;margin-top:16px;}
        .form-button-row .ec-btn{min-width:110px;height:42px;font-weight:800;padding:0 18px;}
        .toolbar-left{flex:1;}
        .toolbar-right{display:flex;align-items:center;gap:10px;flex-wrap:wrap;}
        @media(max-width:900px){.pegawai-form-wrap{left:0;}.pegawai-search{min-width:100%;}.toolbar-left,.toolbar-right{width:100%;}.toolbar-right .ec-btn{flex:1;}}
    </style>
</head>
<body>
<?php include '../function/sidebar.php'; ?>

<main class="content">
    <section class="ec-page-header">
        <h1>Data Pegawai</h1>
        <p>Tambah, ubah, cari, dan hapus data pegawai untuk sistem E-Cuti PT Maju Bersama.</p>
    </section>

    <?php if ($pesan): ?><div class="alert alert-success"><?php echo htmlspecialchars($pesan); ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

    <section class="pegawai-page">
        <?php if ($showForm): ?>
        <div class="pegawai-form-wrap">
            <div class="ec-card pegawai-form" id="formPegawai">
                <h2><?php echo $edit ? 'Edit Pegawai' : 'Tambah Pegawai'; ?></h2>
                <form method="post" action="index.php<?php echo $edit ? '?edit='.urlencode($edit['nik']) : '?show_form=1'; ?>">
                    <input type="hidden" name="aksi" value="simpan">
                    <input type="hidden" name="nik_lama" value="<?php echo htmlspecialchars($edit['nik'] ?? ''); ?>">
                    <div class="form-group">
                        <label>NIK / NIP</label>
                        <input class="ec-input" type="text" name="nik" required value="<?php echo htmlspecialchars($edit['nik'] ?? ($_POST['nik'] ?? '')); ?>">
                    </div>
                    <div class="form-group">
                        <label>Nama</label>
                        <input class="ec-input" type="text" name="nama" required value="<?php echo htmlspecialchars($edit['nama'] ?? ($_POST['nama'] ?? '')); ?>">
                    </div>
                    <div class="form-group">
                        <label>Divisi</label>
                        <input class="ec-input" type="text" name="divisi" required value="<?php echo htmlspecialchars($edit['divisi'] ?? ($_POST['divisi'] ?? '')); ?>">
                    </div>
                    <div class="form-group">
                        <label>Level</label>
                        <?php $selectedLevel = $edit['level'] ?? ($_POST['level'] ?? 'Staff'); ?>
                        <select class="ec-input" name="level">
                            <?php foreach (['Staff','Manager','General Manager','Direktur','Admin'] as $lvl): ?>
                                <option value="<?php echo $lvl; ?>" <?php echo $selectedLevel === $lvl ? 'selected' : ''; ?>><?php echo $lvl; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Sisa Cuti</label>
                        <input class="ec-input" type="number" name="sisacuti" min="0" value="<?php echo htmlspecialchars($edit['sisacuti'] ?? ($_POST['sisacuti'] ?? '12')); ?>">
                    </div>
                    <div class="ec-actions form-button-row">
                        <button class="ec-btn ec-btn-primary" type="submit">Simpan</button>
                        <a class="ec-btn ec-btn-secondary" href="index.php">Batal</a>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <div class="ec-card table-section">
            <div class="ec-toolbar">
                <form class="ec-actions toolbar-left search-controls" method="get" action="index.php">
                    <input class="search-box pegawai-search" type="text" name="keyword" placeholder="Cari pegawai..." value="<?php echo htmlspecialchars($keyword); ?>">
                    <button class="ec-btn ec-btn-primary" type="submit">Cari</button>
                    <a class="ec-btn ec-btn-primary" href="index.php">Refresh</a>
                </form>
                <div class="toolbar-right">
                    <a class="ec-btn ec-btn-primary" href="print.php">Cetak Data</a>
                    <a class="ec-btn ec-btn-primary" href="index.php?show_form=1">+ Tambah Pegawai</a>
                </div>
            </div>
            <div class="ec-table-wrap">
                <table class="ec-table">
                    <thead>
                    <tr>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Divisi</th>
                        <th>Level</th>
                        <th>Sisa Cuti</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($query && mysqli_num_rows($query) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($query)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nik']); ?></td>
                            <td><?php echo htmlspecialchars($row['nama']); ?></td>
                            <td><?php echo htmlspecialchars($row['divisi']); ?></td>
                            <td><?php echo htmlspecialchars($row['level']); ?></td>
                            <td><span class="ec-badge <?php echo ((int)($row['sisa_aktual'] ?? $row['sisacuti']) > 0) ? 'success' : 'danger'; ?>"><?php echo htmlspecialchars($row['sisa_aktual'] ?? $row['sisacuti']); ?> hari</span></td>
                            <td>
                                <div class="ec-actions">
                                    <a class="ec-btn ec-btn-warning" href="index.php?edit=<?php echo urlencode($row['nik']); ?>">Edit</a>
                                    <a class="ec-btn ec-btn-danger" href="index.php?hapus=<?php echo urlencode($row['nik']); ?>" onclick="return confirm('Yakin ingin menghapus pegawai ini?')">Hapus</a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6" style="text-align:center;color:#64748b;">Data pegawai tidak ditemukan.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>
</body>
</html>
