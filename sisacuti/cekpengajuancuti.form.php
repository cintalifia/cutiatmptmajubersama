<?php
// panggil file koneksi.php
require '../function/koneksi.php';

// buat koneksi ke database mysql
koneksi_buka();

// tangkap variabel kd_mhs
$idpengajuan = $_POST['id'];
 session_start();
// query untuk menampilkan mahasiswa berdasarkan kd_mhs
$data = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM pengajuancuti WHERE idpengajuancuti='$idpengajuan'"));

// jika kd_mhs > 0 / form ubah data



        $idpengajuan = $data['idpengajuancuti'];
	$nik = $data['nik'];
	$nama = $_SESSION['nama'];
	$tanggalmulai = $data['tanggalmulai'];
	$lamacuti = $data['lamacuti'];
        $alasancuti = $data['alasancuti'];
	/*
	if($data['status']==1) {
		$status = "Aktif";
	} else {
		$status = "Tidak Aktif";
	}
*/
//  <link rel="stylesheet" href="/resources/demos/style.css">
        
?>
<script>


function Otomatis2() {
    var tes = document.getElementById("tanggalmulai").value;
     document.getElementById("tglmulaitext").value= tes;
    
}

</script>

 <link rel="stylesheet" href="../css/jquery-ui.css">
  <script src="../js/jquery-1.12.4.js"></script>
  <script src="../js/jquery-ui.js"></script>
  <style type="text/css">
    .datepicker{
    z-index: 1151 !important;
   
}
</style>
<form class="form-horizontal" id="form-cekpengajuancuti">
     <p class="statusMsg"></p>
     <div class="control-group">
		<label class="control-label" for="idpengajuan">ID Pengajuan Cuti</label>
		<div class="controls">
			<input type="text" id="idpengajuan" class="input-medium" name="idpengajuan" readonly="readonly" value="<?php echo $idpengajuan ?>">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="nik">NIK</label>
		<div class="controls">
			<input type="text" id="nik" class="input-medium" readonly="readonly" name="nik" oninvalid="this.setCustomValidity('Please Enter valid NIK') required="required" value="<?php echo $nik ?>">
		</div>
	</div>
	
    <div class="control-group">
        
	<label class="control-label" for="nama">Nama</label>
		<div class="controls">
			<input type="text" id="nama" class="input-xlarge" name="nama" readonly="readonly" value="<?php echo $nama ?>">
		</div>
	    </div>
	 <div class="control-group">
		<label class="control-label" for="tanggalmulai">Tanggal Mulai</label>
		<div class="controls">
			<input type="date" onchange="Otomatis2();" id="tanggalmulai" class="input-xlarge" name="tanggalmulai" value="<?php echo $tanggalmulai ?>">
		</div>
	  </div>

           <div class="control-group">
                <label class="control-label" for="lamacuti">Lama Cuti</label>
		<div class="controls">
			<input type="text" id="lamacuti" class="input-xlarge" name="lamacuti" value="<?php echo $lamacuti ?>">
		</div>
	  </div>
      <div class="control-group">
                <label class="control-label" for="alasan">Alasan</label>
		<div class="controls">
			<input type="text" id="alasan" class="input-xlarge" name="alasan" value="<?php echo $alasancuti ?>">
                        <input type="hidden"  id="tglmulaitext" class="input-xlarge" name="tglmulaitext" >
                </div>
	  </div>
	 <?php

	// $cek_user=mysqli_num_rows(mysqli_query($connect, "SELECT * FROM cekpengajuancuti WHERE nik='123123'"));
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

