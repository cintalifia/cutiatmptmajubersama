<?php
session_start();

require '../function/koneksi.php';

if(!isset($_SESSION['username']))
{
    header("Location: ../login.php");
    exit;
}

koneksi_buka();

$nik  = $_SESSION['username'];
$nama = $_SESSION['nama'];
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
font-family:Poppins,sans-serif;
}

body{
background:#f1f5f9;
display:flex;
}

/* SIDEBAR */

.sidebar{
width:260px;
height:100vh;
background:#1e293b;
position:fixed;
left:0;
top:0;
padding:25px;
color:#fff;
}

.logo{
font-size:28px;
font-weight:700;
margin-bottom:30px;
}

.user-box{
background:#334155;
padding:15px;
border-radius:15px;
margin-bottom:25px;
}

.user-box h4{
margin-top:5px;
}

.menu a{
display:block;
padding:14px;
margin-bottom:10px;
border-radius:10px;
text-decoration:none;
color:white;
transition:.3s;
}

.menu a:hover,
.menu a.active{
background:linear-gradient(90deg,#1e3a6f,#6c8cff);
}

/* CONTENT */

.main{
margin-left:260px;
width:calc(100% - 260px);
padding:30px;
}

.header{
background:linear-gradient(135deg,#2563eb,#3b82f6);
padding:30px;
border-radius:20px;
color:white;
margin-bottom:25px;
}

.stats{
display:grid;
grid-template-columns:repeat(3,1fr);
gap:20px;
margin-bottom:25px;
}

.card{
background:white;
padding:25px;
border-radius:20px;
box-shadow:0 10px 20px rgba(0,0,0,.05);
}

.card h2{
margin-top:10px;
color:#2563eb;
}

.form-box{
background:white;
padding:30px;
border-radius:20px;
box-shadow:0 10px 20px rgba(0,0,0,.05);
}

.row{
display:flex;
gap:20px;
}

.col{
flex:1;
}

.form-group{
margin-bottom:18px;
}

.form-group label{
display:block;
margin-bottom:8px;
font-weight:600;
}

.form-control{
width:100%;
height:50px;
padding:0 15px;
border:1px solid #ddd;
border-radius:12px;
}

textarea.form-control{
height:120px;
padding:15px;
}

.btn{
width:100%;
height:55px;
border:none;
border-radius:12px;
background:linear-gradient(90deg,#1e3a6f,#6c8cff);
color:white;
font-size:16px;
font-weight:600;
cursor:pointer;
}

.btn:hover{
background:linear-gradient(90deg,#1e3a6f,#6c8cff);
}

@media(max-width:768px){

.sidebar{
display:none;
}

.main{
margin-left:0;
width:100%;
}

.row{
flex-direction:column;
}

.stats{
grid-template-columns:1fr;
}

}

</style>
</head>

<body>

<div class="sidebar">

<div class="logo">
e-Cuti
</div>

<div class="user-box">
<div>Login as</div>
<h4><?php echo $nama; ?></h4>
</div>

<div class="menu">
<a href="../dashboard/">Dashboard</a>
<a href="#" class="active">Pengajuan Cuti</a>
<a href="../approval/">Approval</a>
<a href="../jadwalcuti/">Jadwal Cuti</a>
<a href="../sisacuti/">Sisa Cuti</a>
<a href="../logout.php">Logout</a>
</div>

</div>

<div class="main">

<div class="header">
<h1>📅 Pengajuan Cuti</h1>
<p>Ajukan cuti secara online dengan mudah dan cepat</p>
</div>

<div class="stats">

<div class="card">
<p>Total Hak Cuti</p>
<h2>12 Hari</h2>
</div>

<div class="card">
<p>Status</p>
<h2>Pending</h2>
</div>

<div class="card">
<p>Tahun</p>
<h2><?php echo date('Y'); ?></h2>
</div>

</div>

<div class="form-box">

<h2 style="margin-bottom:20px;">
Form Pengajuan Cuti
</h2>

<form action="pengajuancuti.input.php" method="POST">

<div class="row">

<div class="col">
<div class="form-group">
<label>NIP</label>
<input type="text"
name="nik"
class="form-control"
value="<?php echo $nik; ?>">
</div>
</div>

<div class="col">
<div class="form-group">
<label>Nama Pegawai</label>
<input type="text"
name="nama"
class="form-control"
value="<?php echo $nama; ?>">
</div>
</div>

</div>

<div class="form-group">

<label>Jenis Cuti</label>

<select name="idcuti" class="form-control">

<?php

$q = mysqli_query($connect,"SELECT * FROM jeniscuti");

while($r = mysqli_fetch_assoc($q))
{
?>

<option value="<?php echo $r['idcuti']; ?>">
<?php echo $r['idcuti'].' - '.$r['jeniscuti']; ?>
</option>

<?php
}
?>

</select>

</div>

<div class="row">

<div class="col">
<div class="form-group">
<label>Tanggal Pengajuan</label>
<input type="date"
name="tanggalpengajuan"
class="form-control">
</div>
</div>

<div class="col">
<div class="form-group">
<label>Tanggal Mulai Cuti</label>
<input type="date"
name="tanggalmulai"
class="form-control">
</div>
</div>

</div>

<div class="form-group">
<label>Lama Hari</label>
<input type="number"
name="lamacuti"
class="form-control">
</div>

<div class="form-group">
<label>Alasan Cuti</label>
<textarea
name="alasancuti"
class="form-control"></textarea>
</div>

<input type="hidden" name="status" value="Pending">

<button class="btn" type="submit">
Simpan Pengajuan
</button>

</form>

</div>

</div>

</body>
</html>

<?php
koneksi_tutup();
?>