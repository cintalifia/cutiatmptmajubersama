<html>
<head>
    <title>Data Karyawan</title>
</head>
<body>
<h1>Data Karyawan</h1><hr>

<a href="print.php">Cetak Data</a><br><br>

<table border="1" cellpadding="8">
<tr>
    <th>NIK</th>
    <th>Nama</th>
    <th>Jenis Kelamin</th>
    <th>Telepon</th>
    <th>Alamat</th>
</tr>
 
<?php
// Load file koneksi.php
include "koneksi.php";
 
$query = "SELECT * FROM karyawan"; // Tampilkan semua data gambar
$sql = mysqli_query($connect, $query); // Eksekusi/Jalankan query dari variabel $query
$row = mysqli_num_rows($sql); // Ambil jumlah data dari hasil eksekusi $sql
 
if($row > 0){ // Jika jumlah data lebih dari 0 (Berarti jika data ada)
    while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
        echo "<tr>";
        echo "<td>".$data['nik']."</td>";
        echo "<td>".$data['nama']."</td>";
        echo "<td>".$data['divisi']."</td>";
        echo "<td>".$data['level']."</td>";
        echo "<td>".$data['sisacuti']."</td>";
        echo "</tr>";
    }
}else{ // Jika data tidak ada
    echo "<tr><td colspan='4'>Data tidak ada</td></tr>";
}
?>
</table>
</body>
</html>
