<?php

// Fungsi untuk menyimpan username, atau password sementara
if (isset($_SESSION['admin'])){
    
} else {
    header('location:login.php');
}

?>