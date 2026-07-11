<?php

require '../function/koneksi.php';

koneksi_buka();

$idpengajuan = $_POST['id'] ?? '';

$query = mysqli_query($connect,"
SELECT
    p.*,
    k.nama,
    k.divisi,
    k.level,
    j.jeniscuti
FROM pengajuancuti p
LEFT JOIN karyawan k
    ON p.nik = k.nik
LEFT JOIN jeniscuti j
    ON p.idcuti = j.idcuti
WHERE p.idpengajuancuti='$idpengajuan'
");

$data = mysqli_fetch_assoc($query);

if(!$data)
{
    exit("Data tidak ditemukan");
}
?>

<form>

<div class="control-group">
<label>ID Pengajuan</label>
<input
type="text"
class="form-control"
value="<?php echo $data['idpengajuancuti']; ?>"
readonly>
</div>

<br>

<div class="control-group">
<label>NIK</label>
<input
type="text"
class="form-control"
value="<?php echo $data['nik']; ?>"
readonly>
</div>

<br>

<div class="control-group">
<label>Nama Pegawai</label>
<input
type="text"
class="form-control"
value="<?php echo $data['nama']; ?>"
readonly>
</div>

<br>

<div class="control-group">
<label>Divisi</label>
<input
type="text"
class="form-control"
value="<?php echo $data['divisi']; ?>"
readonly>
</div>

<br>

<div class="control-group">
<label>Level</label>
<input
type="text"
class="form-control"
value="<?php echo $data['level']; ?>"
readonly>
</div>

<br>

<div class="control-group">
<label>Jenis Cuti</label>
<input
type="text"
class="form-control"
value="<?php echo $data['jeniscuti']; ?>"
readonly>
</div>

<br>

<div class="control-group">
<label>Tanggal Pengajuan</label>
<input
type="text"
class="form-control"
value="<?php echo date('d-m-Y',strtotime($data['tanggalpengajuan'])); ?>"
readonly>
</div>

<br>

<div class="control-group">
<label>Tanggal Mulai Cuti</label>
<input
type="text"
class="form-control"
value="<?php echo date('d-m-Y',strtotime($data['tanggalmulai'])); ?>"
readonly>
</div>

<br>

<div class="control-group">
<label>Lama Cuti</label>
<input
type="text"
class="form-control"
value="<?php echo $data['lamacuti']; ?> Hari"
readonly>
</div>

<br>

<div class="control-group">
<label>Alasan Cuti</label>

<textarea
class="form-control"
rows="4"
readonly><?php echo $data['alasancuti']; ?></textarea>

</div>

<br>

<div class="control-group">
<label>Status</label>

<?php
if($data['status']=='Approved')
{
    echo "<span style='color:green;font-weight:bold'>APPROVED</span>";
}
elseif($data['status']=='Rejected')
{
    echo "<span style='color:red;font-weight:bold'>REJECTED</span>";
}
else
{
    echo "<span style='color:orange;font-weight:bold'>PENDING</span>";
}
?>

</div>

</form>

<?php
koneksi_tutup();
?>
