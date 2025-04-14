<?php

// Koneksi ke database
require 'config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Ambil username dari sesi
$user_id = $_SESSION['admin'];

if (!isset($_GET['idjual'])) {
    echo "ID produk tidak ditemukan.";
    exit();
}

// Mengambil data produk yang ingin dijual berdasarkan username siapa yang menjual
$idjual = $_GET['idjual'];
$query = "SELECT * FROM jual WHERE produk = '$idjual' AND user = '$user_id'";
$result = mysqli_query($conn, $query);
$produk = mysqli_fetch_assoc($result);

if (!$produk) {
    echo "Produk tidak ditemukan atau tidak memiliki izin untuk mengedit.";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2 class="mt-4">Edit Produk</h2>

        <!-- FORM -->
        <form method="POST" action="proses.edit.penjualan.php" enctype="multipart/form-data">
            <input type="hidden" name="idjual" value="<?= $produk['idjual']; ?>">
            
            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" class="form-control" name="produk" value="<?= $produk['produk']; ?>" required>
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <select name="category" class="form-control">
                    <option value="Produk Dua Kelinci" <?= ($produk['category'] == "Produk Dua Kelinci") ? "selected" : ""; ?>>Produk Dua Kelinci</option>
                    <option value="Peralatan Rumah Tangga" <?= ($produk['category'] == "Peralatan Rumah Tangga") ? "selected" : ""; ?>>Peralatan Rumah Tangga</option>
                    <option value="Lainnya" <?= ($produk['category'] == "Lainnya") ? "selected" : ""; ?>>Lainnya</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Harga Jual</label>
                <input type="number" class="form-control" id="hargajual" name="hargajual" value="<?= number_format($produk['hargajual'], 0, ',', '.'); ?>" readonly required>
            </div>

            <div class="form-group">
                <label>Harga (USER)</label>
                <input type="text" class="form-control" id="harga" name="harga" value="<?= number_format($produk['harga'], 0, ',', '.'); ?>" required>
            </div>

            <div class="form-group">
                <label>Profit Koperasi</label>
                <input type="text" class="form-control" id="profit" name="profit" value="<?= number_format($produk['profit'], 0, ',', '.'); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Stok Tersedia</label>
                <input type="number" class="form-control" name="tersedia" value="<?= $produk['tersedia']; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Gambar Saat Ini</label><br>
                <img src="images/<?= $produk['image']; ?>" width="150" alt="Gambar Produk">
            </div>
            
            <div class="form-group">
                <label>Ganti Gambar</label>
                <input type="file" name="image" class="form-control">
                <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar.</small>
            </div>
            
            <button type="submit" class="btn btn-primary" name="ubah">Simpan Perubahan</button>
            <a href="jual.php" class="btn btn-secondary">Batal</a>
        </form>
        <!-- FORM -->
         
    </div>
</body>
    <!-- Fungsi untuk menambahkan pemisah ribuan pada mata uang -->
    <script>
        function formatRupiah(angka) {
            return angka.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function updateHargaJual() {
            let harga = document.getElementById('harga').value.replace(/\./g, '') || 0;
            let profit = document.getElementById('profit').value.replace(/\./g, '') || 0;
            
            harga = parseInt(harga);
            profit = parseInt(profit);

            let hargajual = harga + profit;

            document.getElementById('hargajual').value = formatRupiah(hargajual.toString());
        }

        document.getElementById('harga').addEventListener('input', function(e) {
            e.target.value = formatRupiah(e.target.value);
            updateHargaJual();
        });

        document.getElementById('profit').addEventListener('input', function(e) {
            e.target.value = formatRupiah(e.target.value);
            updateHargaJual();
        });
    </script>
</html>
