<?php
    session_start();
    include("func.php"); // Menghubungkan ke database
    
    if (!isset($_SESSION['admin'])) {
        echo "<script>alert('Anda harus login untuk menjual produk!'); window.location.href='login.php';</script>";
        exit();
    }
    
    // Ambil username dari sesi
    $username = $_SESSION['admin'];
    
    if (!empty($_GET["id"])) {
        $id = $_GET["id"];
        $q = $db->prepare("SELECT * FROM jual WHERE produk=?");
        $q->execute([$id]);
        
        // Jika produk ada di database, maka tampilkan datanya
        if ($q->rowCount() > 0) {
            $r = $q->fetch();
            $user = $r["user"];
            $produk = $r["produk"];
            $category = $r["category"];
            $tersedia = $r["tersedia"];
            $hargajual = $r["hargajual"];
            $harga = $r["harga"];
            $profit = $r["profit"];
            $image = $r["image"];
            $button = "ubah";
        } else {
            $_SESSION["notif"] = "Maaf, Data tidak ditemukan";
            header("Location: jual.php");
            die();
        }
    
    // Fungsi untuk perintah atau simpan jika data produk kosong
    } else {
        $user = "";
        $produk = "";
        $category = "";
        $tersedia = "";
        $hargajual = "";
        $harga = "";
        $profit = "";
        $image = "";
        $button = "penjualan";
    }
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Jual Produk</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="#">Jual Produk Anda</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="jual.php">Kembali</a>
                </li>
            </ul>
        </div>
    </nav>
    
    <main role="main" class="container">
        <div class="jumbotron">
            <h1>Data Barang</h1>

            <!-- Form Menjual Produk -->
            <form method="POST" action="form.action.php" enctype="multipart/form-data">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">User</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="user" value="<?php echo $username; ?>" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Nama Produk</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="produk" required value="<?php echo $produk; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Kategori</label>
                    <div class="col-sm-10">
                        <input type="radio" name="category" value="Produk Dua Kelinci" <?php if($category=='Produk Dua Kelinci') echo 'checked'; ?>> Produk Dua Kelinci<br>
                        <input type="radio" name="category" value="Peralatan Rumah Tangga" <?php if($category=='Peralatan Rumah Tangga') echo 'checked'; ?>> Peralatan Rumah Tangga<br>
                        <input type="radio" name="category" value="Lainnya" <?php if($category=='Lainnya') echo 'checked'; ?>> Lainnya
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Jumlah Stok</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" name="tersedia" required value="<?php echo $tersedia; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Harga (USER)</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="harga" name="harga" value="<?php echo $harga; ?>" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Profit Koperasi</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="profit" name="profit" value="<?php echo $profit; ?>" required >
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Harga /pcs</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="hargajual" name="hargajual" required value="<?php echo $hargajual; ?>" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Gambar</label>
                    <div class="col-sm-10">
                        <input type="file" required class="form-control" name="image" accept="image/*">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary" name="<?php echo $button; ?>">Simpan Data</button>
                    </div>
                </div>
            </form>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.bundle.min.js"></script>
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
</body>
</html>
 