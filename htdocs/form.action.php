<?php
// Koneksi ke database
include "func.php";

if(isset($_POST["simpan"])){
    try {
        $nama_produk = trim($_POST["nama_produk"]);
        
        // Cek apakah nama produk sudah ada di database
        $query = $db->prepare("SELECT * FROM produk WHERE nama_produk = ?");
        $query->execute([$nama_produk]);
        
        if ($query->rowCount() > 0) {
            $_SESSION["notif"] = "Nama produk sudah ada, silakan gunakan nama lain.";
            header("Location: form.input.php");
            exit();
        }
        
        // Lokasi gambar yang akan disimpan
        $targetDir = "images/";
        $targetFile = $targetDir . basename($_FILES["gambar"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Cek apakah file yang diupload adalah gambar
        $check = getimagesize($_FILES["gambar"]["tmp_name"]);
        if($check === false) {
            echo "File yang diupload bukan gambar.";
            $uploadOk = 0;
        }
        
        // Memeriksa ukuran gambar dan format
        if ($_FILES["gambar"]["size"] > 5000000) {
            echo "Maaf, ukuran file terlalu besar.";
            $uploadOk = 0;
        }

        if(!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
            echo "Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "Maaf, file tidak dapat diupload.";
        } else {
            // Apabila gambar sudah terupload maka pindahkan data ke tabel produk
            if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $targetFile)) {
                $db->beginTransaction();

                $q = $db->prepare("INSERT INTO produk (nama_produk, kategori, stok, harga, gambar) VALUES (?, ?, ?, ?, ?)");
                $q->execute([$nama_produk, $_POST["kategori"], $_POST["stok"], $_POST["harga"], basename($targetFile)]);

                $idproduk = $db->lastInsertId();
                $keterangan = "Admin";
                $jumlah = $_POST["stok"];

                $adminid = $_SESSION['owner'];
                $queryAdmin = $db->prepare("SELECT nama FROM admin WHERE adminname = :username");
                $queryAdmin->bindParam(':username', $adminid);
                $queryAdmin->execute();
                $adminData = $queryAdmin->fetch();
                $adminName = $adminData ? $adminData['nama'] : 'Tidak Diketahui';

                // Apabila data sudah dipindah ke tabel produk maka tambahkan juga data ke tabel masuk sebagai pemasukan produk baru
                $p = $db->prepare("INSERT INTO masuk (nproduk, keterangan, jumlah) VALUES (?, ?, ?)");
                $p->execute([$idproduk, $adminName, $jumlah]);
                
                $db->commit();
                
                $_SESSION["notif"] = "Data Berhasil Disimpan.";
                header("Location: table.php");
                die();
            } else {
                echo "Maaf, terjadi kesalahan saat mengupload file.";
            }
        }
    } catch(Exception $e) {
        $db->rollBack();
        echo "Error : " . $e->getMessage();
    }
}


if(isset($_POST["penjualan"])){
    try {
        // Pastikan folder 'images' ada dan dapat ditulisi
        $targetDir = "images/";
        $targetFile = $targetDir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        
        // Membersihkan input angka dari pemisah ribuan
        $harga = str_replace(".", "", $_POST['harga']);
        $profit = str_replace(".", "", $_POST['profit']);
        $hargajual = str_replace(".", "", $_POST['hargajual']);

        // Cek apakah file gambar adalah gambar yang valid
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check === false) {
            echo "File yang diupload bukan gambar.";
            $uploadOk = 0;
        }

        // Cek ukuran file (misalnya, maksimal 5MB)
        if ($_FILES["image"]["size"] > 5000000) {
            echo "Maaf, ukuran file terlalu besar.";
            $uploadOk = 0;
        }

        // Cek format file
        if(!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
            echo "Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.";
            $uploadOk = 0;
        }

        // Cek jika $uploadOk diatur ke 0 oleh kesalahan
        if ($uploadOk == 0) {
            echo "Maaf, file tidak dapat diupload.";
        } else {
            // Jika semua cek lolos, coba untuk mengupload file
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                // Siapkan query untuk menyimpan data ke database
                $q = $db->prepare("INSERT INTO jual (user, produk, category, tersedia, hargajual, harga, profit, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $q->execute([$_POST["user"], $_POST["produk"], $_POST["category"], $_POST["tersedia"], $hargajual, $harga, $profit, basename($targetFile)]);

                $_SESSION["notif"] = "Data Berhasil Disimpan.";
                header("Location: jual.php");
                die();
            } else {
                echo "Maaf, terjadi kesalahan saat mengupload file.";
            }
        }

    } catch(Exception $e) {
        echo "Error : " . $e->getMessage();
    }
}

// Fungsi untuk menarik pengajuan produk yang ingin dijual
if(isset($_GET["action"]) && $_GET["action"] == "tarikpeng" && isset($_GET["idjual"])) {
    try {
        $q = $db->prepare("DELETE FROM jual WHERE produk = ?");
        $q->execute([$_GET["idjual"]]);
        $_SESSION["notif"] = "Data Berhasil dihapus.";
        header("Location: jual.php");
        die();
    } catch(Exception $e) {
        $_SESSION["notif"] = "Data Gagal dihapus. Error: " . $e->getMessage();
        header("Location: jual.php");
        die();
    }
}

