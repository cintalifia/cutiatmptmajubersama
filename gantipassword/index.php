<?php
session_start();
require '../function/koneksi.php';

if (!isset($_SESSION['username'])) {
    header('Location: ../index.php');
    exit;
}

$pesan = '';
$error = '';
$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lama = mysqli_real_escape_string($connect, $_POST['password_lama'] ?? '');
    $baru = mysqli_real_escape_string($connect, $_POST['password_baru'] ?? '');
    $konfirmasi = mysqli_real_escape_string($connect, $_POST['konfirmasi_password'] ?? '');
    $safeUser = mysqli_real_escape_string($connect, $username);

    if ($baru === '' || $konfirmasi === '') {
        $error = 'Password baru dan konfirmasi wajib diisi.';
    } elseif ($baru !== $konfirmasi) {
        $error = 'Konfirmasi password tidak sama.';
    } else {
        $cek = mysqli_query($connect, "SELECT * FROM userlogin WHERE username='$safeUser' AND password='$lama' LIMIT 1");
        if (!$cek || mysqli_num_rows($cek) === 0) {
            $error = 'Password lama salah.';
        } else {
            $ok = mysqli_query($connect, "UPDATE userlogin SET password='$baru' WHERE username='$safeUser'");
            if ($ok) {
                $pesan = 'Password berhasil diperbarui.';
            } else {
                $error = 'Password gagal diperbarui: ' . mysqli_error($connect);
            }
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ganti Password | e-Cuti</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .password-card{max-width:560px;padding:26px;}.form-group{margin-bottom:16px;}.form-group label{display:block;margin-bottom:6px;font-weight:700;color:#334155;font-size:13px;}.form-group input{width:100%;}
        .alert{padding:13px 15px;border-radius:13px;margin-bottom:16px;font-weight:600;}.alert-success{background:#dcfce7;color:#166534;}.alert-danger{background:#fee2e2;color:#991b1b;}
    </style>
</head>
<body>
<?php include '../function/sidebar.php'; ?>
<main class="content">
    <section class="ec-page-header">
        <h1>Ganti Password</h1>
        <p>Perbarui password akun e-Cuti Anda secara berkala.</p>
    </section>
    <?php if ($pesan): ?><div class="alert alert-success"><?php echo htmlspecialchars($pesan); ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

    <section class="ec-card password-card">
        <form method="post" action="index.php">
            <div class="form-group">
                <label>Username</label>
                <input class="ec-input" type="text" value="<?php echo htmlspecialchars($username); ?>" readonly>
            </div>
            <div class="form-group">
                <label>Password Lama</label>
                <input class="ec-input" type="password" name="password_lama" required>
            </div>
            <div class="form-group">
                <label>Password Baru</label>
                <input class="ec-input" type="password" name="password_baru" required>
            </div>
            <div class="form-group">
                <label>Konfirmasi Password Baru</label>
                <input class="ec-input" type="password" name="konfirmasi_password" required>
            </div>
            <button class="ec-btn ec-btn-primary" type="submit">💾 Simpan Password</button>
        </form>
    </section>
</main>
</body>
</html>
