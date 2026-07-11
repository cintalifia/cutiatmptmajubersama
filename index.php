<?php
session_start();
ob_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>E-Cuti PT Maju Bersama - Sistem Cuti Online</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
    <link href="css/sweetalert.css" rel="stylesheet">
    <script src="js/sweetalert-dev.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #0a1628 0%, #1a2a4a 40%, #0d1f3c 100%);
            padding: 20px;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(ellipse at 20% 50%, rgba(108, 140, 255, 0.08) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 50%, rgba(108, 140, 255, 0.05) 0%, transparent 60%),
                radial-gradient(ellipse at 50% 100%, rgba(108, 140, 255, 0.03) 0%, transparent 40%);
            z-index: 0;
        }

        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(1.5px 1.5px at 10% 20%, rgba(255,255,255,0.3), transparent),
                radial-gradient(1px 1px at 20% 60%, rgba(255,255,255,0.2), transparent),
                radial-gradient(1.5px 1.5px at 30% 10%, rgba(255,255,255,0.25), transparent),
                radial-gradient(1px 1px at 45% 80%, rgba(255,255,255,0.2), transparent),
                radial-gradient(1.5px 1.5px at 60% 30%, rgba(255,255,255,0.3), transparent),
                radial-gradient(1px 1px at 75% 70%, rgba(255,255,255,0.15), transparent),
                radial-gradient(1.5px 1.5px at 85% 15%, rgba(255,255,255,0.2), transparent),
                radial-gradient(1px 1px at 95% 50%, rgba(255,255,255,0.2), transparent);
            background-size: 100% 100%;
            background-repeat: no-repeat;
            z-index: 0;
            pointer-events: none;
        }

        .container {
            width: 100%;
            max-width: 1050px;
            display: flex;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.5);
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.97);
            min-height: 600px;
        }

        /* LEFT SIDE */
        .left-side {
            flex: 1.2;
            background: linear-gradient(145deg, rgba(10, 22, 40, 0.95) 0%, rgba(20, 45, 90, 0.92) 100%);
            padding: 60px 50px 50px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .left-side::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(108, 140, 255, 0.08) 0%, transparent 70%);
            border-radius: 50%;
        }

        .left-side::after {
            content: '';
            position: absolute;
            bottom: -20%;
            left: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(108, 140, 255, 0.05) 0%, transparent 70%);
            border-radius: 50%;
        }

        .left-side .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 35px;
            position: relative;
            z-index: 1;
        }

        .left-side .brand-icon {
            width: 48px;
            height: 48px;
            background: rgba(108, 140, 255, 0.15);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .left-side .brand-text {
            color: white;
            font-size: 19px;
            font-weight: 700;
            letter-spacing: 0.3px;
            line-height: 1.25;
        }

        .left-side .brand-text span {
            color: #6c8cff;
        }

        .left-side .brand-text small {
            display: block;
            color: rgba(255, 255, 255, 0.72);
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.2px;
            margin-top: 2px;
        }

        .left-side h1 {
            color: white;
            font-size: 36px;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }

        .left-side h1 span {
            color: #6c8cff;
        }

        .left-side .desc {
            color: rgba(255, 255, 255, 0.6);
            font-size: 15px;
            line-height: 1.7;
            margin-bottom: 35px;
            max-width: 90%;
            position: relative;
            z-index: 1;
        }

        .left-side .features {
            position: relative;
            z-index: 1;
        }

        .left-side .feature-item {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            padding: 8px 0;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        }

        .left-side .feature-item:last-child {
            border-bottom: none;
        }

        .left-side .feature-item::before {
            content: "✓";
            color: #6c8cff;
            font-weight: 700;
            font-size: 16px;
            background: rgba(108, 140, 255, 0.12);
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* RIGHT SIDE - LOGIN */
        .right-side {
            flex: 1;
            padding: 50px 45px 35px 45px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: white;
        }

        .right-side .login-header {
            margin-bottom: 18px;
            text-align: center;
        }

        .right-side .login-header h2 {
            font-size: 28px;
            font-weight: 700;
            color: #0a1628;
            margin-bottom: 2px;
        }

        .right-side .login-header p {
            display: none;
        }

        .form-group {
            margin-bottom: 8px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #1a2a4a;
            margin-bottom: 3px;
        }

        .form-group input {
            width: 100%;
            padding: 10px 14px;
            border: 2px solid #e8ecf1;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
            background: #f8f9fb;
            color: #0a1628;
        }

        .form-group input:focus {
            outline: none;
            border-color: #1e3a6f;
            background: white;
            box-shadow: 0 0 0 3px rgba(30, 58, 111, 0.06);
        }

        .form-group input::placeholder {
            color: #b8c4d4;
        }

        .login-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 10px 0 12px 0;
            font-size: 14px;
        }

        .login-options label {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #4a5a72;
            cursor: pointer;
        }

        .login-options label input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #1e3a6f;
            cursor: pointer;
        }

        .login-options a {
            color: #1e3a6f;
            text-decoration: none;
            font-weight: 600;
        }

        .login-options a:hover {
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #0a1628 0%, #1e3a6f 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            letter-spacing: 0.5px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(30, 58, 111, 0.3);
        }

        .btn-login:active {
            transform: scale(0.98);
        }

        .footer {
            text-align: center;
            margin-top: 16px;
            padding-top: 12px;
            border-top: 1px solid #e8ecf1;
            color: #b0b8c5;
            font-size: 13px;
        }

        .footer span {
            color: #6c8cff;
            font-weight: 600;
        }

        /* RESPONSIVE */
        @media (max-width: 850px) {
            .container {
                flex-direction: column;
                max-width: 480px;
                min-height: auto;
            }

            .left-side {
                padding: 35px 30px;
                min-height: auto;
            }

            .left-side h1 {
                font-size: 28px;
            }

            .left-side .desc {
                max-width: 100%;
            }

            .right-side {
                padding: 30px 30px 25px 30px;
                min-height: auto;
            }

            .right-side .login-header h2 {
                font-size: 24px;
            }
        }

        @media (max-width: 480px) {
            .left-side {
                padding: 25px 20px;
            }

            .left-side h1 {
                font-size: 22px;
            }

            .left-side .feature-item {
                font-size: 13px;
            }

            .right-side {
                padding: 20px 18px 18px 18px;
            }

            .login-options {
                flex-direction: column;
                gap: 8px;
                align-items: flex-start;
            }

            .container {
                border-radius: 14px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <!-- LEFT SIDE -->
    <div class="left-side">
        <div class="brand">
            <div class="brand-icon">📋</div>
            <div class="brand-text">E-<span>Cuti</span><small>PT Maju Bersama</small></div>
        </div>

        <h1>Selamat Datang di <span>E-Cuti PT Maju Bersama</span></h1>
        <p class="desc">
            Kelola pengajuan cuti dengan mudah, cepat dan terintegrasi dalam satu sistem.
        </p>

        <div class="features">
            <div class="feature-item">Pengajuan Cuti Online</div>
            <div class="feature-item">Persetujuan Berjenjang</div>
            <div class="feature-item">Monitoring Status Real-Time</div>
            <div class="feature-item">Riwayat Cuti Lengkap</div>
        </div>
    </div>

    <!-- RIGHT SIDE -->
    <div class="right-side">
        <div class="login-header">
            <h2>Login</h2>
        </div>

        <?php
        require_once 'function/koneksi.php';

        if (isset($_POST['submit'])) {
            $username = mysqli_real_escape_string($connect, trim($_POST['username'] ?? ''));
            $password = mysqli_real_escape_string($connect, trim($_POST['password'] ?? ''));

            $login = mysqli_query(
                $connect,
                "SELECT
                    userlogin.username,
                    karyawan.nik,
                    karyawan.nama,
                    karyawan.divisi,
                    karyawan.level
                 FROM userlogin
                 LEFT JOIN karyawan ON userlogin.username = karyawan.nik
                 WHERE userlogin.username='$username'
                 AND userlogin.password='$password'
                 LIMIT 1"
            );

            if ($login && mysqli_num_rows($login) > 0) {
                $data = mysqli_fetch_assoc($login);

                $nama   = $data['nama'] ?: ($username === 'admin' ? 'Administrator' : $username);
                $divisi = $data['divisi'] ?: ($username === 'admin' ? 'HRD' : '-');
                $level  = $data['level'] ?: ($username === 'admin' ? 'Administrator' : 'User');

                $_SESSION['username'] = $username;
                $_SESSION['status']   = 'login';
                $_SESSION['nama']     = $nama;
                $_SESSION['divisi']   = $divisi;
                $_SESSION['level']    = $level;

                $safeName = htmlspecialchars($nama, ENT_QUOTES, 'UTF-8');
                echo '<script type="text/javascript">swal("Login berhasil", "Selamat datang, ' . $safeName . '!", "success");</script>';
                echo '<meta http-equiv="refresh" content="1.1;url=dashboard/index.php">';
                exit;
            } else {
                echo '<script type="text/javascript">swal("Login Gagal", "Username atau password salah", "error");</script>';
            }
        }
        ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="admin" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="......" required
                       oninvalid="this.setCustomValidity('Masukkan password yang valid')">
            </div>

            <div class="login-options">
                <label>
                    <input type="checkbox" name="remember"> Ingat Saya
                </label>
                <a href="#">Lupa Password?</a>
            </div>

            <button type="submit" name="submit" class="btn-login">Masuk</button>
        </form>

        <div class="footer">
            © 2026 <span>E-Cuti PT Maju Bersama</span>. All rights reserved.
        </div>
    </div>
</div>

</body>
</html>
<?php ob_flush(); ?>