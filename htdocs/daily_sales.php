<?php
header('Content-Type: application/json');

require 'config.php';

// Memilah data penjualan berdasarkan hari dalam seminggu
$sql = "SELECT DAYNAME(tanggal) as hari, SUM(jumlah) as total 
        FROM keluar 
        WHERE keterangan = 'Barang Terjual' 
        AND YEARWEEK(tanggal, 1) = YEARWEEK(CURDATE(), 1) 
        GROUP BY DAYOFWEEK(tanggal) 
        ORDER BY DAYOFWEEK(tanggal) ASC";

$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$conn->close();

echo json_encode($data);

?>
