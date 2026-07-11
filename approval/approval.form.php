<?php

require '../function/koneksi.php';

koneksi_buka();

$id = $_POST['id'] ?? '';

$query = mysqli_query($connect,"
SELECT
    p.*,
    k.nama,
    k.divisi,
    j.jeniscuti
FROM pengajuancuti p
LEFT JOIN karyawan k
    ON p.nik = k.nik
LEFT JOIN jeniscuti j
    ON p.idcuti = j.idcuti
WHERE p.idpengajuancuti='$id'
");

$data = mysqli_fetch_assoc($query);

if(!$data)
{
    echo "Data tidak ditemukan";
    exit;
}
?>

<div class="form-group">
<label>ID Pengajuan</label>
<input
type="text"
class="form-control"
value="<?php echo $data['idpengajuancuti']; ?>"
readonly>
</div>

<div class="form-group">
<label>NIK</label>
<input
type="text"
class="form-control"
value="<?php echo $data['nik']; ?>"
readonly>
</div>

<div class="form-group">
<label>Nama Pegawai</label>
<input
type="text"
class="form-control"
value="<?php echo $data['nama']; ?>"
readonly>
</div>

<div class="form-group">
<label>Divisi</label>
<input
type="text"
class="form-control"
value="<?php echo $data['divisi']; ?>"
readonly>
</div>

<div class="form-group">
<label>Jenis Cuti</label>
<input
type="text"
class="form-control"
value="<?php echo $data['jeniscuti']; ?>"
readonly>
</div>

<div class="form-group">
<label>Tanggal Pengajuan</label>
<input
type="text"
class="form-control"
value="<?php echo $data['tanggalpengajuan']; ?>"
readonly>
</div>

<div class="form-group">
<label>Tanggal Mulai</label>
<input
type="text"
class="form-control"
value="<?php echo $data['tanggalmulai']; ?>"
readonly>
</div>

<div class="form-group">
<label>Lama Cuti</label>
<input
type="text"
class="form-control"
value="<?php echo $data['lamacuti']; ?> Hari"
readonly>
</div>

<div class="form-group">
<label>Alasan Cuti</label>
<textarea
class="form-control"
rows="4"
readonly><?php echo $data['alasancuti']; ?></textarea>
</div>

<input
type="hidden"
id="idpengajuancuti"
value="<?php echo $data['idpengajuancuti']; ?>">

<?php
koneksi_tutup();
?>
