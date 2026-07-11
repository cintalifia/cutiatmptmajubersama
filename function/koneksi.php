<?php
/*
|--------------------------------------------------------------------------
| Koneksi Database e-Cuti
|--------------------------------------------------------------------------
| Ubah nilai konstanta di bawah sesuai konfigurasi MySQL lokal.
*/

if (!defined('DB_NAMA')) {
    define('DB_NAMA', 'dbcuti');
}
if (!defined('DB_USER')) {
    define('DB_USER', 'root');
}
if (!defined('DB_PASSWORD')) {
    define('DB_PASSWORD', '');
}
if (!defined('DB_HOST')) {
    define('DB_HOST', 'localhost');
}

if (function_exists('mysqli_report')) {
    mysqli_report(MYSQLI_REPORT_OFF);
}

$connect = $connect ?? null;

function koneksi_buka() {
    global $connect;

    if ($connect instanceof mysqli) {
        return $connect;
    }

    $connect = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAMA);

    if (!$connect) {
        die('Koneksi database gagal: ' . mysqli_connect_error());
    }

    mysqli_set_charset($connect, 'utf8');
    return $connect;
}

function koneksi_tutup() {
    global $connect;

    if ($connect instanceof mysqli) {
        mysqli_close($connect);
        $connect = null;
    }
}

koneksi_buka();
?>
