<?php
require '../function/koneksi.php';

// buat koneksi ke database mysql
koneksi_buka();

// proses menghapus data mahasiswa

 //   mysqli_query($connect, "DELETE FROM karyawan WHERE nik=".$_POST['hapus']);
	// deklarasikan variabel
        $idpengajuan = $_POST['id'];
        $nik = $_POST['nik'];
        $tanggalmulai = $_POST['tanggalmulai'];
	$lamacuti = $_POST['lamacuti'];
        $alasancuti = $_POST['alasancuti'];


  
                 mysqli_query($connect, "update pengajuancuti set tanggalmulai ='$tanggalmulai',lamacuti='$lamacuti',alasancuti='$alasancuti' where nik ='$nik'")  ;


// tutup koneksi ke database mysql
koneksi_tutup();

?>
