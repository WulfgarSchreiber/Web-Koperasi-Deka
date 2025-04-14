<?php
// Menghubungkan ke database
include 'config.php';


// Pastikan username ada dalam sesi
if (!isset($_SESSION['admin'])) {
    echo "<script>alert('Anda harus login untuk melakukan pemesanan!'); window.location.href='login.php';</script>";
    exit();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Tambah ke keranjang
if (isset($_GET['action']) && $_GET['action'] == "add") {
    $id = intval($_GET['namaproduk']);
    $result = $conn->query("SELECT * FROM produk_user WHERE namaproduk = $id");
    $product = $result->fetch_assoc();

    if ($product) {
        $_SESSION['cart'][$id] = [
            "namaproduk" => $product['namaproduk'],
            "harga" => $product['harga'],
            "gambar" => $product['gambar'],
            "stok" => isset($_SESSION['cart'][$id]) ? $_SESSION['cart'][$id]['stok'] + 1 : 1
        ];
    }
    header("Location: jual.php");
}

?>