<?php
// panggil file koneksi.php
require '../function/koneksi.php';

// buat koneksi ke database mysql
koneksi_buka();

// tangkap variabel kd_mhs
$idcuti = $_POST['id'];

//if($idcuti == "test") {
  //  $idcuti = 0;
//}


// query untuk menampilkan mahasiswa berdasarkan kd_mhs
$data = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM jeniscuti where idcuti='$idcuti'"));
//$data = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM jeniscuti WHERE idcuti='P002'"));
// jika kd_mhs > 0 / form ubah data


if($idcuti == "test" ) { 
	
    
$idnya = mysqli_query($connect, "select * from jeniscuti");
$cek = mysqli_num_rows($idnya);
if($cek > 0){
$query = "SELECT MAX(idcuti) as max FROM jeniscuti";
$hasil = mysqli_query($connect, $query);
$data  = mysqli_fetch_array($hasil);
$budgetNo = $data['max'];
$noUrut = (int) substr($budgetNo, 3, 3);
$noUrut++;
$char = "CT";
$idcuti = $char ."".sprintf("%03s", $noUrut);
$jeniscuti ="";    
    
}else{
// membaca no budgeting terbesar

$idcuti ="CT001";
$jeniscuti ="";

}
        
    
} else {
	$idcuti = $data['idcuti'];
	$jeniscuti = $data['jeniscuti'];

}

//  <link rel="stylesheet" href="/resources/demos/style.css">
?>
 <link rel="stylesheet" href="../css/jquery-ui.css">
  <script src="../js/jquery-1.12.4.js"></script>
  <script src="../js/jquery-ui.js"></script>
  <style type="text/css">
    .datepicker{
    z-index: 1151 !important;
   
}
</style>
<form class="form-horizontal" id="form-jeniscuti">
     <p class="statusMsg"></p>
	<div class="control-group">
		<label class="control-label" for="idcuti">ID Cuti</label>
		<div class="controls">
			<input type="text" id="idcuti" class="input-medium" name="idcuti" readonly="readonly" value="<?php echo $idcuti ?>">
		</div>
	</div>
	
    <div class="control-group">
        
	<label class="control-label" for="jeniscuti">Jenis Cuti</label>
		<div class="controls">
			<input type="text" id="jeniscuti" class="input-xlarge" name="jeniscuti" value="<?php echo $jeniscuti ?>">
		</div>
	    </div>
	 <?php

	// $cek_user=mysqli_num_rows(mysqli_query($connect, "SELECT * FROM jeniscuti WHERE idcuti='123123'"));
//if ($cek_user > 0) {

//echo '<input type="hidden" id="sisacuti2" class="input-xlarge" name="sisacuti2" value="1">';
//}
?>
</form>

<?php
// tutup koneksi ke database mysql
koneksi_tutup();
?>
<script type="text/javascript">
 
    $(".modal-body .datepicker").datepicker({
        dateformat: 'yyyy-mm-dd'
    });
   
</script>

