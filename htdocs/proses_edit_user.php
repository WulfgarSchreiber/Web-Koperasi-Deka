<?php

session_start();

include 'func.php';

if(isset($_POST["ubah"])){
    try {
        $id = $_POST["id"];
        $username = $_POST["username"];

        // Cek apakah nama produk sudah ada di database, kecuali untuk ID yang sedang diedit
        $cek = $db->prepare("SELECT COUNT(*) FROM login WHERE username = ? AND id != ?");
        $cek->execute([$username, $id]);
        $count = $cek->fetchColumn();
        
        $fields = [];
        $values = [];

        // Periksa setiap input, hanya update jika tidak kosong
        if (!empty($_POST["nama"])) {
            $fields[] = "nama=?";
            $values[] = $_POST["nama"];
        }


        if (!empty($_POST["telepon"])) {
            $fields[] = "telepon=?";
            $values[] = $_POST["telepon"];
        }

        if (!empty($_POST["password"])) {
            $fields[] = "password=?";
            $values[] = $_POST["password"];
        }

        // Fungsi untuk merubah data user
        if (!empty($fields)) {
            $sql = "UPDATE login SET " . implode(", ", $fields) . " WHERE id=?";
            $values[] = $id;
            $q = $db->prepare($sql);
            $q->execute($values);
            $_SESSION["notif"] = "Data Berhasil diperbaharui.";
        } else {
            $_SESSION["notif"] = "Tidak ada perubahan yang dilakukan.";
        }

        header("Location: userview.php");
        exit();

    } catch(Exception $e) {
        echo "Error : ".$e->getMessage();
    }
}

?>