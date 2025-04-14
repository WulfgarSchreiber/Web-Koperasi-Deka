<?php

// Menghubungkan ke database
include 'config.php';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<script>alert('Keranjang belanja kosong!'); window.location.href='keranjang.php';</script>";
    exit();
}

// Memaastikan user dalam sesi
if (!isset($_SESSION['admin'])) {
    echo "<script>alert('Anda harus login untuk melakukan pemesanan!'); window.location.href='login.php';</script>";
    exit();
}

// Mulai transaksi database
mysqli_begin_transaction($conn);

try {
    $total_jumlah = 0;
    $harga_keseluruhan = 0;

    // Hitung total jumlah dan harga keseluruhan
    foreach ($_SESSION['cart'] as $id => $item) {
        $total_jumlah += intval($item['stok']);
        $harga_keseluruhan += floatval($item['stok']) * floatval($item['harga']);
    }

    // Ambil username dari sesi
    $username = $_SESSION['admin'];
    $status = "Belum Bayar";

    // Masukkan data ke tabel pemesanan
    $query = "INSERT INTO pemesanan (userid, totalproduk, harga, status) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sids", $username, $total_jumlah, $harga_keseluruhan, $status);
    mysqli_stmt_execute($stmt);

    // Ambil idpemesanan yang baru saja dibuat
    $idpemesanan = mysqli_insert_id($conn);

    // Masukkan data ke tabel detail pemesanan
    foreach ($_SESSION['cart'] as $id => $item) {
        $product_id = intval($id);
        $quantity = intval($item['stok']);
        $price = floatval($item['harga']);
        $total_price = $quantity * $price;

        $query = "INSERT INTO detail_pemesanan (idpemesanan, idproduk, jumlah, harga) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "iiid", $idpemesanan, $product_id, $quantity, $total_price);
        mysqli_stmt_execute($stmt);
    }

    // Jika berhasil, commit transaksi
    mysqli_commit($conn);

    // Kosongkan keranjang
    $_SESSION['cart'] = [];
    setcookie("shopping_cart", "", time() - 3600, "/");

    echo "<script>alert('Pesanan berhasil diproses!'); window.location.href='produk.php';</script>";
} catch (Exception $e) {
    mysqli_rollback($conn);
    echo "<script>alert('Terjadi kesalahan! Silakan coba lagi.'); window.location.href='keranjang.php';</script>";
}
?>
 