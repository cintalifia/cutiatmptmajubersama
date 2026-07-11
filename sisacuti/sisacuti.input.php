<?php
require '../function/koneksi.php';

// buat koneksi ke database mysql
koneksi_buka();

// proses menghapus data mahasiswa
if(isset($_POST['ubah'])) {
   
//mysqli_query($connect, "DELETE FROM karyawan WHERE nik=".$_POST['hapus']);
// mysqli_query($connect, "update karyawan set nama='bbba' where nik ='3434'")  ;       
}        
if(isset($_POST['hapus'])) {
   
mysqli_query($connect, "DELETE FROM karyawan WHERE nik=".$_POST['hapus']);
// mysqli_query($connect, "update karyawan set nama='aaaaa' where nik ='3213'")  ;       
} else {
 //   mysqli_query($connect, "DELETE FROM karyawan WHERE nik=".$_POST['hapus']);
	// deklarasikan variabel
	$nik	= $_POST['id'];
        $nik2	= $_POST['nik'];
	$nama	= $_POST['nama'];
	$divisi	= $_POST['divisi'];
	$levelkaryawan = $_POST['levelkaryawan'];
        $sisacuti = $_POST['sisacuti'];
	
	// validasi agar tidak ada data yang kosong
	if($nik!="" && $nama!="" && $divisi!="") {
		// proses tambah data mahasiswa
		if($nik == 0) {



		$tanggalnya = date("Y-m-d", strtotime($tglmasuk));
			mysqli_query($connect, "INSERT INTO karyawan VALUES('$nik2','$nama','$divisi','$levelkaryawan','$sisacuti')");
			$nik ="";
                	$nama ="";
                        $divisi ="";
 echo'<a href="#dialog-karyawan" id="0" class="btn tambah" data-toggle="modal">';
                } else {
                 //   $tanggalnya = date("Y-m-d", strtotime($tglmasuk));
                 mysqli_query($connect, "update karyawan set nama ='$nama',divisi='$divisi',level='$levelkaryawan',sisacuti='$sisacuti' where nik ='$nik'")  ;
		//  mysqli_query($connect, "UPDATE karyawan SET nama = $nama,divisi = $divisi,WHERE nik = '$nik'3"
                //  );
                 
		}
	}
}

// tutup koneksi ke database mysql
koneksi_tutup();

?>
