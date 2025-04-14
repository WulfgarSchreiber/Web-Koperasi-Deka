<?php
include("func.php");

if (isset($_GET['idpemesanan'])) {
    $idpemesanan = $_GET['idpemesanan'];
    
    // Cek status konfirmasi di detail_pemesanan
    $qCekKonfirmasi = $db->prepare("SELECT COUNT(*) FROM detail_pemesanan WHERE idpemesanan = ? AND status != 'Terkonfirmasi'");
    $qCekKonfirmasi->execute([$idpemesanan]);
    $belumTerkonfirmasi = $qCekKonfirmasi->fetchColumn();

    if ($belumTerkonfirmasi > 0) {
        echo "<script>alert('Ada produk yang belum terkonfirmasi dalam pesanan ini. Tidak dapat melanjutkan.'); window.location.href='pemesanan.php';</script>";
        exit();
    }

    // Hitung total produk dan total harga dari detail_pemesanan
    $qTotal = $db->prepare("SELECT SUM(jumlah) AS totalproduk, SUM(harga) AS totalharga FROM detail_pemesanan WHERE idpemesanan = ?");
    $qTotal->execute([$idpemesanan]);
    $total = $qTotal->fetch();
    $totalProduk = $total['totalproduk'] ?? 0;
    $totalHarga = $total['totalharga'] ?? 0;

    // Ambil data pemesanan untuk dipindahkan ke pesanan_selesai
    $qPemesanan = $db->prepare("SELECT * FROM pemesanan WHERE idpemesanan = ?");
    $qPemesanan->execute([$idpemesanan]);
    $pemesanan = $qPemesanan->fetch();

    if ($pemesanan) {
        $qInsertSelesai = $db->prepare("INSERT INTO pesanan_selesai (idpemesanan, user, totalproduk, harga, tanggal, status) VALUES (?, ?, ?, ?, ?, ?)");
        $qInsertSelesai->execute([
            $pemesanan['idpemesanan'],
            $pemesanan['userid'],
            $totalProduk,
            $totalHarga,
            $pemesanan['tanggal'],
            'Lunas'
        ]);

        // Hapus data dari tabel pemesanan setelah dipindahkan
        $qDeletePemesanan = $db->prepare("DELETE FROM pemesanan WHERE idpemesanan = ?");
        $qDeletePemesanan->execute([$idpemesanan]);
    }

    echo "<script>alert('Pesanan telah terkonfirmasi!'); window.location.href='pemesanan.php';</script>";
    exit();
} else {
    echo "ID pemesanan tidak ditemukan!";
}
?>
