<?php

// Fungsi untuk logout dari web
session_start();
session_destroy();
header('location:login.php');

?>