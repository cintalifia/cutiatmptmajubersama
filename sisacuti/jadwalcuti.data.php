
<html>
     <link href="../css/style.css" rel="stylesheet" type="text/css">
        <link href="../css/bootstrap.min.css" rel="stylesheet" media="screen">

<?php
// panggil berkas koneksi.php
require '../function/koneksi.php';

// buat koneksi ke database mysql
koneksi_buka();
include '../function/functionnya.php';
$pagenya = $_POST['4'];
$tpages2 = $_POST['pagenya'];
?>

               
<?php


if(isset($keyword)){ // Jika veriabel $keyword ada (user telah mengklik tombol search)
			$param = $keyword;
			$param2 = $keyword2;
			// Buat query untuk menampilkan data siswa berdasarkan NIS / Nama / Jenis Kelamin / Telp / Alamat
			//$sql = $pdo->prepare("SELECT * FROM siswa WHERE nama LIKE :na");
			//$sql->bindParam(':na', $param);
			
			//$sql->execute(); // Eksekusi querynya
                        
			$sql ="select pengajuancuti.idpengajuancuti,pengajuancuti.nik,karyawan.nama,pengajuancuti.tanggalmulai,pengajuancuti.lamacuti,pengajuancuti.status from pengajuancuti inner join karyawan on pengajuancuti.nik = karyawan.nik where pengajuancuti.tanggalmulai BETWEEN '$param' AND '$param2'";
                        //$sql ="SELECT * FROM pengajuancuti where nik='45'";
                       
		}else{ // Jika user belum mengklik tombol search
			// Buat query untuk menampilkan semua data siswa
			//$sql ="select pengajuancuti.idpengajuancuti,pengajuancuti.nik,karyawan.nama,pengajuancuti.tanggalmulai,pengajuancuti.lamacuti,pengajuancuti.status from pengajuancuti inner join karyawan on pengajuancuti.nik = karyawan.nik";
			$sql ="SELECT * FROM pengajuancuti where nik='000'";
		}
?>
		
<table class="table table-condensed table-bordered table-hover" cellpadding="0" cellspacing="0">
<thead>
	<tr>
	<th style="width:20px">No</th>
		<th style="width:120px">NIK</th>
		<th style="width:200px">Nama</th>
		<th style="width:200px">Tanggal Mulai</th>
		<th style="width:120px">Lama Cuti</th>
                <th style="width:120px">Status</th>

	</tr>
</thead>
<tbody>
	<?php 
		$i = 1;
                $y = 1;
		//$query = mysqli_query($connect, "SELECT * FROM jadwalcuti");
		//$sql =  "SELECT * FROM jadwalcuti ORDER BY nama";
        $result = mysqli_query($connect, $sql);
        //echo $sql;
        //pagination config start
        $rpp = 10; // jumlah record per halaman
        $reload = "#data-jadwalcuti";
        
        $page = intval($_GET["page"]);
        if($page<=0) $page = 1;  
        $tcount = mysqli_num_rows($result);
        $tpages = ($tcount) ? ceil($tcount/$rpp) : 1; // total pages, last page number
      //  $tpages = $_POST['pagenya'];
        $count = 0;
        $i = ($page-1)*$rpp;
        $no_urut = ($page-1)*$rpp;
		// tampilkan data mahasiswa selama masih ada
		//while($data = mysqli_fetch_array($result)) {
                        while(($count<$rpp) && ($i<$tcount)) {
                        mysqli_data_seek($result,$i );
                        $data = mysqli_fetch_array($result);

                        ?>
	<tr>
		<td><?php echo $y++ ?></td>
		<td><?php echo $data['nik'] ?></td>
		<td><?php echo $data['nama'] ?></td>
		<td><?php echo $data['tanggalmulai'] ?></td>
		<td><?php echo $data['lamacuti'] ?></td>
                <td><?php echo $data['status'] ?></td>
		
		
	</tr>
	<?php
		$i++;
                $count++;
                
		}
	?>
</tbody>
</table>
    


 <div> <?php echo paginate_one($reload, $page, $tpages); ?></div>
<?php 

// tutup koneksi ke database mysql <form method="post" action="<?php echo $_SERVER['PHP_SELF']; 
koneksi_tutup(); 
?>

</html>