<?php
$keyword = $_GET['keyword'] ?? $_POST['keyword'] ?? '';
header('Location: index.php?keyword=' . urlencode($keyword));
exit;
?>
