<?php

require 'config.php';

// Pastikan user sudah login
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['admin'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_jual = $_POST['idjual'];
    $produk = $_POST['produk'];
    $harga_jual = $_POST['hargajual'];
    $tersedia = $_POST['tersedia'];

    // Membersihkan input angka dari pemisah ribuan
    $harga = str_replace(".", "", $_POST['harga']);
    $profit = str_replace(".", "", $_POST['profit']);
    $hargajual = str_replace(".", "", $_POST['hargajual']);
    
    // Cek apakah ada file gambar yang diupload
    if (!empty($_FILES['image']['name'])) {
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_path = "images/" . $image_name;
        move_uploaded_file($image_tmp, $image_path);

        // Update dengan gambar baru
        $sql = "UPDATE jual SET produk=?, tersedia=?, hargajual=?, harga=?, profit=? image=? WHERE idjual=? AND user=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("siiiissi", $produk, $tersedia, $hargajual, $harga, $profit, $image_name, $id_jual, $user_id);
    } else {
        // Update tanpa mengganti gambar
        $sql = "UPDATE jual SET produk=?, tersedia=?, hargajual=?, harga=?, profit=? WHERE idjual=? AND user=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("siiiisi", $produk, $tersedia, $hargajual, $harga, $profit, $id_jual, $user_id);
    }
    
    if ($stmt->execute()) {
        echo "<script>alert('Produk berhasil diperbarui!'); window.location.href='jual.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat memperbarui produk!'); window.history.back();</script>";
    }
    
    $stmt->close();
    $conn->close();
} else {
    header("Location: jual.php");
    exit();
}
