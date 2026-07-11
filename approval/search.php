<?php
session_start();

require '../function/koneksi.php';

koneksi_buka();

$keyword = $_POST['keyword'] ?? '';

$sql = mysqli_query($connect,"
SELECT
    p.*,
    k.nama,
    k.divisi,
    k.level
FROM pengajuancuti p
LEFT JOIN karyawan k
ON p.nik = k.nik
WHERE
    p.idpengajuancuti LIKE '%$keyword%'
    OR p.nik LIKE '%$keyword%'
    OR k.nama LIKE '%$keyword%'
    OR k.divisi LIKE '%$keyword%'
ORDER BY p.idpengajuancuti DESC
");

$output = '';

if(mysqli_num_rows($sql) > 0)
{
    $output .= '
    <table class="table-modern">

    <thead>
    <tr>
        <th>No</th>
        <th>ID</th>
        <th>NIK</th>
        <th>Nama</th>
        <th>Divisi</th>
        <th>Tanggal Mulai</th>
        <th>Lama Cuti</th>
        <th>Status</th>
    </tr>
    </thead>

    <tbody>
    ';

    $no = 1;

    while($d = mysqli_fetch_assoc($sql))
    {
        $output .= '
        <tr>
            <td>'.$no++.'</td>
            <td>'.$d['idpengajuancuti'].'</td>
            <td>'.$d['nik'].'</td>
            <td>'.$d['nama'].'</td>
            <td>'.$d['divisi'].'</td>
            <td>'.$d['tanggalmulai'].'</td>
            <td>'.$d['lamacuti'].' Hari</td>
            <td>'.$d['status'].'</td>
        </tr>
        ';
    }

    $output .= '
    </tbody>
    </table>
    ';
}
else
{
    $output .= '
    <div class="empty">
        Data tidak ditemukan
    </div>
    ';
}

echo json_encode([
    "hasil" => $output
]);

koneksi_tutup();
?>
