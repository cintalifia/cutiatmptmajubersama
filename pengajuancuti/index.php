<?php
session_start();

require '../function/koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

koneksi_buka();

$nik   = $_SESSION['username'];
$nama  = isset($_SESSION['nama']) ? $_SESSION['nama'] : $_SESSION['username'];
$level = isset($_SESSION['level']) ? $_SESSION['level'] : 'User';

$isAdmin = ($nik === 'admin');
$whereCard = $isAdmin ? '1=1' : "nik='" . mysqli_real_escape_string($connect, $nik) . "'";

$jmlPengajuan = mysqli_num_rows(
    mysqli_query(
        $connect,
        "SELECT * FROM pengajuancuti WHERE $whereCard"
    )
);

$jmlPending = mysqli_num_rows(
    mysqli_query(
        $connect,
        "SELECT * FROM pengajuancuti
         WHERE $whereCard
         AND LOWER(status)='pending'"
    )
);

$jmlApprove = mysqli_num_rows(
    mysqli_query(
        $connect,
        "SELECT * FROM pengajuancuti
         WHERE $whereCard
         AND LOWER(status) IN ('approved','approve','success','diterima')"
    )
);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>e-Cuti | Pengajuan Cuti</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

body{
background:#eef4ff;
display:block;
overflow-x:hidden;
}

.content{
    margin-left:270px;
    width:calc(100vw - 270px);
    box-sizing:border-box;
    overflow-x:hidden;
    min-height:100vh;
    padding:30px;
}

.header{
background:#fff;
padding:30px;
border-radius:20px;
box-shadow:0 10px 25px rgba(0,0,0,.08);
margin-bottom:25px;
}

.header h1{
font-size:32px;
color:#1e293b;
}

.header p{
margin-top:5px;
color:#64748b;
}

.cards{
display:grid;
grid-template-columns:repeat(3,minmax(0,1fr));
gap:20px;
margin-bottom:25px;
}

.card{
background:#fff;
padding:25px;
border-radius:18px;
box-shadow:0 8px 20px rgba(0,0,0,.08);
}

