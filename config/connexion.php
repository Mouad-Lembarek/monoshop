<?php
    $dsn="mysql:host=localhost;dbname=monoshop";
    $user="root";
    $password="root";
    try{
        $access=new PDO($dsn,$user,$password);
    }catch(PDOException $e){
        echo "Erreur : ".$e->getMessage()."<br>";
    }
?>