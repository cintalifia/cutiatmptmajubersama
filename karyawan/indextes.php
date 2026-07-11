<?php
require '../function/koneksi.php';

if (!isset($connect)) {
    die("Error: Koneksi database gagal!");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Karyawan</title>
</head>
<body>

<style>
/* ===== RESET ===== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    padding: 20px;
    background: #f1f5f9;
    font-family: Arial, Helvetica, sans-serif;
}

/* ===== CONTAINER UTAMA ===== */
.table-wrapper {
    width: 100%;
    max-width: 100%;
    overflow-x: auto; /* ← PENTING! buat scroll horizontal */
    overflow-y: visible;
    -webkit-overflow-scrolling: touch; /* buat iPhone */
}

/* ===== BOX TABEL ===== */
.table-box{
    background:#fff;
    border-radius:20px;
    box-shadow:0 10px 20px rgba(0,0,0,.08);
    padding: 15px 10px;
    min-width: 100%;
    display: inline-block; /* biar sesuai konten */
}

/* ===== TABEL ===== */
table{
    width: 100%;
    min-width: 750px; /* biar ga nyempit */
    border-collapse:collapse;
    font-size: 14px;
    table-layout: fixed; /* biar rata */
}

/* ===== HEADER ===== */
table th{
    background:#2563eb;
    color:#fff;
    padding:12px 8px;
    text-align:center;
    font-weight: 600;
    white-space: nowrap;
    border: 1px solid #1d4ed8;
}

/* ===== BODY ===== */
table td{
    padding:10px 8px;
    border-bottom:1px solid #eee;
    text-align:center;
    word-wrap: break-word;
    vertical-align: middle;
    border: 1px solid #f0f0f0;
}

table tr:hover{
    background:#f8fbff;
}

/* ===== LEBAR KOLOM ===== */
table th:nth-child(1), 
table td:nth-child(1) { 
    width: 12%; /* NIK */
}

table th:nth-child(2), 
table td:nth-child(2) { 
    width: 25%; /* Nama */
}

table th:nth-child(3), 
table td:nth-child(3) { 
    width: 18%; /* Divisi */
}

table th:nth-child(4), 
table td:nth-child(4) { 
    width: 15%; /* Level */
}

table th:nth-child(5), 
table td:nth-child(5) { 
    width: 15%; /* Sisa Cuti */
}

table th:nth-child(6), 
table td:nth-child(6) { 
    width: 15%; /* Aksi */
}

/* ===== TOMBOL ===== */
.btn-edit, .btn-hapus {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 12px;
    font-weight: 500;
    color: #fff !important;
    margin: 1px 2px;
    white-space: nowrap;
    transition: all 0.2s;
    border: none;
    cursor: pointer;
    min-width: 50px;
}

.btn-edit{
    background:#2563eb;
}

.btn-edit:hover{
    background:#1d4ed8;
    transform: scale(1.05);
}

.btn-hapus{
    background:#ef4444;
}

.btn-hapus:hover{
    background:#dc2626;
    transform: scale(1.05);
}

/* ===== PESAN KOSONG ===== */
.no-data{
    text-align:center;
    padding:40px 20px;
    color:#666;
    font-size: 16px;
}

/* ===== RESPONSIVE ===== */
@media screen and (max-width: 1024px) {
    table {
        font-size: 13px;
        min-width: 700px;
    }
    
    table th, table td {
        padding: 8px 5px;
    }
    
    .btn-edit, .btn-hapus {
        font-size: 11px;
        padding: 4px 7px;
        min-width: 40px;
    }
}

@media screen and (max-width: 768px) {
    .table-box {
        padding: 10px 5px;
    }
    
    table {
        font-size: 12px;
        min-width: 620px;
    }
    
    table th, table td {
        padding: 6px 4px;
    }
    
    .btn-edit, .btn-hapus {
        font-size: 10px;
        padding: 3px 5px;
        min-width: 35px;
    }
}

@media screen and (max-width: 480px) {
    table {
        font-size: 11px;
        min-width: 500px;
    }
    
    table th, table td {
        padding: 5px 3px;
    }
    
    .btn-edit, .btn-hapus {
        font-size: 9px;
        padding: 2px 4px;
        min-width: 30px;
    }
}
</style>

<!-- ===== WRAPPER UTAMA ===== -->
<div class="table-wrapper">
    <div class="table-box">
        <table>
            <thead>
                <tr>
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>Divisi</th>
                    <th>Level</th>
                    <th>Sisa Cuti</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = mysqli_query($connect, "SELECT * FROM karyawan ORDER BY nama ASC");
                
                if (!$query) {
                    echo '<tr><td colspan="6" style="color:red;padding:20px;text-align:center;">';
                    echo "Error SQL: " . mysqli_error($connect);
                    echo '</td></tr>';
                } else {
                    if(mysqli_num_rows($query) > 0) {
                        while($data = mysqli_fetch_assoc($query)) {
                ?>
                            <tr>
                                <td><?php echo htmlspecialchars($data['nik']); ?></td>
                                <td><?php echo htmlspecialchars($data['nama']); ?></td>
                                <td><?php echo htmlspecialchars($data['divisi']); ?></td>
                                <td><?php echo htmlspecialchars($data['level']); ?></td>
                                <td><?php echo htmlspecialchars($data['sisacuti']); ?></td>
                                <td>
                                    <a href="edit.php?nik=<?php echo urlencode($data['nik']); ?>" class="btn-edit">
                                        ✏ Edit
                                    </a>
                                    <a href="hapus.php?nik=<?php echo urlencode($data['nik']); ?>"
                                    class="btn-hapus"
                                    onclick="return confirm('Yakin ingin menghapus data ini?')">
                                        🗑 Hapus
                                    </a>
                                </td>
                            </tr>
                <?php
                        }
                    } else {
                ?>
                        <tr>
                            <td colspan="6" class="no-data">
                                📝 Belum ada data pegawai
                            </td>
                        </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>