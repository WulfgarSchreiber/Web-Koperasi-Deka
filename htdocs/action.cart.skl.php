<?php

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
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM produk WHERE id = $id");
    $product = $result->fetch_assoc();

    if ($product) {
        $_SESSION['cart'][$id] = [
            "nama_produk" => $product['nama_produk'],
            "harga" => $product['harga'],
            "gambar" => $product['gambar'],
            "stok" => isset($_SESSION['cart'][$id]) ? $_SESSION['cart'][$id]['stok'] + 1 : 1
        ];
    }
    header("Location: skl.php");
}

?>