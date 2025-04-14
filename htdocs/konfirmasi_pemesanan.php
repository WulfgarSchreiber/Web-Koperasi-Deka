<?php
include("func.php");

if (isset($_GET['idpemesanan'])) {
    $idpemesanan = $_GET['idpemesanan'];
    
    // Cek apakah semua produk di detail_pemesanan sudah terkonfirmasi
    $qCekKonfirmasi = $db->prepare("SELECT COUNT(*) FROM detail_pemesanan WHERE idpemesanan = ? AND status != 'Terkonfirmasi'");
    $qCekKonfirmasi->execute([$idpemesanan]);
    $belumTerkonfirmasi = $qCekKonfirmasi->fetchColumn();

    if ($belumTerkonfirmasi == 0) {
        // Semua produk sudah terkonfirmasi, langsung pindahkan pesanan ke pesanan_selesai
        $qPemesanan = $db->prepare("SELECT * FROM pemesanan WHERE idpemesanan = ?");
        $qPemesanan->execute([$idpemesanan]);
        $pemesanan = $qPemesanan->fetch();

        if ($pemesanan) {
            $qInsertSelesai = $db->prepare("INSERT INTO pesanan_selesai (idpemesanan, user, totalproduk, harga, tanggal, status) VALUES (?, ?, ?, ?, ?, ?)");
            $qInsertSelesai->execute([
                $pemesanan['idpemesanan'],
                $pemesanan['userid'],
                $pemesanan['totalproduk'],
                $pemesanan['harga'],
                $pemesanan['tanggal'],
                'Lunas'
            ]);

            // Hapus data dari tabel pemesanan setelah dipindahkan
            $qDeletePemesanan = $db->prepare("DELETE FROM pemesanan WHERE idpemesanan = ?");
            $qDeletePemesanan->execute([$idpemesanan]);
        }
    } else {
        // Proses seperti biasa jika ada produk yang belum terkonfirmasi
        $qDetail = $db->prepare("SELECT * FROM detail_pemesanan WHERE idpemesanan = ?");
        $qDetail->execute([$idpemesanan]);
        $details = $qDetail->fetchAll();
        
        $stokCukup = true;
        
        if ($details) {
            foreach ($details as $detail) {
                $idproduk = $detail['idproduk'];
                $jumlah = $detail['jumlah'];

                // Cek stok produk sebelum mengurangi
                $qCekStok = $db->prepare("SELECT stok FROM produk WHERE id = ?");
                $qCekStok->execute([$idproduk]);
                $stok = $qCekStok->fetchColumn();

                if ($stok < $jumlah) {
                    $stokCukup = false;
                    echo "<script>alert('Stok tidak mencukupi untuk produk yang dipesan'); window.location.href='pemesanan.php';</script>";
                    break;
                }
            }

            if ($stokCukup) {
                foreach ($details as $detail) {
                    $idproduk = $detail['idproduk'];
                    $jumlah = $detail['jumlah'];
                    $tanggal = date('Y-m-d H:i:s');
                    $keterangan = "Barang Terjual";
                    $adminid = $_SESSION['owner'];

                    $queryAdmin = $db->prepare("SELECT nama FROM admin WHERE adminname = :username");
                    $queryAdmin->bindParam(':username', $adminid);
                    $queryAdmin->execute();
                    $adminData = $queryAdmin->fetch();
                    $adminName = $adminData ? $adminData['nama'] : 'Tidak Diketahui';

                    $qUpdate = $db->prepare("UPDATE produk SET stok = stok - ? WHERE id = ?");
                    $qUpdate->execute([$jumlah, $idproduk]);

                    $qUpdateUser = $db->prepare("UPDATE produk_user SET stok = stok - ? WHERE namaproduk = ?");
                    $qUpdateUser->execute([$jumlah, $idproduk]);

                    $qInsert = $db->prepare("INSERT INTO keluar (nproduk, keterangan, pjawab, jumlah) VALUES (?, ?, ?, ?)");
                    $qInsert->execute([$idproduk, $keterangan, $adminName, $jumlah]);
                }

                $qPemesanan = $db->prepare("SELECT * FROM pemesanan WHERE idpemesanan = ?");
                $qPemesanan->execute([$idpemesanan]);
                $pemesanan = $qPemesanan->fetch();

                if ($pemesanan) {
                    $qInsertSelesai = $db->prepare("INSERT INTO pesanan_selesai (idpemesanan, user, totalproduk, harga, tanggal, status) VALUES (?, ?, ?, ?, ?, ?)");
                    $qInsertSelesai->execute([
                        $pemesanan['idpemesanan'],
                        $pemesanan['userid'],
                        $pemesanan['totalproduk'],
                        $pemesanan['harga'],
                        $pemesanan['tanggal'],
                        'Lunas'
                    ]);

                    // Update semua produk di detail_pemesanan menjadi terkonfirmasi
                    $qUpdateKonfirmasi = $db->prepare("UPDATE detail_pemesanan SET status = 'Terkonfirmasi' WHERE idpemesanan = ?");
                    $qUpdateKonfirmasi->execute([$idpemesanan]);

                    $qDeletePemesanan = $db->prepare("DELETE FROM pemesanan WHERE idpemesanan = ?");
                    $qDeletePemesanan->execute([$idpemesanan]);
                }
            }
        }
    }

    echo "<script>alert('Pesanan telah terkonfirmasi!'); window.location.href='pemesanan.php';</script>";
    exit();
} else {
    echo "ID pemesanan tidak ditemukan!";
}
?>
