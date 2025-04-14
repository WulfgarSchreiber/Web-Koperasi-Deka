<?php
header('Content-Type: application/json');

// Menghubungkan ke database
require 'config.php';

// Mengambil data produk terjual berdasarkan bulan dalam satu tahun
$year = date("Y"); // Ambil tahun saat ini
$sql = "SELECT MONTH(tanggal) AS bulan, SUM(jumlah) AS total_terjual
        FROM keluar
        WHERE keterangan = 'Barang Terjual' 
        AND YEAR(tanggal) = $year
        GROUP BY MONTH(tanggal)
        ORDER BY bulan";

$result = $conn->query($sql);

$data = array_fill(0, 12, 0); // Inisialisasi array dengan 12 bulan (Jan - Des)

while ($row = $result->fetch_assoc()) {
    $bulanIndex = (int)$row["bulan"] - 1; // Ubah bulan ke index array (0 = Jan, 11 = Des)
    $data[$bulanIndex] = (int)$row["total_terjual"];
}

$conn->close();

echo json_encode($data);
?>
