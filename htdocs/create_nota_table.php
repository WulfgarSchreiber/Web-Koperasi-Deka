<?php

// Membuat tabel nota apabila belum ada
include("func.php");

try {
    // Format membuat tabel
    $sql = "CREATE TABLE IF NOT EXISTS nota (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        idpemesanan INT(11) NOT NULL,
        namaproduk VARCHAR(255) NOT NULL,
        jumlah INT(11) NOT NULL,
        harga DECIMAL(10,2) NOT NULL,
        tunai DECIMAL(10,2) NOT NULL,
        kembali DECIMAL(10,2) NOT NULL,
        tanggal_cetak DATETIME NOT NULL,
        INDEX (idpemesanan)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    
    $db->exec($sql);
    
    echo "Tabel 'nota' berhasil dibuat.";
    
} catch(PDOException $e) {
    echo "Error membuat tabel: " . $e->getMessage();
}