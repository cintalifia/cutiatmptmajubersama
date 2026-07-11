<?php

function paginate_one($reload, $page, $tpages)
{
    $firstlabel = "&laquo;";
    $prevlabel  = "&lsaquo;";
    $nextlabel  = "&rsaquo;";
    $lastlabel  = "&raquo;";

    $out = '<ul class="pagination">';

    if ($page > 1) {
        $out .= '<li><a href="'.$reload.'">'.$firstlabel.'</a></li>';
    } else {
        $out .= '<li><span>'.$firstlabel.'</span></li>';
    }

    if ($page == 1) {
        $out .= '<li><span>'.$prevlabel.'</span></li>';
    } else {
        $out .= '<li><a href="'.$reload.'&page='.($page-1).'">'.$prevlabel.'</a></li>';
    }

    $out .= '<li><span class="current">Halaman '.$page.' dari '.$tpages.'</span></li>';

    if ($page < $tpages) {
        $out .= '<li><a href="'.$reload.'&page='.($page+1).'">'.$nextlabel.'</a></li>';
    } else {
        $out .= '<li><span>'.$nextlabel.'</span></li>';
    }

    if ($page < $tpages) {
        $out .= '<li><a href="'.$reload.'&page='.$tpages.'">'.$lastlabel.'</a></li>';
    } else {
        $out .= '<li><span>'.$lastlabel.'</span></li>';
    }

    $out .= '</ul>';

    return $out;
}

function menunya()
{
    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }

?>

<style>

.menu,
.menu ul{
    list-style:none;
    padding:0;
    margin:0;
}

.menu li{
    margin-bottom:8px;
}

.menu li a{
    display:block;
    padding:12px 15px;
    color:#fff;
    text-decoration:none;
    border-radius:12px;
    transition:.3s;
    font-weight:500;
}

.menu > li > a{
    background:rgba(255,255,255,.15);
}

.menu > li > a:hover{
    background:#fff;
    color:#2563eb;
}

.menu ul{
    display:none;
    margin-top:5px;
    padding-left:12px;
}

.menu ul li a{
    background:rgba(255,255,255,.08);
    font-size:14px;
}

.menu ul li a:hover{
    background:#fff;
    color:#2563eb;
}

.pagination{
    list-style:none;
    display:flex;
    gap:5px;
    margin-top:15px;
}

.pagination li a,
.pagination li span{
    display:block;
    padding:8px 12px;
    background:#fff;
    border:1px solid #ddd;
    text-decoration:none;
    border-radius:8px;
}

.current{
    background:#2563eb !important;
    color:#fff !important;
}

</style>

<ul class="menu">

<?php
if ($_SESSION['username'] == 'admin') {
?>

<li>
    <a href="#">📁 Master</a>
    <ul>
        <li><a href="../karyawan/">👥 Tambah Pegawai</a></li>
        <li><a href="../jeniscuti/">📄 Tambah Jenis Cuti</a></li>
    </ul>
</li>

<li>
    <a href="#">📅 Cuti</a>
    <ul>
        <li><a href="../pengajuancuti/">Pengajuan Cuti</a></li>
        <li><a href="../cekpengajuancuti/">Cek Pengajuan Cuti</a></li>
        <li><a href="../jadwalcuti/">Jadwal Cuti</a></li>
        <li><a href="../sisacuti/">Sisa Cuti</a></li>
    </ul>
</li>

<li>
    <a href="#">✔ Konfirmasi</a>
    <ul>
        <li><a href="../approval/">Approval Cuti</a></li>
        <li><a href="../proses/">Proses Form Cuti</a></li>
    </ul>
</li>

<?php
}
elseif ($_SESSION['level'] == 'Staff') {
?>

<li>
    <a href="#">📅 Cuti</a>
    <ul>
        <li><a href="../pengajuancuti/">Pengajuan Cuti</a></li>
        <li><a href="../cekpengajuancuti/">Cek Pengajuan Cuti</a></li>
        <li><a href="../jadwalcuti/">Jadwal Cuti</a></li>
        <li><a href="../sisacuti/">Sisa Cuti</a></li>
    </ul>
</li>

<?php
}
elseif ($_SESSION['level'] == 'Manager') {
?>

<li>
    <a href="#">📅 Cuti</a>
    <ul>
        <li><a href="../pengajuancuti/">Pengajuan Cuti</a></li>
        <li><a href="../cekpengajuancuti/">Cek Pengajuan Cuti</a></li>
        <li><a href="../jadwalcuti/">Jadwal Cuti</a></li>
        <li><a href="../sisacuti/">Sisa Cuti</a></li>
    </ul>
</li>

<?php
}
elseif ($_SESSION['level'] == 'General Manager' || $_SESSION['level'] == 'Direktur') {
?>

<li>
    <a href="#">📅 Cuti</a>
    <ul>
        <li><a href="../pengajuancuti/">Pengajuan Cuti</a></li>
        <li><a href="../cekpengajuancuti/">Cek Pengajuan Cuti</a></li>
        <li><a href="../jadwalcuti/">Jadwal Cuti</a></li>
        <li><a href="../sisacuti/">Sisa Cuti</a></li>
    </ul>
</li>

<?php
}
?>

<li>
    <a href="#">⚙ Pengguna</a>
    <ul>
        <li><a href="../gantipassword/">🔑 Ganti Password</a></li>
        <li><a href="../login/logout.php">🚪 Logout</a></li>
    </ul>
</li>

</ul>

<script src="../js/jquery.min.js"></script>

<script>
$(function(){

    $('.menu > li > ul').hide();

    $('.menu > li > a').click(function(e){

        if($(this).next('ul').length){

            e.preventDefault();

            $('.menu > li > ul')
            .not($(this).next())
            .slideUp();

            $(this).next().slideToggle();
        }

    });

});
</script>

<?php
}
?>
