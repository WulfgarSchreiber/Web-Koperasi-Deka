<?php

// Menghubungkan ke database
include("func.php");

$q = $db->query("SELECT idpemesanan, status FROM pemesanan");
$data = [];

while ($row = $q->fetch(PDO::FETCH_ASSOC)) {
    $data[$row['idpemesanan']] = $row['status'];
}



echo json_encode($data);

?>