// Fungsi untuk menambah pemasukan produk
if(isset($_POST["bmasuk"])){
    try {
        // Mulai transaksi
        $db->beginTransaction();
        
        // Simpan data ke tabel 'masuk'
        $q = $db->prepare("INSERT INTO masuk (nproduk, keterangan, jumlah) VALUES (?, ?, ?)");
        $q->execute([$_POST["nproduk"], $_POST["keterangan"], $_POST["jumlah"]]);
        
        // Update jumlah stok di tabel 'produk'
        $update = $db->prepare("UPDATE produk SET stok = stok + ? WHERE id = ?");
        $update->execute([$_POST["jumlah"], $_POST["nproduk"]]);
        
        // Commit transaksi jika semua berhasil
        $db->commit();
        
        $_SESSION["notif"] = "Data Berhasil Disimpan dan Stok Diperbarui.";
        header("Location: pemasukan.php");
        die();
    } catch (Exception $e) {
        // Rollback jika terjadi error
        $db->rollBack();
        echo "Error: " . $e->getMessage();
    }
}

// Fungsi untuk menambah pengeluaran produk
if(isset($_POST["bkeluar"])){
    try {
        // Mulai transaksi
        $db->beginTransaction();
        
        // Simpan data ke tabel 'masuk'
        $q = $db->prepare("INSERT INTO keluar (nproduk, keterangan, pjawab, jumlah) VALUES (?, ?, ?, ?)");
        $q->execute([$_POST["nproduk"], $_POST["keterangan"], $_POST["pjawab"], $_POST["jumlah"]]);
        
        // Update jumlah stok di tabel 'produk'
        $update = $db->prepare("UPDATE produk SET stok = stok - ? WHERE id = ?");
        $update->execute([$_POST["jumlah"], $_POST["nproduk"]]);
        
        // Commit transaksi jika semua berhasil
        $db->commit();
        
        $_SESSION["notif"] = "Data Berhasil Disimpan dan Stok Diperbarui.";
        header("Location: pengeluaran.php");
        die();
    } catch (Exception $e) {
        // Rollback jika terjadi error
        $db->rollBack();
        echo "Error: " . $e->getMessage();
    }
}

// Fungsi untuk menghapus suatu produk
if(isset($_GET["action"]) && $_GET["action"] == "hapus" && isset($_GET["id"])) {
    try {
        $q = $db->prepare("DELETE FROM produk WHERE nama_produk = ?");
        $q->execute([$_GET["id"]]);
        $_SESSION["notif"] = "Data Berhasil dihapus.";
        header("Location: table.php");
        die();
    } catch(Exception $e) {
        $_SESSION["notif"] = "Data Gagal dihapus. Error: " . $e->getMessage();
        header("Location: table.php");
        die();
    }
}

// Fungsi untuk menghapus pemesanan user dari web admin yang belum terkonfirmasi
if(isset($_GET["action"]) && $_GET["action"] == "hapuspemesanan" && isset($_GET["idpemesanan"])) {
    try {
        $q = $db->prepare("DELETE FROM pemesanan WHERE idpemesanan = ?");
        $q->execute([$_GET["idpemesanan"]]);
        $_SESSION["notif"] = "Data Berhasil dihapus.";
        header("Location: pemesanan.php");
        die();
    } catch(Exception $e) {
        $_SESSION["notif"] = "Data Gagal dihapus. Error: " . $e->getMessage();
        header("Location: pemesanan.php");
        die();
    }
}

// Fungsi untuk menghapus pengajuan penjualan dari user dari web admin
if(isset($_GET["action"]) && $_GET["action"] == "hapuspeng" && isset($_GET["idjual"])) {
    try {
        $q = $db->prepare("DELETE FROM jual WHERE produk = ?");
        $q->execute([$_GET["idjual"]]);
        $_SESSION["notif"] = "Data Berhasil dihapus.";
        header("Location: request.php");
        die();
    } catch(Exception $e) {
        $_SESSION["notif"] = "Data Gagal dihapus. Error: " . $e->getMessage();
        header("Location: request.php");
        die();
    }
}

// Menghapus pemesanan user yang belum terkonfirmasi dari web user (Membatalkan Pesanan)
if(isset($_GET["action"]) && $_GET["action"] == "hapuspemesananuser" && isset($_GET["idpemesanan"])) {
    try {
        $q = $db->prepare("DELETE FROM pemesanan WHERE idpemesanan = ?");
        $q->execute([$_GET["idpemesanan"]]);
        $_SESSION["notif"] = "Data Berhasil dihapus.";
        header("Location: myorder.php");
        die();
    } catch(Exception $e) {
        $_SESSION["notif"] = "Data Gagal dihapus. Error: " . $e->getMessage();
        header("Location: pemesanan.php");
        die();
    }
}


?>