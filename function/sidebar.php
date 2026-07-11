<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$nama     = $_SESSION['nama'] ?? $_SESSION['username'] ?? 'User';
$username = $_SESSION['username'] ?? '-';
$level    = $_SESSION['level'] ?? 'User';
$divisi   = $_SESSION['divisi'] ?? '-';
if ($username === 'admin') {
    $nama = 'Administrator';
    $level = 'Administrator';
}
$current  = trim(str_replace('\\', '/', dirname($_SERVER['PHP_SELF'] ?? '')), '/');
$isAdmin  = ($username === 'admin');
$isApprover = $isAdmin;

function sidebar_active($needle, $current) {
    return strpos($current, trim($needle, '/')) !== false ? ' active' : '';
}

function sidebar_group_open($items, $current) {
    foreach ($items as $item) {
        if (strpos($current, trim($item, '/')) !== false) {
            return ' open';
        }
    }
    return '';
}
?>

<style>
:root{
    --ec-login-bg:#0a1628;
    --ec-login-bg-2:#1a2a4a;
    --ec-login-bg-3:#0d1f3c;
    --ec-accent:#6c8cff;
    --ec-accent-2:#8ea5ff;
    --ec-page-bg:#eef3fb;
    --ec-text:#162033;
    --ec-muted:#6b7a90;
    --ec-white:#ffffff;
    --ec-border:#e8ecf1;
    --ec-danger:#ef4444;
}
*,*::before,*::after{box-sizing:border-box;}
html{width:100%;overflow-x:hidden;}
body{
    margin:0;
    background:var(--ec-page-bg);
    color:var(--ec-text);
    font-family:'Poppins','Segoe UI',Arial,sans-serif;
    display:block;
    overflow-x:hidden;
}
.sidebar{
    width:310px;
    height:100vh;
    position:fixed;
    left:0;
    top:0;
    z-index:1000;
    color:#fff;
    overflow-y:auto;
    background:
        radial-gradient(circle at 18% 16%, rgba(108,140,255,.22), transparent 30%),
        radial-gradient(circle at 88% 82%, rgba(108,140,255,.12), transparent 30%),
        linear-gradient(160deg,var(--ec-login-bg) 0%,var(--ec-login-bg-2) 55%,var(--ec-login-bg-3) 100%);
    box-shadow:0 30px 70px rgba(10,22,40,.36);
}
.sidebar *{box-sizing:border-box;}
.sidebar::-webkit-scrollbar{width:7px;}
.sidebar::-webkit-scrollbar-thumb{background:rgba(255,255,255,.18);border-radius:999px;}
.sidebar .logo{
    padding:24px 24px 8px;
}
.sidebar .logo .brand-name{
    margin:0;
    font-size:36px;
    font-weight:800;
    letter-spacing:.2px;
    line-height:1.1;
}
.sidebar .logo .brand-name span{color:var(--ec-accent);}
.sidebar .logo .brand-subtitle{
    margin:6px 0 0;
    font-size:14px;
    color:rgba(255,255,255,.72);
    font-weight:500;
}
.sidebar .profile{
    margin:18px 18px 18px;
    padding:16px;
    border-radius:18px;
    background:rgba(255,255,255,.075);
    border:1px solid rgba(255,255,255,.08);
    box-shadow:inset 0 1px 0 rgba(255,255,255,.06), 0 18px 35px rgba(0,0,0,.14);
}
.sidebar .profile-circle{
    width:62px;
    height:62px;
    border-radius:16px;
    display:flex;
    align-items:center;
    justify-content:center;
    background:rgba(108,140,255,.18);
    color:#fff;
    border:1px solid rgba(108,140,255,.35);
    font-size:28px;
    font-weight:800;
    margin-bottom:12px;
}
.sidebar .profile h3{
    margin:0 0 4px;
    font-size:28px;
    line-height:1.25;
    font-weight:800;
    color:#fff;
}
.sidebar .profile p{
    margin:0;
    color:rgba(255,255,255,.72);
    font-size:13px;
    font-weight:700;
}
.sidebar .menu{
    list-style:none;
    padding:0 14px 24px;
    margin:0;
}
.sidebar .menu li{list-style:none;}
.sidebar .menu a{
    text-decoration:none;
    font-size:15px;
    font-weight:600;
    transition:.2s ease;
}
.sidebar .single-link,
.sidebar .menu-head{
    width:100%;
    min-height:46px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:10px;
    padding:12px 14px;
    color:rgba(255,255,255,.86);
    border-radius:12px;
    background:rgba(255,255,255,.06);
    border:1px solid rgba(255,255,255,.055);
    margin-bottom:8px;
}
.sidebar .single-link .left,
.sidebar .menu-head .left{
    display:flex;
    align-items:center;
    gap:10px;
}
.sidebar .single-link:hover,
.sidebar .single-link.active,
.sidebar .menu-section.open > .menu-head,
.sidebar .menu-head:hover{
    background:linear-gradient(90deg,rgba(108,140,255,.96),rgba(79,111,230,.92));
    color:#fff;
    border-color:rgba(255,255,255,.12);
    box-shadow:0 12px 24px rgba(108,140,255,.22);
}
.sidebar .chevron{
    font-size:11px;
    opacity:.75;
    transition:.2s ease;
}
.sidebar .menu-section.open .chevron{transform:rotate(90deg);}
.sidebar .submenu{
    list-style:none;
    padding:0;
    margin:-2px 0 10px;
    overflow:hidden;
    display:none;
    border-radius:12px;
    background:rgba(255,255,255,.035);
    border:1px solid rgba(255,255,255,.045);
}
.sidebar .menu-section.open .submenu{display:block;}
.sidebar .submenu a{
    display:flex;
    align-items:center;
    gap:10px;
    padding:11px 14px 11px 22px;
    color:rgba(255,255,255,.66);
    border-bottom:1px solid rgba(255,255,255,.04);
    background:rgba(255,255,255,.02);
}
.sidebar .submenu li:last-child a{border-bottom:0;}
.sidebar .submenu a::before{
    content:'▸';
    font-size:10px;
    color:rgba(255,255,255,.38);
}
.sidebar .submenu a:hover,
.sidebar .submenu a.active{
    color:#fff;
    background:rgba(108,140,255,.20);
    padding-left:26px;
}
.sidebar .submenu a.active::before{color:var(--ec-accent-2);}
.sidebar .logout-link{
    color:#fecaca!important;
    background:rgba(239,68,68,.12)!important;
}
.sidebar .logout-link:hover{
    background:rgba(239,68,68,.24)!important;
    box-shadow:none!important;
}
.content,
#content-wrapper,
.main-content{
    margin-left:310px;
    width:calc(100vw - 310px);
    max-width:calc(100vw - 310px);
    min-height:100vh;
    padding:30px;
    box-sizing:border-box;
    overflow-x:hidden;
}
.ec-page-header,
.header{
    background:#fff;
    border-radius:22px;
    padding:26px 30px;
    margin-bottom:24px;
    box-shadow:0 14px 30px rgba(10,22,40,.08);
    border:1px solid rgba(232,236,241,.95);
}
.ec-page-header h1,
.header h1{
    margin:0;
    color:#0a1628;
    font-size:30px;
    font-weight:800;
}
.ec-page-header p,
.header p{
    margin:6px 0 0;
    color:#7a8aa0;
}
.ec-card,
.table-box,
.form-box,
.card,
.form-card,
.table-card,
.stat-card{
    background:#fff;
    max-width:100%;
    border-radius:20px;
    box-shadow:0 12px 28px rgba(10,22,40,.08);
    border:1px solid rgba(232,236,241,.95);
}
.ec-toolbar,
.action-bar{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:14px;
    flex-wrap:wrap;
    margin-bottom:18px;
}
.ec-actions{display:flex;align-items:center;gap:10px;flex-wrap:wrap;}
.ec-input,
.search-box,
.form-control,
input[type='text'],
input[type='password'],
input[type='number'],
input[type='date'],
select,
textarea{
    border:1px solid #d7dde7;
    border-radius:12px;
    padding:11px 13px;
    outline:none;
    transition:.2s ease;
}
.ec-input:focus,
.search-box:focus,
.form-control:focus,
input:focus,
select:focus,
textarea:focus{
    border-color:#6c8cff;
    box-shadow:0 0 0 4px rgba(108,140,255,.14);
}
.btn-custom,
.ec-btn,
.btn{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:7px;
    padding:11px 16px;
    border-radius:12px;
    border:0;
    text-decoration:none;
    cursor:pointer;
    font-family:'Poppins','Segoe UI',Arial,sans-serif;
    font-size:14px;
    font-weight:800;
    line-height:1.2;
    min-height:44px;
    white-space:nowrap;
    transition:.2s ease;
}
.ec-btn-primary,
.btn-primary{
    background:linear-gradient(90deg,#1e3a6f,#6c8cff)!important;
    color:#fff!important;
}
.ec-btn-secondary{background:linear-gradient(90deg,#1e3a6f,#6c8cff)!important;color:#fff!important;}
.ec-btn-danger,
.btn-danger{background:#ef4444!important;color:#fff!important;}
.ec-btn-warning,
.btn-warning{background:#f59e0b!important;color:#fff!important;}
.ec-btn:hover,
.btn-custom:hover,
.btn:hover{
    transform:translateY(-1px);
    box-shadow:0 10px 20px rgba(108,140,255,.22);
}
.ec-table-wrap{width:100%;max-width:100%;overflow-x:auto;border-radius:14px;}
table.ec-table{width:100%;border-collapse:collapse;border-spacing:0;min-width:760px;}
.ec-table th{
    background:linear-gradient(90deg,#0a1628,#1a2a4a);
    color:#fff;
    padding:14px 12px;
    text-align:center;
    font-size:13px;
    border:1px solid #dbe3ef;
}
.ec-table td{padding:13px 12px;border:1px solid #e2e8f0;color:#334155;vertical-align:middle;text-align:center;}
.ec-table td .ec-actions{justify-content:center;}
.ec-table thead th{vertical-align:middle;}
.ec-table tr:hover td{background:#f8f9fb;}
.table th,.table td,.table-bordered th,.table-bordered td{border:1px solid #dbe3ef!important;text-align:center!important;vertical-align:middle!important;}
.content table{border-collapse:collapse;}
.content table th,.content table td{border:1px solid #dbe3ef;text-align:center;vertical-align:middle;}
.ec-badge{display:inline-flex;padding:6px 10px;border-radius:999px;font-size:12px;font-weight:700;}
.ec-badge.success{background:#dcfce7;color:#166534;}
.ec-badge.warning{background:#fef3c7;color:#92400e;}
.ec-badge.danger{background:#fee2e2;color:#991b1b;}
.ec-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:18px;margin-bottom:24px;}
.ec-grid.ec-grid-4{grid-template-columns:repeat(4,minmax(0,1fr));}
.ec-stat{padding:22px;min-height:120px;display:flex;flex-direction:column;justify-content:center;}
.ec-stat h2{margin:0;color:#1e3a6f;font-size:34px;}
.ec-stat p{margin:6px 0 0;color:#7a8aa0;}
@media(max-width:1200px){
    .ec-grid.ec-grid-4{grid-template-columns:repeat(2,minmax(0,1fr));}
}
@media(max-width:900px){
    .sidebar{position:relative;width:100%;height:auto;}
    .content,#content-wrapper,.main-content{margin-left:0;width:100%;max-width:100%;padding:18px;}
    .ec-grid,.ec-grid.ec-grid-4{grid-template-columns:1fr;}
    .ec-toolbar,.action-bar{align-items:stretch;}
    .ec-actions{width:100%;}
    .search-box{width:100%;}
}
</style>

<aside class="sidebar">
    <div class="logo">
        <div class="brand-name">E-<span>Cuti</span></div>
        <p class="brand-subtitle">PT Maju Bersama</p>
    </div>
    <div class="profile">
        <div class="profile-circle"><?php echo htmlspecialchars(strtoupper(substr($nama, 0, 1))); ?></div>
        <h3><?php echo htmlspecialchars($nama); ?></h3>
        <?php if (!$isAdmin && $divisi !== '-' && $level !== '-'): ?>
            <p><?php echo htmlspecialchars($level); ?> - <?php echo htmlspecialchars($divisi); ?></p>
        <?php endif; ?>
    </div>

    <ul class="menu">
        <li>
            <a class="single-link<?php echo sidebar_active('dashboard', $current); ?>" href="../dashboard/">
                <span class="left">🏠 <span>Dashboard</span></span>
            </a>
        </li>

        <?php if ($isAdmin): ?>
        <li class="menu-section<?php echo sidebar_group_open(['karyawan','jeniscuti'], $current); ?>">
            <a href="#" class="menu-head">
                <span class="left">📁 <span>Master</span></span>
                <span class="chevron">▶</span>
            </a>
            <ul class="submenu">
                <li><a class="<?php echo sidebar_active('karyawan', $current); ?>" href="../karyawan/">Tambah Pegawai</a></li>
                <li><a class="<?php echo sidebar_active('jeniscuti', $current); ?>" href="../jeniscuti/">Tambah Jenis Cuti</a></li>
            </ul>
        </li>
        <?php endif; ?>

        <li class="menu-section<?php echo sidebar_group_open(['pengajuancuti','cekpengajuancuti','jadwalcuti','sisacuti'], $current); ?>">
            <a href="#" class="menu-head">
                <span class="left">📅 <span>Cuti</span></span>
                <span class="chevron">▶</span>
            </a>
            <ul class="submenu">
                <li><a class="<?php echo sidebar_active('pengajuancuti', $current); ?>" href="../pengajuancuti/">Pengajuan Cuti</a></li>
                <li><a class="<?php echo sidebar_active('cekpengajuancuti', $current); ?>" href="../cekpengajuancuti/">Cek Pengajuan Cuti</a></li>
                <li><a class="<?php echo sidebar_active('jadwalcuti', $current); ?>" href="../jadwalcuti/">Lihat Jadwal Cuti</a></li>
                <li><a class="<?php echo sidebar_active('sisacuti', $current); ?>" href="../sisacuti/">Lihat Sisa Cuti</a></li>
            </ul>
        </li>

        <?php if ($isApprover): ?>
        <li class="menu-section<?php echo sidebar_group_open(['approval','proses','cetak.php'], $current); ?>">
            <a href="#" class="menu-head">
                <span class="left">✔ <span>Konfirmasi</span></span>
                <span class="chevron">▶</span>
            </a>
            <ul class="submenu">
                <li><a class="<?php echo sidebar_active('approval', $current); ?>" href="../approval/">Approval Cuti</a></li>
                <li><a class="<?php echo sidebar_active('proses', $current); ?>" href="../proses/">Proses Form Cuti</a></li>
            </ul>
        </li>
        <?php endif; ?>

        <li class="menu-section<?php echo sidebar_group_open(['gantipassword'], $current); ?>">
            <a href="#" class="menu-head">
                <span class="left">⚙ <span>Pengguna</span></span>
                <span class="chevron">▶</span>
            </a>
            <ul class="submenu">
                <li><a class="<?php echo sidebar_active('gantipassword', $current); ?>" href="../gantipassword/">Ganti Password</a></li>
                <li><a class="logout-link" href="../login/logout.php">Logout</a></li>
            </ul>
        </li>
    </ul>
</aside>

<script>
document.addEventListener('DOMContentLoaded', function(){
    var heads = document.querySelectorAll('.sidebar .menu-head');
    for (var i = 0; i < heads.length; i++) {
        heads[i].addEventListener('click', function(e){
            e.preventDefault();
            var section = this.parentNode;
            if (section.classList.contains('open')) {
                section.classList.remove('open');
            } else {
                var sections = document.querySelectorAll('.sidebar .menu-section');
                for (var j = 0; j < sections.length; j++) {
                    sections[j].classList.remove('open');
                }
                section.classList.add('open');
            }
        });
    }
});
</script>
