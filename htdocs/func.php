<?php

//Ganti dengan database yang kalian miliki
session_start();
    //Declarated Variable
    define('DB_HOST','sql207.ezyro.com');
    define('DB_NAME','ezyro_38238173_deka');
    define('DB_USER','ezyro_38238173');
    define('DB_PASS','9f8b2353342');

    //Try to Connected
    try{
        $db=new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER,DB_PASS);
        //echo "Database Connected";
    }catch(PDOException $e){
        echo "Database Not Connected -> ".$e->getMessage();
        exit;
    }

?>

