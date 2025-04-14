<?php
include("func.php");

if (!isset($_GET['action']) || $_GET['action'] !== 'konfirmasi' || !isset($_GET['id'])) {
    echo "<script>alert('Aksi tidak valid!'); window.history.back();</script>";
    exit;
}

$idDetail = $_GET['id'];

// Ambil username admin dari sesi
$adminid = $_SESSION['owner'];

// Ambil nama lengkap admin berdasarkan username
$queryAdmin = $db->prepare("SELECT nama FROM admin WHERE adminname = :username");
$queryAdmin->bindParam(':username', $adminid);
$queryAdmin->execute();
$adminData = $queryAdmin->fetch();
$adminName = $adminData ? $adminData['nama'] : 'Tidak Diketahui';

// Ambil detail pemesanan
$qDetail = $db->prepare("SELECT d.idproduk, d.jumlah, p.nama_produk, p.stok, d.idpemesanan FROM detail_pemesanan d JOIN produk p ON d.idproduk = p.id WHERE d.id = ?");
$qDetail->execute([$idDetail]);
$detail = $qDetail->fetch();

if (!$detail) {
    echo "<script>alert('Detail pemesanan tidak ditemukan!'); window.history.back();</script>";
    exit;
}

// Cek stok
if ($detail['jumlah'] > $detail['stok']) {
    echo "<script>alert('Stok tidak mencukupi untuk produk: " . $detail['nama_produk'] . "'); window.history.back();</script>";
    exit;
}

// Catat ke tabel pengeluaran
$qInsert = $db->prepare("INSERT INTO keluar (nproduk, jumlah, keterangan, pjawab) VALUES (?, ?, ?, ?)");
$qInsert->execute([$detail['idproduk'], $detail['jumlah'], "Barang Terjual", $adminName]);

// Kurangi stok produk
$qUpdate = $db->prepare("UPDATE produk SET stok = stok - ? WHERE id = ?");
$qUpdate->execute([$detail['jumlah'], $detail['idproduk']]);

// Update status menjadi terkonfirmasi
$qConfirm = $db->prepare("UPDATE detail_pemesanan SET status = 'Terkonfirmasi' WHERE id = ?");
$qConfirm->execute([$idDetail]);

if (!isset($_GET['action'])) {
    echo "Aksi tidak ditemukan!";
    exit;
}

$action = $_GET['action'];

if ($action === 'sesuaikan' && isset($_GET['id'], $_GET['stok'], $_GET['hargaSatuan'])) {
    $id = $_GET['id'];
    $stok = (int)$_GET['stok'];
    $hargaSatuan = (int)$_GET['hargaSatuan'];

    // Perbarui jumlah pesanan dan harga total sesuai stok yang tersedia
    $qUpdate = $db->prepare("UPDATE detail_pemesanan SET jumlah = ?, harga = ? * ? WHERE id = ?");
    $qUpdate->execute([$stok, $stok, $hargaSatuan, $id]);

    if ($qUpdate) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "Aksi tidak valid!";
}

// Redirect kembali
header("Location: detail_pemesanan.php?idpemesanan=" . $detail['idpemesanan']);
exit;
?>
