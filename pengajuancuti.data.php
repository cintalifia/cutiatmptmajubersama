<?php
session_start();

require 'function/koneksi.php';
include 'function/functionnya.php';

koneksi_buka();

$nik  = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$nama = isset($_SESSION['nama']) ? $_SESSION['nama'] : '';
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Pengajuan Cuti</title>

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
padding:30px;
}

.container{
max-width:1100px;
margin:auto;
}

.header{
background:linear-gradient(135deg,#2563eb,#3b82f6);
padding:35px;
border-radius:20px;
color:#fff;
margin-bottom:25px;
}

.header h1{
font-size:32px;
}

.header p{
margin-top:8px;
opacity:.9;
}

.form-box{
background:#fff;
padding:30px;
border-radius:20px;
box-shadow:0 10px 25px rgba(0,0,0,.08);
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
border:1px solid #dbeafe;
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
background:#2563eb;
color:white;
font-size:16px;
font-weight:600;
cursor:pointer;
}

.btn:hover{
background:#1d4ed8;
}

@media(max-width:768px){

.row{
flex-direction:column;
}

}

</style>
</head>

<body>

<div class="container">

<div class="header">
<h1>📅 Pengajuan Cuti</h1>
<p>Ajukan cuti secara online</p>
</div>

<div class="form-box">

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
<label>Nama</label>
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

<div class="row">

<div class="col">
<div class="form-group">
<label>Lama Hari</label>
<input type="number"
name="lamacuti"
class="form-control">
</div>
</div>

<div class="col">
<div class="form-group">
<label>Status</label>
<input type="text"
name="status"
class="form-control"
value="Pending">
</div>
</div>

</div>

<div class="form-group">
<label>Alasan Cuti</label>

<textarea
name="alasancuti"
class="form-control"></textarea>
</div>

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