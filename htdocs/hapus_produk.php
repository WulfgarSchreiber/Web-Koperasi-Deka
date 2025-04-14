<?php 
    include ("func.php"); // Menghubungkan ke database

    // Fungsi untuk menghapus produk pada halaman detail pemesanan
    if (!isset($_GET['id']) || !isset($_GET['idpemesanan'])) {
        echo "ID produk atau ID pemesanan tidak ditemukan!";
        exit;
    }

    $id = $_GET['id'];
    $idpemesanan = $_GET['idpemesanan'];

    // Hapus detail pemesanan
    $qDelete = $db->prepare("DELETE FROM detail_pemesanan WHERE id = ?");
    $qDelete->execute([$id]);

    // Cek apakah penghapusan berhasil
    if ($qDelete->rowCount() > 0) {
        echo "Produk berhasil dihapus dari detail pemesanan.";
    } else {
        echo "Gagal menghapus produk. Mungkin produk tidak ditemukan.";
    }

    // Redirect kembali ke halaman detail pemesanan
    header("Location: detail_pemesanan.php?idpemesanan=" . $idpemesanan);
    exit;
?>