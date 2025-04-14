<?php

// Funsgsi untuk menyimpan username, dan password sementara
if (isset($_SESSION['owner'])){
    
} else {
    header('location:ownlogin.php');
}

?>