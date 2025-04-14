<?php
// Koneksi ke database
$host = "localhost";
$user = "root";
$password = "";
$database = "deka"; // Ganti dengan nama database Anda

$conn = new mysqli($host, $user, $password, $database);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Cek apakah ada data yang dikirim
if (isset($_POST['idproduk']) && !empty($_POST['idproduk'])) {
    $idproduk = $_POST['idproduk'];
    $keterangan = $_POST['keterangan'];
    $tanggal = $_POST['tanggal'];
    // Simpan hanya ID produk ke tabel pesanan (atau tabel lain yang sesuai)
    $sql = "INSERT INTO masuk (idproduk, keterangan, tanggal) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idproduk);

    if ($stmt->execute()) {
        echo "Data berhasil disimpan.";
    } else {
        echo "Gagal menyimpan data: " . $conn->error;
    }

    $stmt->close();
    } else {
        echo "Silakan pilih produk terlebih dahulu.";
    }

// Tutup koneksi
$conn->close();
?>
