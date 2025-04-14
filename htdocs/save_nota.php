<?php
include("func.php");

// Set content type to JSON
header('Content-Type: application/json');

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Ambil data ID pemesanan
$idpemesanan = isset($_POST['idpemesanan']) ? $_POST['idpemesanan'] : null;
$tunai = isset($_POST['tunai']) ? intval($_POST['tunai']) : 0;
$kembalian = isset($_POST['kembalian']) ? intval($_POST['kembalian']) : 0;

// Validate data
if (!$idpemesanan) {
    echo json_encode(['success' => false, 'message' => 'ID pemesanan tidak valid']);
    exit;
}

try {
    
    $db->beginTransaction();
    
    // Get detail pemesanan data
    $qDetail = $db->prepare("SELECT d.*, p.nama_produk FROM detail_pemesanan d JOIN produk p ON d.idproduk = p.id WHERE d.idpemesanan = ?");
    $qDetail->execute([$idpemesanan]);
    $details = $qDetail->fetchAll();
    
    if (empty($details)) {
        throw new Exception('Detail pemesanan tidak ditemukan');
    }
    
    // Memindahkan data ke tabel database nota
    $insertNota = $db->prepare("INSERT INTO nota (idpemesanan, namaproduk, jumlah, harga, tunai, kembali, tanggal_cetak) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    
    foreach ($details as $detail) {
        $insertNota->execute([
            $idpemesanan,
            $detail['nama_produk'],
            $detail['jumlah'],
            $detail['harga'],
            $tunai,
            $kembalian
        ]);
    }
    
    // Commit transaction
    $db->commit();
    
    // Return success
    echo json_encode(['success' => true, 'message' => 'Data nota berhasil disimpan']);
    
} catch (Exception $e) {
    // Rollback transaction on error
    $db->rollBack();
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}