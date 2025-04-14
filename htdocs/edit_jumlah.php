<?php

//Menghubungkan ke database
require 'func.php';

if (!isset($_GET['action'])) {
    echo "Aksi tidak ditemukan!";
    exit;
}

$action = $_GET['action'];

if ($action === 'edit_jumlah') {
    if (!isset($_GET['id']) || !isset($_GET['jumlah'])) {
        echo "ID atau jumlah tidak ditemukan!";
        exit;
    }

    $idProduk = $_GET['id'];
    $jumlahBaru = (int) $_GET['jumlah'];

    // Ambil harga satuan produk
    $q = $db->prepare("SELECT harga FROM produk WHERE id = ?");
    $q->execute([$idProduk]);
    $produk = $q->fetch();

    if (!$produk) {
        echo "Produk tidak ditemukan!";
        exit;
    }

    $hargaSatuan = $produk['harga'];
    $totalHargaBaru = $jumlahBaru * $hargaSatuan;

    // Update jumlah dan total harga di detail_pemesanan
    $update = $db->prepare("UPDATE detail_pemesanan SET jumlah = ?, harga = ? WHERE idproduk = ?");
    $update->execute([$jumlahBaru, $totalHargaBaru, $idProduk]);

    echo "Jumlah dan harga berhasil diperbarui!";
}

?>

