<?php

// KONFIRMASI PRODUK USER

include("func.php"); // Pastikan file ini berisi koneksi ke database

if (isset($_GET['idjual'])) {
    $idjual = $_GET['idjual'];

    // Ambil data dari tabel jual
    $q = $db->prepare("SELECT * FROM jual WHERE idjual = ?");
    $q->execute([$idjual]);
    $data = $q->fetch();

    if ($data) {
        // Periksa apakah nama produk sudah ada di tabel produk
        $check = $db->prepare("SELECT COUNT(*) FROM produk WHERE nama_produk = ?");
        $check->execute([$data['produk']]);
        $exists = $check->fetchColumn();

        if ($exists) {
            echo "<script>
                    alert('Produk dengan nama yang sama sudah ada!');
                    window.location.href='request.php'; 
                  </script>";
            exit();
        }

        // Pindahkan data ke tabel produk
        $insert = $db->prepare("INSERT INTO produk (nama_produk, kategori, stok, harga, gambar) VALUES (?, ?, ?, ?, ?)");
        $insert->execute([$data['produk'], $data['category'], $data['tersedia'], $data['hargajual'], $data['image']]);

        // Ambil ID produk yang baru dimasukkan
        $id_produk = $db->lastInsertId();

        // Pindahkan data ke tabel produk_user
        $insertUser = $db->prepare("INSERT INTO produk_user (user, namaproduk, kategori, hargajual, harga, profit, stok, gambar) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $insertUser->execute([$data['user'], $data['produk'], $data['category'], $data['hargajual'], $data['harga'], $data['profit'], $data['tersedia'], $data['image']]);

        // Ambil nama lengkap dari tabel user
        $userQuery = $db->prepare("SELECT nama FROM login WHERE username = ?");
        $userQuery->execute([$data['user']]);
        $userData = $userQuery->fetch();
        $namaPenyetor = $userData ? $userData['nama'] : $data['user'];

        // Catat pemasukan ke tabel masuk
        $insertPemasukan = $db->prepare("INSERT INTO masuk (nproduk, jumlah, keterangan) VALUES (?, ?, ?)");
        $insertPemasukan->execute([$id_produk, $data['tersedia'], $namaPenyetor]);

        if ($insert && $insertPemasukan) {
            // Hapus data dari tabel jual setelah dipindahkan
            $delete = $db->prepare("DELETE FROM jual WHERE idjual = ?");
            $delete->execute([$idjual]);

            echo "<script>
                    alert('Produk berhasil dikonfirmasi, dipindahkan, dan dicatat ke pemasukan!');
                    window.location.href='request.php'; 
                  </script>";
        } else {
            echo "<script>
                    alert('Gagal memindahkan data atau mencatat pemasukan.');
                    window.location.href='request.php'; 
                  </script>";
        }
    } else {
        echo "<script>
                alert('Data tidak ditemukan.');
                window.location.href='request.php'; 
              </script>";
    }
} else {
    echo "<script>
            alert('ID produk tidak valid!');
            window.location.href='request.php'; 
          </script>";
}