.card h2{
font-size:38px;
background:linear-gradient(90deg,#1e3a6f,#6c8cff);
-webkit-background-clip:text;
background-clip:text;
color:transparent;
}

.card p{
color:#64748b;
}

.form-box{
background:#fff;
padding:30px;
border-radius:18px;
box-shadow:0 8px 20px rgba(0,0,0,.08);
}

.form-box h3{
margin-bottom:20px;
font-size:22px;
color:#1e293b;
}

.form-group{
margin-bottom:18px;
}

label{
display:block;
margin-bottom:5px;
font-weight:600;
font-size:14px;
color:#334155;
}

.form-control{
width:100%;
padding:14px 16px;
border:1px solid #ddd;
border-radius:8px;
font-size:15px;
transition: border 0.3s;
}

.form-control:focus{
border-color:#6c8cff;
outline:none;
box-shadow:0 0 0 4px rgba(108,140,255,0.14);
}

textarea.form-control{
height:120px;
resize:vertical;
}

.row{
display:flex;
gap:20px;
}

.col{
flex:1;
}

/* ===== BUTTON GROUP ===== */
.btn-group {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 20px;
    align-items: center;
}

/* ===== BUTTON SAMA WARNA ===== */
.btn-group .btn {
    padding: 0 18px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-family:'Poppins','Segoe UI',Arial,sans-serif;
    font-weight: 800;
    font-size: 14px;
    transition: all 0.3s;
    text-decoration: none;
    display: inline-flex;
    align-items:center;
    justify-content:center;
    text-align: center;
    min-width: 112px;
    height:44px;
    background: linear-gradient(90deg,#1e3a6f,#6c8cff);
    color: #fff;
}

.btn-group .btn:hover {
    background: linear-gradient(90deg,#1e3a6f,#6c8cff);
    transform: translateY(-1px);
    box-shadow: 0 10px 20px rgba(108,140,255,.22);
}

/* ===== RESPONSIVE ===== */
@media(max-width:900px){

.content{
margin-left:0;
width:100%;
padding:15px;
}

.cards{
grid-template-columns:1fr;
}

.row{
flex-direction:column;
}

.btn-group {
    justify-content: center; /* ← tengah di HP */
    flex-direction: column;
    gap: 10px;
}

.btn-group .btn {
    width: 100%;
    padding: 0 18px;
    font-size: 14px;
    min-width: unset;
}

}

/* ===== RESPONSIVE HP KECIL ===== */
@media(max-width:480px){
    .form-box {
        padding: 15px;
    }
    
    .btn-group .btn {
        padding: 0 15px;
        font-size: 14px;
    }
}
</style>
</head>

<body>

<?php include '../function/sidebar.php'; ?>

<div class="content">

<div class="header">
<h1>Pengajuan Cuti</h1>
<p>Sistem e-Cuti PT Maju Bersama</p>
</div>

<div class="cards">

<div class="card">
<h2><?php echo $jmlPengajuan; ?></h2>
<p>Total Pengajuan</p>
</div>

<div class="card">
<h2><?php echo $jmlPending; ?></h2>
<p>Pending</p>
</div>

<div class="card">
<h2><?php echo $jmlApprove; ?></h2>
<p>Approved</p>
</div>

</div>

<div class="form-box">

<h3>Form Pengajuan Cuti</h3>

<form action="pengajuancuti.input.php" method="POST" id="formCuti">

<div class="row">

<div class="col">
<div class="form-group">
<label>NIK</label>
<input
type="text"
name="nik"
class="form-control"
value="<?php echo $nik; ?>"
readonly>
</div>
</div>

<div class="col">
<div class="form-group">
<label>Jenis Cuti</label>

<select
name="idcuti"
class="form-control"
required>

<?php

$q = mysqli_query(
$connect,
"SELECT * FROM jeniscuti ORDER BY jeniscuti"
);

while($r = mysqli_fetch_assoc($q))
{
?>

<option value="<?php echo $r['idcuti']; ?>">
<?php echo $r['jeniscuti']; ?>
</option>

<?php
}
?>

</select>

</div>
</div>

</div>

<div class="row">

<div class="col">
<div class="form-group">
<label>Tanggal Pengajuan</label>
<input
type="date"
name="tanggalpengajuan"
class="form-control"
id="tanggalpengajuan"
required>
</div>
</div>

<div class="col">
<div class="form-group">
<label>Tanggal Mulai Cuti</label>
<input
type="date"
name="tanggalmulai"
class="form-control"
id="tanggalmulai"
required>
</div>
</div>

</div>

<div class="form-group">
<label>Lama Cuti (Hari)</label>
<input
type="number"
name="lamacuti"
class="form-control"
id="lamacuti"
required
min="1"
placeholder="Masukkan jumlah hari cuti">
</div>

<div class="form-group">
<label>Alasan Cuti</label>
<textarea
name="alasancuti"
class="form-control"
id="alasancuti"
required
placeholder="Tuliskan alasan cuti Anda..."></textarea>
</div>

<!-- ===== BUTTON SAMPINGAN RAPAT & WARNA SAMA ===== -->
<div class="btn-group">
    <button type="submit" class="btn">Simpan</button>
    <a href="../dashboard/" class="btn">Batal</a>
</div>

</form>

</div>

</div>

<!-- ===== JAVASCRIPT ===== -->
<script>
// Auto set tanggal hari ini
document.addEventListener('DOMContentLoaded', function() {
    var today = new Date().toISOString().split('T')[0];
    document.getElementById('tanggalpengajuan').value = today;
});

// Validasi tanggal mulai
document.getElementById('tanggalmulai').addEventListener('change', function() {
    var tanggalPengajuan = document.getElementById('tanggalpengajuan').value;
    var tanggalMulai = this.value;
    
    if (tanggalMulai < tanggalPengajuan) {
        alert('Tanggal mulai cuti tidak boleh kurang dari tanggal pengajuan!');
        this.value = '';
    }
});

// Konfirmasi batal
document.querySelector('.btn-batal')?.addEventListener('click', function(e) {
    if (!confirm('Yakin ingin membatalkan?')) {
        e.preventDefault();
    }
});
</script>

</body>
</html>

<?php
koneksi_tutup();
?>