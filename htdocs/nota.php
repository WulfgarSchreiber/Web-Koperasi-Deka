<?php 
    include ("func.php");

    if (!isset($_GET['idpemesanan'])) {
        echo "ID pemesanan tidak ditemukan!";
        exit;
    }
    
    $idpemesanan = $_GET['idpemesanan'];

    // Ambil username admin dari sesi
    $adminid = $_SESSION['owner'];

    // Ambil nama lengkap dan no telepon admin berdasarkan username
    $queryAdmin = $db->prepare("SELECT nama, telepon FROM admin WHERE adminname = :username");
    $queryAdmin->bindParam(':username', $adminid);
    $queryAdmin->execute();
    $adminData = $queryAdmin->fetch();
    $adminName = $adminData ? $adminData['nama'] : 'Tidak Diketahui';
    $adminPhone = $adminData ? $adminData['telepon'] : 'Tidak Diketahui';

    // Periksa apakah pesanan sudah lunas dan ada di tabel pesanan_selesai
    $qSelesai = $db->prepare("SELECT * FROM pesanan_selesai WHERE idpemesanan = ? AND status = 'Lunas'");
    $qSelesai->execute([$idpemesanan]);
    $pesanan_selesai = $qSelesai->fetch();

    if ($pesanan_selesai) {
        // Jika pesanan sudah selesai, ambil data dari tabel pesanan_selesai
        $status_pembayaran = $pesanan_selesai['status'];
        $tanggal_pesanan = $pesanan_selesai['tanggal'];

        $qDetail = $db->prepare("SELECT d.*, p.nama_produk FROM detail_pemesanan d 
                                JOIN produk p ON d.idproduk = p.id 
                                WHERE d.idpemesanan = ? 
                                ORDER BY d.tanggal");
        $qDetail->execute([$idpemesanan]);
        $details = $qDetail->fetchAll();
    } else {
        // Jika belum selesai, ambil data dari tabel pemesanan
        $q = $db->prepare("SELECT * FROM pemesanan WHERE idpemesanan = ?");
        $q->execute([$idpemesanan]);
        $pemesanan = $q->fetch();

        if (!$pemesanan) {
            echo "Data pemesanan tidak ditemukan!";
            exit;
        }

        $status_pembayaran = $pemesanan['status'];
        $tanggal_pesanan = $pemesanan['tanggal'];

        $qDetail = $db->prepare("SELECT d.*, p.nama_produk FROM detail_pemesanan d 
                                JOIN produk p ON d.idproduk = p.id 
                                WHERE d.idpemesanan = ? 
                                ORDER BY d.tanggal");
        $qDetail->execute([$idpemesanan]);
        $details = $qDetail->fetchAll();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?php echo $idpemesanan; ?></title>
    <link rel="stylesheet" href="css/invoice.css">
    <link href="css/styles.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
</head>
<body>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="pemesanan_selesai.php">&larr; Kembali</a></li>
    </ol>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="5">
                    <table>
                        <tr>
                            <td class="title">
                                <h2>Koperasi</h2><br>
                            </td>
                            <td>
                                Pesanan #: <?php echo $idpemesanan; ?><br>
                                Tanggal: <?php echo $tanggal_pesanan; ?><br>
                                Status: <strong><?php echo $status_pembayaran; ?></strong>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
				<td colspan="5">
					<table>
						<tr>
							<td>
								Koperasi Dua Kelinci<br>
								Hub.<br>
								
							</td>

							<td>
								<?php echo $adminName; ?><br>
								<?php echo $adminPhone; ?><br>
								
							</td>
						</tr>
					</table>
				</td>
			</tr>

            <tr class="heading">
                <td>No #</td>
                <td>Nama Produk</td>
                <td>Jumlah</td>
                <td></td>
                <td>Harga</td>
            </tr>
            
            <?php 
            $no = 1;
            $total = 0;
            foreach ($details as $detail) { 
                $subtotal = $detail['harga'];
                $total += $subtotal;
            ?>
            <tr class="item">
                <td><?php echo $no++; ?></td>
                <td><?php echo $detail['nama_produk']; ?></td>
                <td><?php echo $detail['jumlah']; ?></td>
                <td></td>
                <td>Rp<?php echo number_format($subtotal); ?></td>
            </tr>
            <?php } ?>
            
            <tr class="total">
                <td colspan="3"><strong>Total:</strong></td>
                <td colspan="2">Rp<space> <?php echo number_format($total); ?></td>
            </tr>
        </table>
        <div class="center-text">
            <br><button type="button" class="btn btn-sm btn-dark" onclick="window.print()">
                Cetak
            </button>
        </div>
    </div>
</body>
</html>
