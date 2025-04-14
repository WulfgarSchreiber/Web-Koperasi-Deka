<?php
// Menghubungkan ke database
include 'config.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Cek stok produk sebelum menambahkan
function cek_stok($id) {
    global $conn; // Pastikan koneksi ke database tersedia
    $query = mysqli_query($conn, "SELECT stok FROM produk WHERE id = $id");
    $data = mysqli_fetch_assoc($query);
    return $data['stok'];
}

// Tambah atau kurangi jumlah produk dalam keranjang
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);

    if ($_GET['action'] == "add") {
        $stok_tersedia = cek_stok($id);
        
        if ($_SESSION['cart'][$id]['stok'] < $stok_tersedia) {
            $_SESSION['cart'][$id]['stok']++;
        } else {
            echo "<script>alert('Jumlah produk sudah mencapai stok maksimal!');</script>";
        }
        
        header("Location: keranjang.php");
        exit();
    } elseif ($_GET['action'] == "remove") {
        if ($_SESSION['cart'][$id]['stok'] > 1) {
            $_SESSION['cart'][$id]['stok']--;
        } else {
            unset($_SESSION['cart'][$id]);
        }
        
        header("Location: keranjang.php");
        exit();
    }
}

// Simpan keranjang ke cookie (opsional, untuk sesi lebih lama)
setcookie("shopping_cart", json_encode($_SESSION['cart']), time() + (86400 * 7), "/");

// Tampilkan isi keranjang
$total = 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
</head>
<body>
<section class="h-100 h-custom">
    <div class="container h-100 py-5">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col" class="h5">Produk</th>
                                <th scope="col">Jumlah</th>
                                <th scope="col">Harga</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($_SESSION['cart'] as $id => $item) : 
                                $stok_tersedia = cek_stok($id); 
                            ?>
                                <tr>
                                    <th scope="row">
                                        <div class="d-flex align-items-center">
                                            <img src="images/<?= $item['gambar'] ?>" class="img-fluid rounded-3" style="width: 120px;" alt="<?= $item['nama_produk'] ?>">
                                            <div class="flex-column ms-4">
                                                <p class="mb-2"> <?= $item['nama_produk'] ?> </p>
                                            </div>
                                        </div>
                                    </th>
                                    <td class="align-middle">
                                        <div class="d-flex flex-row">
                                            <a href="keranjang.php?action=remove&id=<?= $id ?>" class="btn btn-link px-2">
                                                <i class="fa fa-minus"></i>
                                            </a>
                                            <input type="number" name="quantity" value="<?= $item['stok'] ?>" class="form-control form-control-sm text-center" style="width: 50px;" readonly>
                                            <a href="keranjang.php?action=add&id=<?= $id ?>" class="btn btn-link px-2 <?= ($item['stok'] >= $stok_tersedia) ? 'disabled' : '' ?>">
                                                <i class="fa fa-plus"></i>
                                            </a>
                                        </div>
                                        <small class="text-muted">Stok tersedia: <?= number_format($stok_tersedia) ?></small>
                                    </td>
                                    <td class="align-middle">
                                        <p class="mb-0" style="font-weight: 500;">Rp <?= number_format($item['harga'] * $item['stok']) ?></p>
                                    </td>
                                    <td class="align-middle">
                                        <a href="keranjang.php?action=remove&id=<?= $id ?>&full=true" class="btn btn-danger btn-sm">Hapus</a>
                                    </td>
                                </tr>
                                <?php $total += $item['harga'] * $item['stok']; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="card shadow-2-strong mb-5" style="border-radius: 16px;">
                    <div class="card-body p-4">
                        <?php
                        $total_jumlah = array_sum(array_column($_SESSION['cart'], 'stok'));
                        ?>
                        <div class="d-flex justify-content-between" style="font-weight: 500;">
                            <p class="mb-2">Total Produk</p>
                            <p class="mb-2"><?= $total_jumlah ?> Item</p>
                        </div>          
                        <hr class="my-4">
                        <div class="d-flex justify-content-between mb-4" style="font-weight: 500;">
                            <p class="mb-2">Total</p>
                            <p class="mb-2">Rp <?= number_format($total) ?></p>
                        </div>
                        <a href="konfirmasi.php" class="btn btn-primary btn-lg btn-block">Checkout</a>
                    </div>
                </div>
                <a href="produk.php">Kembali Belanja</a>
            </div>
        </div>
    </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
