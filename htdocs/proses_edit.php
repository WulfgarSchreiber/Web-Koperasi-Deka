<?php

session_start();

include 'func.php';

if(isset($_POST["ubah"])){
    try {
        $id = $_POST["id"];
        $nama_produk = $_POST["nama_produk"];

        // Cek apakah nama produk sudah ada di database, kecuali untuk ID yang sedang diedit
        $cek = $db->prepare("SELECT COUNT(*) FROM produk WHERE nama_produk = ? AND id != ?");
        $cek->execute([$nama_produk, $id]);
        $count = $cek->fetchColumn();
        
        if ($count > 0) {
            echo "<script>alert('Nama produk sudah ada, silakan gunakan nama lain.'); window.location.href='table.php';</script>";
            exit();
        }
        
        $fields = [];
        $values = [];

        // Periksa setiap input, hanya update jika tidak kosong
        if (!empty($nama_produk)) {
            $fields[] = "nama_produk=?";
            $values[] = $nama_produk;
        }

        if (!empty($_POST["kategori"])) {
            $fields[] = "kategori=?";
            $values[] = $_POST["kategori"];
        }

        if (!empty($_POST["stok"])) {
            $fields[] = "stok=?";
            $values[] = $_POST["stok"];
        }

        if (!empty($_POST["harga"])) {
            $fields[] = "harga=?";
            $values[] = $_POST["harga"];
        }

        // Handle upload gambar jika ada
        if (!empty($_FILES["gambar"]["name"])) {
            $targetDir = "images/"; // Folder penyimpanan
            $fileName = time() . "_" . basename($_FILES["gambar"]["name"]);
            $targetFile = $targetDir . $fileName;
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            // Memerika file yang diupload adalah gambar
            $check = getimagesize($_FILES["gambar"]["tmp_name"]);
            if($check === false) {
                $_SESSION["notif"] = "File yang diupload bukan gambar.";
                $uploadOk = 0;
            }

            // Mememeriksa ukuran gambar
            if ($_FILES["gambar"]["size"] > 5000000) {
                $_SESSION["notif"] = "Maaf, ukuran file terlalu besar.";
                $uploadOk = 0;
            }

            // Memeriksa format gmabar
            if(!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
                $_SESSION["notif"] = "Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.";
                $uploadOk = 0;
            }

            if ($uploadOk) {
                if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $targetFile)) {
                    $fields[] = "gambar=?";
                    $values[] = $fileName;
                } else {
                    $_SESSION["notif"] = "Gagal mengunggah gambar.";
                    header("Location: table.php");
                    exit();
                }
            } else {
                header("Location: table.php");
                exit();
            }
        }

        // Data produk akan diupdate
        if (!empty($fields)) {
            $sql = "UPDATE produk SET " . implode(", ", $fields) . " WHERE id=?";
            $values[] = $id;
            $q = $db->prepare($sql);
            $q->execute($values);
            $_SESSION["notif"] = "Data Berhasil diperbaharui.";
        } else {
            $_SESSION["notif"] = "Tidak ada perubahan yang dilakukan.";
        }

        header("Location: table.php");
        exit();

    } catch(Exception $e) {
        echo "Error : ".$e->getMessage();
    }
}

?>