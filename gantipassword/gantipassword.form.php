<?php
// panggil file koneksi.php
require '../function/koneksi.php';

// buat koneksi ke database mysql
koneksi_buka();

// tangkap variabel kd_mhs
$nik = $_POST['id'];

// query untuk menampilkan mahasiswa berdasarkan kd_mhs
$data = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM pengajuancuti WHERE nik=".$nik));

// jika kd_mhs > 0 / form ubah data
if($nik> 0) { 
	$nik = $data['nik'];
	$nama = $data['nama'];
	$divisi = $data['divisi'];
	$levelkaryawan = $data['level'];
        $sisacuti = $data['sisacuti'];
	/*
	if($data['status']==1) {
		$status = "Aktif";
	} else {
		$status = "Tidak Aktif";
	}
*/
//form tambah data
} else {
	$nik ="";
	$nama ="";
	$divisi ="";
        $sisacuti = "";
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
<form class="form-horizontal" id="form-pengajuancuti">
     <p class="statusMsg"></p>
	<div class="control-group">
		<label class="control-label" for="nik">NIK</label>
		<div class="controls">
			<input type="text" id="nik" class="input-medium" name="nik" oninvalid="this.setCustomValidity('Please Enter valid NIK') required="required" value="<?php echo $nik ?>">
		</div>
	</div>
	
    <div class="control-group">
        
	<label class="control-label" for="nama">Nama</label>
		<div class="controls">
			<input type="text" id="nama" class="input-xlarge" name="nama" value="<?php echo $nama ?>">
		</div>
	    </div>
	 <div class="control-group">
		<label class="control-label" for="divisi">Divisi</label>
		<div class="controls">
			<input type="text" id="divisi" class="input-xlarge" name="divisi" value="<?php echo $divisi ?>">
		</div>
	  </div>
          <div class="control-group">
                <label class="control-label" for="levelkaryawan">level</label>
		<div class="controls">
			<!-- <input type="text" id="levelkaryawan" class="input-xlarge" name="levelkaryawan" value="<?php //echo $levelkaryawan ?>"> -->
                    <select class="input-small" name="levelkaryawan">
				<?php 
				// tampilkan untuk form ubah pengajuancuti
				if($nik > 0) { ?>
					<option value="<?php echo $levelkaryawan ?>"><?php echo $levelkaryawan ?></option>
				        <?php
                                       if($levelkaryawan == 'Staff'){
                                          echo '<option value="Manager">Manager</option>';
                                          echo '<option value="General Manager">General Manager</option>';   
                                          
                                            
                                          }else if ($levelkaryawan == 'Manager'){
                                          echo'<option value="Staff">Staff</option>';
                                          echo '<option value="General Manager">General Manager</option>';   
                                          
                                            
                                          }else if ($levelkaryawan == 'General Manager'){

                                            echo'<option value="Staff">Staff</option>';
                                          echo '<option value="Manager">Manager</option>';
                                          
                                          }
                                          else{
                                          echo'<option value="Staff">Staff</option>';
                                          echo '<option value="Manager">Manager</option>';
                                          echo '<option value="General Manager">General Manager</option>';   
                                          }
                                        ?>
                                 <?php }else{ ?>
                                     <option value="Staff">Staff</option>
                                     <option value="Manager">Manager</option>
                                     <option value="General Manager">General Manager</option>
                               <?php  } ?>
                             <!--           if($_POST['levelkaryawan'] == "Tetap") {
				<option value="Kontrak">Kontrak</option>
                                }else{
                                <option value="Tetap">Tetap</option>
				} -->
			</select>
		</div>
	  </div>

           <div class="control-group">
                <label class="control-label" for="sisacuti">Sisa Cuti</label>
		<div class="controls">
			<input type="text" id="sisacuti" class="input-xlarge" name="sisacuti" value="<?php echo $sisacuti ?>">
		</div>
	  </div>
	 <?php

	// $cek_user=mysqli_num_rows(mysqli_query($connect, "SELECT * FROM pengajuancuti WHERE nik='123123'"));
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

