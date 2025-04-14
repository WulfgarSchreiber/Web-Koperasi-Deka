<?php

    // Menghubungkan ke database
    include ("func.php");
    require 'sessionadmin.php';
    
    if (!isset($_GET['id'])) {
        echo "ID produk tidak ditemukan.";
        exit;
    }

    // Mengambil data dari produk user
    $id = $_GET['id'];
    $q = $db->prepare("SELECT * FROM produk_user WHERE id = ?");
    $q->execute([$id]);
    $produk = $q->fetch();

    if (!$produk) {
        echo "Produk tidak ditemukan.";
        exit;
    }

    //Ambil ID produk dari database produk
    $qProduk = $db->prepare("SELECT id FROM produk WHERE nama_produk = ? AND kategori = ?");
    $qProduk->execute([$produk['namaproduk'], $produk['kategori']]);
    $produkData = $qProduk->fetch();
    $produkId = $produkData ? $produkData['id'] : null;

    //Update pada produk user
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user = $_POST['user'];
        $namaproduk = $_POST['namaproduk'];
        $kategori = $_POST['kategori'];
        $hargajual = str_replace('.', '', $_POST['hargajual']);
        $harga = str_replace('.', '', $_POST['harga']);
        $profit = str_replace('.', '', $_POST['profit']);

        $update = $db->prepare("UPDATE produk_user SET user = ?, namaproduk = ?, kategori = ?, hargajual = ?, harga = ?, profit = ? WHERE id = ?");
        $update->execute([$user, $namaproduk, $kategori, $hargajual, $harga, $profit, $id]);

        //Update harga pada bagian produk
        $updateHarga = $db->prepare("UPDATE produk SET harga = ?, nama_produk = ?, kategori = ? WHERE id = ?");
        $updateHarga->execute([$hargajual, $namaproduk, $kategori, $produkId]);
        
        header('Location: produkuser.php');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    <link href="css/styles.css" rel="stylesheet" />
</head>
<body>
    <div class="container">
        <h1>Edit Produk</h1>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <div class="form-group">
                <label>User</label>
                <input type="text" name="user" class="form-control" value="<?php echo htmlspecialchars($produk['user']); ?>" required readonly>
            </div>
            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="namaproduk" class="form-control" value="<?php echo htmlspecialchars($produk['namaproduk']); ?>" required>
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori" class="form-control" required>
                    <option value="Produk Dua Kelinci" <?php echo ($produk['kategori'] === "Produk Dua Kelinci") ? "selected" : ""; ?>>Produk Dua Kelinci</option>
                    <option value="Peralatan Rumah Tangga" <?php echo ($produk['kategori'] === "Peralatan Rumah Tangga") ? "selected" : ""; ?>>Peralatan Rumah Tangga</option>
                    <option value="Lainnya" <?php echo ($produk['kategori'] === "Lainnya") ? "selected" : ""; ?>>Lainnya</option>
                </select>
            </div>
            <div class="form-group">
                <label>Harga Jual</label>
                <input type="text" id="hargajual" name="hargajual" class="form-control" value="<?php echo number_format($produk['hargajual'], 0, ',', '.'); ?>" required readonly>
            </div>
            <div class="form-group">
                <label>Harga (USER)</label>
                <input type="text" id="harga" name="harga" class="form-control" value="<?php echo number_format($produk['harga'], 0, ',', '.'); ?>" required>
            </div>
            <div class="form-group">
                <label>Profit Koperasi</label>
                <input type="text" id="profit" name="profit" class="form-control" value="<?php echo number_format($produk['profit'], 0, ',', '.'); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="produkuser.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</body>
<!-- Menambahkan pemisah ribuan pada mata uang -->
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
