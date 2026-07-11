
<html>
     <link href="../css/style.css" rel="stylesheet" type="text/css">
        <link href="../css/bootstrap.min.css" rel="stylesheet" media="screen">

<?php
// panggil berkas koneksi.php
require '../function/koneksi.php';

// buat koneksi ke database mysql
koneksi_buka();
include '../function/functionnya.php';
?>

<form class="form-horizontal" id="form-gantipassword">
     <p class="statusMsg"></p>
        <div class="control-group">
		<label class="control-label" for="passwordlama">Password Lama</label>
		<div class="controls">
			<input type="password" id="passwordlama" class="input-medium" name="passwordlama" value="">
		</div>
	</div>
     <div class="control-group">
		<label class="control-label" for="passwordbaru">Password Baru</label>
		<div class="controls">
			<input type="text" id="passwordbaru" class="input-medium" name="passwordbaru" value="">
		</div>
	</div>
	
    <div class="control-group">
        
	<label class="control-label" for="konfirmasipassword">Konfirmasi Password</label>
		<div class="controls">
			<input type="text" id="konfirmasipassword" class="input-xlarge" name="konfirmasipassword" value="">
		</div>
	    </div>

 <button class="btn btn-success" type="button" id="simpan-gantipassword" >Simpan3</button>
       
</form>
        <script src="aplikasi.js"></script>    
        
<?php 

// tutup koneksi ke database mysql <form method="post" action="<?php echo $_SERVER['PHP_SELF']; 
koneksi_tutup(); 
?>

</html>