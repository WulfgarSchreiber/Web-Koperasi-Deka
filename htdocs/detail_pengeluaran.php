<?php
require 'config.php';
require 'sessionadmin.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID pengeluaran tidak ditemukan.");
}

$id_pengeluaran = intval($_GET['id']);

// Ambil data pengeluaran berdasarkan ID
$query = "SELECT k.idkeluar, k.tanggal, k.nproduk, p.nama_produk, k.jumlah, k.keterangan, k.pjawab
          FROM keluar k 
          JOIN produk p ON k.nproduk = p.id 
          WHERE k.idkeluar = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_pengeluaran);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die("Data pengeluaran tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengeluaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3><span style="font-family: 'Times New Roman'">Detail Pengeluaran</span></h3>
        </div>
        <div class="card-body">
            <table class="table">
                <tr>
                    <th>ID Produk</th>
                    <td><?= htmlspecialchars($data['nproduk']) ?></td>
                </tr>
                <tr>
                    <th>Nama Produk</th>
                    <td><?= htmlspecialchars($data['nama_produk']) ?></td>
                </tr>
                <tr>
                    <th>Jumlah Keluar</th>
                    <td><?= htmlspecialchars($data['jumlah']) ?></td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td><?= htmlspecialchars($data['tanggal']) ?></td>
                </tr>
                <tr>
                    <th>Keterangan</th>
                    <td><?= htmlspecialchars($data['keterangan']) ?></td>
                </tr>
                <tr>
                    <th>Penanggung Jawab</th>
                    <td><?= htmlspecialchars($data['pjawab']) ?></td>
                </tr>
            </table>
            <a href="kartu_stok.php" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
